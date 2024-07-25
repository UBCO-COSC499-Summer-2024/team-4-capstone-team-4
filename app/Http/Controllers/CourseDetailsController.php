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

    Log::info('User Role:', ['role' => $userRole]);
    Log::info('Search Query:', ['query' => $query]);

    $courseSections = CourseSection::with('area')
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
        })
        ->get();

    $courseSections = $courseSections->map(function ($section) {
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

        return (object)[
            'id' => $section->id,
            'name' => $formattedName,
            'departmentName' => $section->area->name ?? 'Unknown',
            'enrolled' => $section->enrolled,
            'dropped' => $section->dropped,
            'capacity' => $section->capacity,
            'averageRating' => $averageRating,
        ];
    });

    Log::info('Fetched Course Sections:', ['count' => $courseSections->count(), 'data' => $courseSections]);
    Log::info('Processed Course Sections:', ['count' => $courseSections->count(), 'data' => $courseSections]);

    $instructors = User::whereHas('roles', function ($query) {
        $query->where('role', 'instructor');
    })->get();

    $areas = Area::all(); // Ensure you retrieve the areas from the database

    $tas = TeachingAssistant::with(['courseSections.teaches.instructor'])
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
                    return $course->teaches->instructor->user->firstname . ' ' . $course->teaches->instructor->user->lastname;
                })->implode(', ')
            ];
        });

    if ($request->ajax()) {
        return response()->json($courseSections);
    }

    $sortField = 'courseName';
    $sortDirection = 'asc';

    return view('course-details', compact('courseSections', 'userRole', 'user', 'sortField', 'sortDirection', 'instructors', 'areaId', 'tas', 'areas')); // Ensure 'areas' is included in the compact
}



    public function save(Request $request){
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

        $updatedSections = [];

        $arrayLengths = [count($ids), count($courseNames), count($enrolledStudents), count($droppedStudents), count($courseCapacities)];
        if (count(array_unique($arrayLengths)) !== 1) {
            return response()->json(['message' => 'Data arrays are not of the same length.'], 400);
        }

        for ($i = 0; $i < count($ids); $i++) {
            if (!isset($courseNames[$i]) || !isset($enrolledStudents[$i]) || !isset($droppedStudents[$i]) || !isset($courseCapacities[$i])) {
                Log::error('Missing array index', [
                    'index' => $i,
                    'courseNames' => $courseNames,
                    'enrolledStudents' => $enrolledStudents,
                    'droppedStudents' => $droppedStudents,
                    'courseCapacities' => $courseCapacities,
                ]);
                continue;
            }

            $courseSection = CourseSection::find($ids[$i]);
            if ($courseSection) {
                $nameParts = explode(' ', $courseNames[$i]);
                $courseSection->prefix = $nameParts[0];
                $courseSection->number = $nameParts[1];
                $courseSection->enrolled = (int)$enrolledStudents[$i];
                $courseSection->dropped = (int)$droppedStudents[$i];
                $courseSection->capacity = (int)$courseCapacities[$i];
                $courseSection->save();

                $updatedSections[] = $courseSection;
            } else {
                Log::error('Course section not found', ['id' => $ids[$i]]);
            }
        }

        return response()->json([
            'message' => 'Courses updated successfully.',
            'updatedSections' => $updatedSections
        ]);
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
