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
        })->orderBy('updated_at', 'asc');

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

            // If no instructor is assigned, display 'No Instructors'
            if (empty($section->teaches)) {
                $instructorName = 'No Instructors';
            } else {
                $instructorName = $section->teaches->instructor->user->getName();
            }

            $timings = sprintf('%s - %s', $section->time_start, $section->time_end);

            return [
                'id' => $section->id,
                'prefix' => $section->prefix,
                'number' => $section->number,
                'section' => $section->section,
                'year' => $section->year,
                'session' => $section->session,
                'term' => $section->term,
                'formattedName' => $formattedName,
                'departmentName' => $section->area->name ?? 'Unknown',
                'instructorName' => $instructorName,
                'enrolled' => $section->enroll_end,
                'dropped' => $section->dropped,
                'room' => $section->room,
                'timings' => $timings,
                'capacity' => $section->capacity,
                'averageRating' => $averageRating,
            ];
        });

        Log::info('Fetched Course Sections:', ['count' => $courseSections->count(), 'data' => $courseSections]);

        $areas = Area::all();

        $tas = TeachingAssistant::with(['courseSections.teaches.instructor.user'])
            ->get()
            ->map(function ($ta) {
                return (object)[
                    'name' => $ta->name,
                    'email' => $ta->email,
                    'rating' => $ta->rating,
                    'taCourses' => $ta->courseSections->map(function ($course) {
                        return $course->prefix . ' ' . $course->number . ' ' . $course->section;
                    })->implode(', '),
                    'instructorName' => $ta->courseSections->map(function ($course) {
                        // ---- OLD CODE ----
                        // return optional($course->teaches->instructor->user)->firstname . ' ' . optional($course->teaches->instructor->user)->lastname;

                        //   --- NEW CODE

                        if ($course->teaches && $course->teaches->instructor && $course->teaches->instructor->user) {
                            $instructor = $course->teaches->instructor->user;
                            return $instructor->firstname . ' ' . $instructor->lastname;
                        } else {
                            return 'N/A';
                        }
                    })->implode(', ')
                ];
            });

            $sortField = 'courseName';
            $sortDirection = 'asc';
            $courses = CourseSection::all();

            return view('course-details', compact('courseSections', 'userRole', 'user', 'sortField', 'sortDirection', 'areaId', 'areas', 'tas', 'activeTab', 'courses'));
        }



    public function getTeachingAssistants() {
        $tas = TeachingAssistant::select('id', 'name')->get();
        return response()->json($tas);
    }

    public function getInstructors() {
        $instructors = User::join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->where('user_roles.role', 'instructor')
            ->select('users.id', 'users.firstname', 'users.lastname')
            ->get();

        return response()->json($instructors);
    }

    public function getCoursesByInstructor($instructorId) {
        $courses = CourseSection::whereHas('teaches', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->get(['id', 'prefix', 'number', 'section', 'year', 'session', 'term']);

        return response()->json($courses);
    }

    public function assignTA(Request $request){
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

    public function createTA(Request $request){
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


    public function save(Request $request) {
        try {
            $ids = $request->input('ids', []);
            $courseNames = $request->input('courseNames', []);
            $enrolledStudents = $request->input('enrolledStudents', []);
            $droppedStudents = $request->input('droppedStudents', []);
            $courseCapacities = $request->input('courseCapacities', []);

            Log::info('Request Data:', [
                'ids' => $ids,
                'courseNames' => $courseNames,
                'enrolledStudents' => $enrolledStudents,
                'droppedStudents' => $droppedStudents,
                'courseCapacities' => $courseCapacities,
            ]);

            $arrayLengths = [count($ids), count($courseNames), count($enrolledStudents), count($droppedStudents), count($courseCapacities)];
            if (count(array_unique($arrayLengths)) !== 1) {
                return response()->json(['message' => 'Data arrays are not of the same length.'], 400);
            }

            $updatedSections = [];

            for ($i = 0; $i < count($ids); $i++) {
                $courseSection = CourseSection::find($ids[$i]);

                if ($courseSection) {
                    $courseSection->enroll_end = $enrolledStudents[$i];
                    $courseSection->dropped = $droppedStudents[$i];
                    $courseSection->capacity = $courseCapacities[$i];
                    // Assuming you have a method to update the course name or other fields as needed
                    // $courseSection->name = $courseNames[$i]; // Uncomment if you want to update the course name
                    $courseSection->save();

                    $updatedSections[] = $courseSection;
                } else {
                    Log::error('Invalid course section ID', [
                        'course_section_id' => $ids[$i],
                    ]);
                }
            }

            return response()->json(['message' => 'Courses updated successfully.', 'updatedSections' => $updatedSections]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Failed to update courses.'], 500);
        }
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
