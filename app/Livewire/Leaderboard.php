<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Class Leaderboard
 *
 * Manages rendering and filtering of a leaderboard of instructors based on selected areas.
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

    /**
     * Render the leaderboard view with filtered instructor data.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render() {
        $areas = $this->selectedAreas;

        $usersQuery = User::query();

        $usersQuery->whereHas('roles', function ($queryBuilder) {
            $queryBuilder->where('role', 'instructor');
        });

        if (!empty($areas)) {
            $usersQuery->whereHas('teaches.courseSection.area', function ($queryBuilder) use ($areas) {
                $queryBuilder->whereIn('name', $areas);
            });
        }

        $currentYear = date('Y');
        $usersQuery = $usersQuery->distinct()
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->leftJoin('teaches', 'user_roles.id', '=', 'teaches.instructor_id')
            ->leftJoin('course_sections', 'teaches.course_section_id', '=', 'course_sections.id')
            ->leftJoin('areas', 'course_sections.area_id', '=', 'areas.id')
            ->leftJoin(DB::raw("(SELECT * FROM instructor_performance WHERE year = $currentYear) as instructor_performance"), 'user_roles.id', '=', 'instructor_performance.instructor_id');

        $usersQuery->select('users.*', 'instructor_performance.instructor_id', 'instructor_performance.score')
            ->orderBy('instructor_performance.score', 'desc');

        $users = $usersQuery->get();

        return view('livewire.leaderboard', ['users' => $users]);
    }

    /**
     * Handle filtering of leaderboard data based on selected areas.
     *
     * @return void
     */
    public function filter() {
        $this->selectedAreas = $this->selectedAreas;
    }
}

