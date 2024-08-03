<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CourseSection;
use App\Models\TeachingAssistant;
use Illuminate\Http\Request;
use App\Models\SeiData;
use App\Models\User;
use App\Models\Teach;
use Illuminate\Support\Facades\Log;
use App\Models\Area;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Auth;

class TaDetails extends Component
{
    use WithPagination;

    public $sortField = 'taName'; // default 
    public $sortDirection = 'asc'; //default

    public $searchTerm = '';
    public $areaId = null;
    public $pagination; 

    public function render()
    {
        $user = Auth::user();
        if($user->hasRole('instructor') && !$user->hasRoles(['dept_head', 'dept_staff', 'admin'])){
            $userRole = 'instructor';
        }else{
            $userRole = 'other';
        }

        $query = $this->searchTerm;
        $areaId = $this->areaId;

        $areas = Area::all();

        $tasQuery = TeachingAssistant::with(['courseSections.area', 'courseSections.teaches.instructor.user'])
        ->when($userRole === 'instructor', function ($queryBuilder) use ($user) {
            $queryBuilder->whereHas('teaches', function ($query) use ($user) {
                $query->where('instructor_id', $user->roles->where('role', 'instructor')->first()->id);
            });
        })
        ->when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->whereRaw('LOWER(teaching_assistants.name) LIKE ?', ['%' . strtolower($query) . '%']);
            });
        })
        ->when($areaId, function ($queryBuilder) use ($areaId) {
            $queryBuilder->whereHas('courseSections', function ($query) use ($areaId) {
                $query->where('area_id', $areaId);
            });
        });

        //pagination
        switch ($this->pagination) {
            case 25:
                $tas = $tasQuery->paginate(25);
                break;
            case 50:
                $tas = $tasQuery->paginate(50);
                break;
            case 100:
                $tas = $tasQuery->paginate(100);
                break;
            case 'all':
                $tas = $tasQuery->get();
                break;
            default: 
                $tas = $tasQuery->paginate(10);
                break;
        }

        $tas->transform(function ($ta) {
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
    
        $sortField = 'courseName';
        $sortDirection = 'asc';
        $courses = CourseSection::all();
    
        return view('livewire.ta-details', compact('sortField', 'sortDirection', 'areaId', 'areas', 'tas', 'courses'));
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
}
