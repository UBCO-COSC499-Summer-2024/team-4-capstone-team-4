<?php

namespace App\Http\Controllers;

use App\Models\CourseSection;
use App\Models\TeachingAssistant;
use Illuminate\Http\Request;
use App\Models\SeiData;
use App\Models\User;
use App\Models\Teach;
use Illuminate\Support\Facades\Log;
use App\Models\Area;
use Mpdf\Mpdf;


class CourseDetailsController extends Controller
{
    public function show(Request $request, User $user)
{
    $authenticatedUser = $request->user();

    if (!$authenticatedUser || ($authenticatedUser->id !== $user->id && !$authenticatedUser->hasRoles(['admin', 'dept_head']))) {
        abort(403, 'Unauthorized access.');
    }

    $userRole = $user->roles->first()->role ?? 'guest';
    $query = $request->input('search', '');
    $areaId = $request->input('area_id', null);
    $activeTab = $request->input('activeTab'); // Get the active tab from the request

    Log::info('User Role:', ['role' => $userRole]);
    Log::info('Search Query:', ['query' => $query]);
    
    $courseSectionsQuery = CourseSection::with(['area', 'teaches.instructor.user'])
        ->when($userRole === 'instructor', function ($queryBuilder) use ($user) {
            $queryBuilder->whereHas('teaches', function ($query) use ($user) {
                $query->where('instructor_id', $user->id);
            });
        })
        ->when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->whereRaw('LOWER(prefix || \' \' || number || \' \' || section || \' - \' || year || session || \' \' || term) LIKE ?', ['%' . strtolower($query) . '%']);
            });
        })
        ->when($areaId, function ($queryBuilder) use ($areaId) {
            $queryBuilder->where('area_id', $areaId);
        });

    $courseSections = $courseSectionsQuery->paginate(7); // Apply pagination

    $courseSections->getCollection()->transform(function ($section) {
        $seiData = $section->seiData()->first() ?? null;
        $averageRating = $seiData ? $this->calculateAverageRating($seiData->questions) : 0;

        $formattedName = sprintf('%s %s %s - %s%s %s',
            $section->prefix,
            $section->number,
            $section->section,
            $section->year,
            $section->session,
            $section->term
        );

        $instructorName = optional($section->teaches->instructor->user)->firstname . ' ' . optional($section->teaches->instructor->user)->lastname;

        return (object)[
            'id' => $section->id,
            'name' => $formattedName,
            'departmentName' => $section->area->name ?? 'Unknown',
            'instructorName' => $instructorName,
            'enrolled' => $section->enroll_end,  // Use enroll_end here
            'dropped' => $section->dropped,
            'capacity' => $section->capacity,
            'averageRating' => $averageRating,
        ];
    });

    Log::info('Fetched Course Sections:', ['count' => $courseSections->count(), 'data' => $courseSections]);

    $areas = Area::all(); 

    $tas = TeachingAssistant::with(['courseSections.teaches.instructor.user'])
    ->paginate(7) // Apply pagination for TAs
    ->through(function ($ta) {
        return (object)[
            'name' => $ta->name,
            'email' => $ta->email,
            'rating' => $ta->rating,
            'taCourses' => $ta->courseSections->map(function ($course) {
                return $course->prefix . ' ' . $course->number . ' ' . $course->section;
            })->implode(', '),
            'instructorName' => $ta->courseSections->map(function ($course) {
                return optional($course->teaches->instructor->user)->firstname . ' ' . optional($course->teaches->instructor->user)->lastname;
            })->implode(', ')
        ];
    });

    if ($request->ajax()) {
        return response()->json($courseSections);
    }

    $sortField = 'courseName';
    $sortDirection = 'asc';
    $courses = CourseSection::all()->map(function ($section) {
        return (object)[
            'id' => $section->id,
            'name' => sprintf('%s %s %s - %s%s %s',
                $section->prefix,
                $section->number,
                $section->section,
                $section->year,
                $section->session,
                $section->term
            ),
        ];
    });

    return view('course-details', compact('courseSections', 'userRole', 'user', 'sortField', 'sortDirection', 'areaId', 'areas', 'tas','activeTab','courses'));
}

    
    public function getTeachingAssistants()
    {
        $tas = TeachingAssistant::select('id', 'name')->get();
        return response()->json($tas);
    }

public function getInstructors()
{
    $instructors = User::join('user_roles', 'users.id', '=', 'user_roles.user_id')
        ->where('user_roles.role', 'instructor')
        ->select('users.id', 'users.firstname', 'users.lastname')
        ->get();

    return response()->json($instructors);
}

public function getCoursesByInstructor($instructorId)
{
    $courses = CourseSection::whereHas('teaches', function ($query) use ($instructorId) {
        $query->where('instructor_id', $instructorId);
    })->get(['id', 'prefix', 'number', 'section', 'year', 'session', 'term']);

    return response()->json($courses);
}

public function assignTA(Request $request)
{
    $taId = $request->input('ta_id');
    $instructorId = $request->input('instructor_id');
    $courseId = $request->input('course_id');

    // Logic to assign the TA to the course
    $courseSection = CourseSection::find($courseId);
    if ($courseSection) {
        $courseSection->teachingAssistants()->attach($taId);
    }

    // Fetch updated TA data
    $tas = TeachingAssistant::with(['courseSections.teaches.instructor.user'])
        ->get()
        ->map(function ($ta) {
            return (object)[
                'name' => $ta->name,
                'rating' => $ta->rating,
                'taCourses' => $ta->courseSections->map(function ($course) {
                    return $course->prefix . ' ' . $course->number . ' ' . $course->section;
                })->implode(', '),
                'instructorName' => $ta->courseSections->map(function ($course) {
                    return optional($course->teaches->instructor->user)->firstname . ' ' . optional($course->teaches->instructor->user)->lastname;
                })->implode(', ')
            ];
        });

    return response()->json($tas);
}

