<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Class Leaderboard
 *
 * Manages rendering and filtering of a leaderboard of instructors based on selected areas and year.
 *
 * @package App\Livewire
 */
class Leaderboard extends Component {

    /**
     * The selected areas for filtering the leaderboard.
     *
     * @var array
     */
    public $selectedAreas = [];
    public $year;

    public function mount() {
        $this->year = date('Y');
    }

    /**
     * Render the leaderboard view with filtered instructor data.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render() {
        $deptId = UserRole::where('user_id', Auth::id())->firstWhere('role', 'dept_head')->department_id;
        $usersQuery = User::query();
        $areas = $this->selectedAreas;

        $usersQuery->whereHas('roles', function ($queryBuilder) {
            $queryBuilder->where('role', 'instructor');
        });

        $currentYear = $this->year;

        $usersQuery = $usersQuery->distinct()
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->leftJoin('role_assignments', 'user_roles.id', '=', 'role_assignments.instructor_id') // Corrected the column name
            ->leftJoin('service_roles', function ($join) {
                $join->on('role_assignments.service_role_id', '=', 'service_roles.id')
                    ->where('service_roles.archived', false);
            })
            ->leftJoin('teaches', 'user_roles.id', '=', 'teaches.instructor_id')
            ->leftJoin('course_sections', function ($join) {
                $join->on('teaches.course_section_id', '=', 'course_sections.id')
                    ->where('course_sections.archived', false);
            })
            ->leftJoin('areas', 'course_sections.area_id', '=', 'areas.id')
            ->leftJoin('extra_hours', function ($join) use ($currentYear) {
                $join->on('user_roles.id', '=', 'extra_hours.instructor_id') 
                    ->where('extra_hours.year', $currentYear)
                    ->where('extra_hours.archived', false);
            })
            ->leftJoin('instructor_performance', function ($join) use ($currentYear) {
                $join->on('user_roles.id', '=', 'instructor_performance.instructor_id')
                    ->where('instructor_performance.year', $currentYear);
            })
            ->where('areas.dept_id', $deptId);

            if (!empty($areas)) {
                $usersQuery->where(function ($query) use ($areas) {
                    $query->whereIn('areas.name', $areas)
                        ->orWhereIn('extra_hours.area_id', function ($query) use ($areas) {
                            $query->select('id')
                                ->from('areas')
                                ->whereIn('name', $areas);
                        })
                        ->orWhereIn('service_roles.area_id', function ($query) use ($areas) {
                            $query->select('id')
                                ->from('areas')
                                ->whereIn('name', $areas);
                        });
                });
            }

        $usersQuery->select('users.*', 'instructor_performance.instructor_id', 'instructor_performance.score')
            ->orderBy('instructor_performance.score', 'desc');

        $users = $usersQuery->get();

        return view('livewire.leaderboard', ['users' => $users]);
    }

    /**
     * Handle filtering of leaderboard data based on selected areas and year.
     *
     * @return void
     */
    public function filter() {
        $this->render();
    }
    public function clearFilter () {
        $this->selectedAreas = [];
        $this->render();
    }

    /**
    * Adds an ordinal suffix to a number to denote its position.
    *
    * Examples:
    * - 1 becomes 1st
    * - 2 becomes 2nd
    * - 3 becomes 3rd
    * - 4 becomes 4th
    * - and so on...
    *
    * @param int $number The number to which the ordinal suffix is added.
    * @return string The number with its ordinal suffix.
    */
    private function addOrdinalSuffix($number) {
        $suffix = '';
    
        if (!in_array(($number % 100), [11, 12, 13])) {
            switch ($number % 10) {
                case 1:
                    $suffix = 'st';
                    break;
                case 2:
                    $suffix = 'nd';
                    break;
                case 3:
                    $suffix = 'rd';
                    break;
                default:
                    $suffix = 'th';
                    break;
            }
        } else {
            $suffix = 'th';
        }
    
        return $number . $suffix;
    }
}


