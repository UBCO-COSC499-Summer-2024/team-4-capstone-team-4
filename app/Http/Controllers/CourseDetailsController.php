<?php

namespace App\Http\Controllers;

use App\Models\CourseSection;
use Illuminate\Http\Request;
use App\Models\SeiData;
use App\Models\User;
use App\Models\Teach;
use Illuminate\Support\Facades\Log;

class CourseDetailsController extends Controller {

    public function show(Request $request){
        $courseSections = CourseSection::with('area')->get()->map(function ($section, $index) {
            $seiData = SeiData::where('course_section_id', $section->id)->first();
            $averageRating = $seiData ? $this->calculateAverageRating($seiData->questions) : 0;

            $formattedName = sprintf('%s %s - %s%s %s', 
                $section->name, 
                $section->section, 
                $section->year, 
                $section->session, 
                $section->term
            );

            return (object) [
                'id' => $section->id,
                'name' => $formattedName,
                'departmentName' => $section->area ? $section->area->name : 'Unknown',
                'enrolled' => $section->enrolled,
                'dropped' => $section->dropped,
                'capacity' => $section->capacity,
                'averageRating' => $averageRating,
            ];
        });

        $courses = CourseSection::all(); 
        $instructors = User::all(); 

        $sortField = 'courseName';
        $sortDirection = 'asc';

        return view('course-details', compact('courseSections', 'courses', 'instructors', 'sortField', 'sortDirection'));
    }

    public function save(Request $request){
        $ids = $request->input('ids', []);
        $courseNames = $request->input('courseNames', []);
        $enrolledStudents = $request->input('enrolledStudents', []);
        $droppedStudents = $request->input('droppedStudents', []);
        $courseCapacities = $request->input('courseCapacities', []);

        // Log the request data for debugging
        Log::info('Request Data:', [
            'ids' => $ids,
            'courseNames' => $courseNames,
            'enrolledStudents' => $enrolledStudents,
            'droppedStudents' => $droppedStudents,
            'courseCapacities' => $courseCapacities,
        ]);

        $updatedSections = [];

        // Ensure all arrays have the same length
        $arrayLengths = [count($ids), count($courseNames), count($enrolledStudents), count($droppedStudents), count($courseCapacities)];
        if (count(array_unique($arrayLengths)) !== 1) {
            return response()->json(['message' => 'Data arrays are not of the same length.'], 400);
        }

        for ($i = 0; $i < count($ids); $i++) {
            // Check if the key exists before accessing the array
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
                $courseSection->name = $courseNames[$i];
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
    public function search(Request $request) {
        $query = $request->input('query');
        $courseSections = CourseSection::with('area')
            ->where('name', 'LIKE', "%$query%")
            ->orWhereHas('area', function($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%");
            })
            ->get()
            ->map(function ($section, $index) {
                $seiData = SeiData::where('course_section_id', $section->id)->first();
                $averageRating = $seiData ? $this->calculateAverageRating($seiData->questions) : 0;
    
                $formattedName = sprintf('%s %s - %s%s %s', 
                    $section->name, 
                    $section->section, 
                    $section->year, 
                    $section->session, 
                    $section->term
                );
    
                return [
                    'id' => $section->id,
                    'name' => $formattedName,
                    'departmentName' => $section->area ? $section->area->name : 'Unknown',
                    'enrolled' => $section->enrolled,
                    'dropped' => $section->dropped,
                    'capacity' => $section->capacity,
                    'averageRating' => $averageRating,
                ];
            });
    
        return response()->json($courseSections);
    }

    private function calculateAverageRating($questionsJson) {
        $questions = json_decode($questionsJson, true);
        if (is_array($questions) && !empty($questions)) {
            $ratings = array_filter(array_values($questions), function($value) {
                return is_numeric($value);
            });
            $averageRating = count($ratings) > 0 ? array_sum($ratings) / count($ratings) : 0;
            return round($averageRating, 2);
        }
        return 0;
    }
}