public function createTA(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'rating' => 'required|numeric|min:0|max:5',
    ]);

    $ta = new TeachingAssistant();
    $ta->name = $validatedData['name'];
    $ta->rating = $validatedData['rating'];
    $ta->save();

    return response()->json(['message' => 'TA created successfully.', 'ta' => $ta]);
}

public function exportPdf(Request $request)
{
    try {
        $courseSections = CourseSection::all(); // Get the data to be exported

        $html = view('pdf.course-sections', compact('courseSections'))->render(); // Create the HTML view for PDF

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $filename = 'course_sections.pdf';
        $mpdf->Output($filename, 'D');

    } catch (\Exception $e) {
        Log::error('PDF export error: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to save PDF. Please try again.'], 500);
    }
}

    public function exportCSV()
    {
        // Your CSV export logic here
        $courseSections = CourseSection::with(['area', 'teaches.instructor.user'])->get();

        // Convert data to CSV format
        $csvData = "Course Name,Area,Instructor,Enrolled,Dropped,Capacity,SEI Data\n";
        foreach ($courseSections as $section) {
            $csvData .= "{$section->name},{$section->area->name},{$section->teaches->instructor->user->name},{$section->enrolled},{$section->dropped},{$section->capacity},{$section->averageRating}\n";
        }

        $csvFileName = 'courses.csv';
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$csvFileName}");
    }
    public function manageSeiData(Request $request, $courseId = null)
    {
        if ($request->isMethod('get')) {
            Log::info('Fetching SEI data for course section ID:', ['courseId' => $courseId]);
    
            $seiData = SeiData::where('course_section_id', $courseId)->first();
    
            if ($seiData) {
                Log::info('SEI data found:', ['seiData' => $seiData]);
    
                $questions = json_decode($seiData->questions, true);
                Log::info('Decoded questions:', ['questions' => $questions]);
    
                // Add course_section_id to the response for completeness
                $questions['course_section_id'] = $seiData->course_section_id;
                return response()->json($questions);
            }
    
            Log::warning('No SEI data found for course section ID:', ['courseId' => $courseId]);
            return response()->json([]);
        } elseif ($request->isMethod('post')) {
            Log::info('Saving SEI data', ['requestData' => $request->all()]);
    
            $data = $request->all();
    
            foreach ($data['course_id'] as $index => $courseId) {
                $questions = [];
                for ($i = 1; $i <= 6; $i++) {
                    $questions['q' . $i] = $data['q' . $i][$index] ?? null;
                }
    
                SeiData::updateOrCreate(
                    ['course_section_id' => $courseId],
                    ['questions' => json_encode($questions)]
                );
            }
    
            Log::info('SEI data saved successfully');
            return response()->json(['message' => 'SEI data saved successfully.']);
        }
    }
    
    

public function save(Request $request)
{
    $taIds = $request->input('ta_id', []);
    $instructorIds = $request->input('instructor_id', []);
    $courseIds = $request->input('course_id', []);

    Log::info('Request Data:', [
        'ta_ids' => $taIds,
        'instructor_ids' => $instructorIds,
        'course_ids' => $courseIds,
    ]);

    $arrayLengths = [count($taIds), count($instructorIds), count($courseIds)];
    if (count(array_unique($arrayLengths)) !== 1) {
        return response()->json(['message' => 'Data arrays are not of the same length.'], 400);
    }

    for ($i = 0; $i < count($taIds); $i++) {
        if (!isset($taIds[$i]) || !isset($instructorIds[$i]) || !isset($courseIds[$i])) {
            Log::error('Missing array index', [
                'index' => $i,
                'ta_ids' => $taIds,
                'instructor_ids' => $instructorIds,
                'course_ids' => $courseIds,
            ]);
            continue;
        }

        // Logic to save the TA assignment
        $courseSection = CourseSection::find($courseIds[$i]);
        $teachingAssistant = TeachingAssistant::find($taIds[$i]);
        $instructor = User::find($instructorIds[$i]);

        if ($courseSection && $teachingAssistant && $instructor) {
            // Assuming you have a pivot table or a model relationship to save this data
            // Example:
            $courseSection->teachingAssistants()->attach($teachingAssistant->id, [
                'instructor_id' => $instructor->id
            ]);
        } else {
            Log::error('Invalid data for TA assignment', [
                'course_section' => $courseSection,
                'teaching_assistant' => $teachingAssistant,
                'instructor' => $instructor,
            ]);
        }
    }

    return response()->json(['message' => 'TAs assigned successfully.']);
}


    private function calculateAverageRating($questionsJson){
        $questions = json_decode($questionsJson, true);
        if (is_array($questions) && !empty($questions)) {
            $ratings = array_filter(array_values($questions), function ($value) {
                return is_numeric($value);
            });
            $averageRating = count($ratings) > 0 ? array_sum($ratings) / count($ratings) : 0;
            return round($averageRating, 2);
        }
        return 0;
    }
}
