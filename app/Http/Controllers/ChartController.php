<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRole;
use App\Models\Department;
use App\Models\Area;
use App\Models\User;
use App\Models\AreaPerformance;
use App\Models\DepartmentPerformance;
use App\Models\RoleAssignment;
use App\Models\ServiceRole;
use App\Models\ExtraHour;
use App\Models\CourseSection;
use App\Models\Teach;
use App\Models\InstructorPerformance;
use Illuminate\Support\Facades\DB;

/**
 * Controller class for handling chart-related actions.
 */
class ChartController extends Controller {

    /**
     * Show the chart dashboard for the authenticated user.
     *
     * This method determines the user roles and fetches relevant performance
     * data based on the roles. It then prepares charts and other data to be
     * displayed on the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function showChart(Request $request, $instructor_id = null, $name = null, $switch = null, $year = null, $area = null) {
        $currentMonth = date('F');
        $currentYear = date('Y');
        $userId = Auth::id();
        $userRoles = UserRole::where('user_id', $userId)->get();
        $chosenInstructor = $instructor_id ?: $request->query('instructor_id');
        $switch = $switch ?: $request->query('switch');
        $name = $name ?: $request->query('name');
        $year = $year ?: $request->query('year');
        $area = $area ?: $request->query('area');
        
        $isInstructor = false;
        $isDeptHead = false;
        $isDeptStaff = false;
        $isAdmin = false;

        if ($year) {
            $currentYear = $year;
        }

        if ($area) {
            $area = json_decode($area, true);
        }

        if ($chosenInstructor) {
            $instructorRoleId = $chosenInstructor;
            $isInstructor = true;
        }

        elseif ($switch) {

            $instructorRole = $userRoles->firstWhere('role', 'instructor');
            if ($instructorRole) {
                $instructorRoleId = $instructorRole->id;
                $isInstructor = true;
            }

        }

        else {
            $dept = null;

            $adminRole = $userRoles->firstWhere('role', 'admin');
            if ($adminRole) {
                $isAdmin = true;
            }

            $deptHeadRole = $userRoles->firstWhere('role', 'dept_head');
            if ($deptHeadRole) {
                $deptHeadRoleId = $deptHeadRole->id;
                $dept = $deptHeadRole->department_id;
                $isDeptHead = true;
            }

            $deptStaffRole = $userRoles->firstWhere('role', 'dept_staff');
            if ($deptStaffRole) {
                $deptStaffRoleId = $deptStaffRole->id;
                $dept = $deptStaffRole->department_id;
                $isDeptStaff = true;
            }

            $instructorRole = $userRoles->firstWhere('role', 'instructor');
            if ($instructorRole) {
                $instructorRoleId = $instructorRole->id;
                $isInstructor = true;
            }
        }

        if ($isDeptHead || $isDeptStaff) {
            $deptPerformance = DepartmentPerformance::where('dept_id', $dept)
                ->where('year', $currentYear)
                ->first();

            if ($deptPerformance === null) {
                $this->createPerformance($dept, "dept", $currentYear);
                $deptPerformance = DepartmentPerformance::where('dept_id', $dept)
                ->where('year', $currentYear)
                ->first();
            }

            $dataLabels = [];
            $areaPerformances = [];

            $deptName = Department::where('id', $dept)->value('name');
            $dataLabels[] = $deptName;

            $totalHours = [];
            $departmentHours = json_decode($deptPerformance->total_hours, true);
            $totalHours[] = array_values($departmentHours);

            $dept_areas = Area::where('dept_id', $dept)->get();
            foreach ($dept_areas as $dept_area) {
                if ($area && $area['id'] != null) {
                    if ($dept_area->id === $area['id']) {
                        $dataLabels[] = $dept_area->name;

                        $performance = AreaPerformance::where('area_id', $dept_area->id)
                        ->where('year', $currentYear)
                        ->first();

                        if ($performance === null) {
                            $this->createPerformance($dept_area->id, "area", $currentYear);
                        }

                        if ($performance) {
                            $areaPerformances[] = $performance;
                            $totalHours[] = array_values(json_decode($performance->total_hours, true));
                        }
                    }
                }

                else {
                    $dataLabels[] = $dept_area->name;

                    $performance = AreaPerformance::where('area_id', $dept_area->id)
                    ->where('year', $currentYear)
                    ->first();

                    if ($performance === null) {
                        $this->createPerformance($dept_area->id, "area", $currentYear);
                    }

                    if ($performance) {
                        $areaPerformances[] = $performance;
                        $totalHours[] = array_values(json_decode($performance->total_hours, true));
                    }
                }
            }

            if ($area && $area['id'] != null) {
                $areas = $this->getAreas($dept);
                $deptAssignmentCount = $this->countDeptAssignments($dept_areas, $currentYear, $area);
                $leaderboard = $this->leaderboardPrev($dept, $currentYear, false, $area);
                $deptYears = $this->getPerformanceYears($dept, true, $area);
                $deptPerformance = AreaPerformance::where('area_id', $area['id'])->where('year', $currentYear)->first();

                $chart1 = $this->deptLineChart($dataLabels, $totalHours);
                $chart2 = $this->performancePieChart(array_slice($deptAssignmentCount[1], 0, 5, true), $area['name'] . " Service Role Preview", "Hours", "AreaRolePieChart");
                $chart3 = $this->performancePieChart(array_slice($deptAssignmentCount[3], 0, 5, true), $area['name'] . " Extra Hours Preview", "Hours", "AreaExtraPieCHart");

                return view('dashboard', compact('chart1', 'chart2', 'chart3', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'isAdmin', 'deptAssignmentCount', 'deptPerformance', 'leaderboard', 'deptYears', 'currentYear', 'areas', 'area'));
            }

            $areas = $this->getAreas($dept);
            $deptAssignmentCount = $this->countDeptAssignments($dept_areas, $currentYear, null);
            $leaderboard = $this->leaderboardPrev($dept, $currentYear, false, null);
            $deptYears = $this->getPerformanceYears($dept, true, null);

            $chart1 = $this->deptLineChart($dataLabels, $totalHours);
            $chart2 = $this->departmentPieChart($deptAssignmentCount[1], "Total Service Roles by Area", "Service Roles", "DeptRolePieChart");
            $chart3 = $this->departmentPieChart($deptAssignmentCount[3], "Total Extra Hours by Area", "Extra Hours", "DeptExtraPieCHart");
            $chart4 = $this->departmentPieChart($deptAssignmentCount[5], "Total Course Sections by Area", "Course Sections", "DeptCoursePieChart");

            return view('dashboard', compact('chart1', 'chart2', 'chart3', 'chart4', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'isAdmin', 'deptAssignmentCount', 'deptPerformance', 'leaderboard', 'deptYears', 'currentYear', 'areas', 'area'));
            
        } 
        
        elseif ($isInstructor) {
            $performance = InstructorPerformance::where('instructor_id', $instructorRoleId)
                ->where('year', $currentYear)
                ->first();

            if ($performance === null) {
                $this->createPerformance($instructorRoleId, "instructor", $currentYear);
                $performance = InstructorPerformance::where('instructor_id', $instructorRoleId)
                    ->where('year', $currentYear)
                    ->first();
            }

            $hasTarget = false;
            if ($performance->target_hours !== null) {
                $hasTarget = true;
            }

            $assignmentCount = $this->countAssignments($instructorRoleId, $hasTarget, $currentYear, $currentMonth);
            $ranking = $this->getRank($instructorRoleId, $currentYear, $performance->score);
            $years = $this->getPerformanceYears($instructorRoleId, false);

            $chart1 = $this->instructorLineChart($performance, $hasTarget);
            $chart2 = $this->performancePieChart(array_slice($assignmentCount[0], 0, 5, true), "Service Roles Preview", "Hours", "RolePieChart");
            $chart3 = $this->performancePieChart(array_slice($assignmentCount[1], 0, 5, true), "Extra Hours Preview", "Hours", "ExtraPieChart");

            if ($hasTarget) {
                $chart4 = $this->instructorProgressBar($performance, $currentMonth);

                if ($chosenInstructor) {
                    return view('performance', compact('chart1', 'chart2', 'chart3', 'chart4', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'isAdmin', 
                                'hasTarget', 'assignmentCount', 'ranking', 'performance', 'name', 'years', 'currentYear'));
                }

                else {
                    return view('dashboard', compact('chart1', 'chart2', 'chart3', 'chart4', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'isAdmin', 
                                'hasTarget', 'assignmentCount', 'ranking', 'performance', 'switch', 'years', 'currentYear'));
                }

            } else {

                if ($chosenInstructor) {
                    return view('performance', compact('chart1', 'chart2', 'chart3', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'isAdmin', 
                                'hasTarget', 'assignmentCount', 'ranking', 'performance', 'name', 'years', 'currentYear'));
                }

                else {
                    return view('dashboard', compact('chart1', 'chart2', 'chart3', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'isAdmin',
                                'hasTarget', 'assignmentCount', 'ranking', 'performance', 'switch', 'years', 'currentYear'));
                }
            }
        } else {
            return view('dashboard', compact('currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'isAdmin'));
        }
    }

    /**
     * Create a performance record for the given ID and type.
     *
     * This method initializes a new performance record for an instructor, department, or area,
     * setting default values and saving it to the database.
     *
     * @param int $id The ID of the instructor, department, or area.
     * @param string $type The type of performance record to create ('instructor', 'dept', 'area').
     * @param int $currentYear The current year.
     */
    private function createPerformance($id, $type, $currentYear) {
        $performance = null;

        if ($type === "instructor") {
            $performance = new InstructorPerformance();
            $performance->instructor_id = $id;
            $performance->target_hours = null;
            $performance->score = 0;
        } elseif ($type === "dept") {
            $performance = new DepartmentPerformance();
            $performance->dept_id = $id;
        } elseif ($type === "area") {
            $performance = new AreaPerformance();
            $performance->area_id = $id;
        }
        
        $performance->year = $currentYear;
        $performance->sei_avg = 0;
        $performance->enrolled_avg = 0;
        $performance->dropped_avg = 0;
        $performance->total_hours = json_encode([
            'January' => 0,
            'February' => 0,
            'March' => 0,
            'April' => 0,
            'May' => 0,
            'June' => 0,
            'July' => 0,
            'August' => 0,
            'September' => 0,
            'October' => 0,
            'November' => 0,
            'December' => 0,
        ]);
        $performance->save();
    }

    /**
     * Retrieve and order instructor performances by score for a specified department.
     *
     * This function fetches the instructor performances for all instructors
     * who teach a course, have a service role, or extra hours in an area 
     * within a specified department. The results are ordered by their performance scores.
     *
     * @param int $deptId The ID of the department for which to retrieve performances.
     * @param int $currentYear The current year.
     * @param array $area An array containing the name and id of a selected area.
     * @param boolean $forRank Defines if the user is getting the leaderboard for ranking purposes.
     * @return array An array containing the names and scores of instructors. 
     */
    private function leaderboardPrev($deptId, $currentYear, $forRank, $area) {
        $usersQuery = User::query();
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
            ->where('areas.dept_id', $deptId)->whereNotNull('instructor_performance.score');

        if ($area && $area['id'] != null) {
            $usersQuery->where('areas.id', $area['id']);
        }

        if ($forRank) {
            $usersQuery->select('users.firstname', 'users.lastname', 'instructor_performance.score', 
                                'service_roles.name as service_role_name', 'extra_hours.hours as extra_hours')
                    ->orderBy('instructor_performance.score', 'desc');
        }
        
        else {
            $usersQuery->select('users.firstname', 'users.lastname', 'instructor_performance.score')
                ->orderBy('instructor_performance.score', 'desc')->take(5);
        }

        $users = $usersQuery->get();

        $leaderboardData = [];

        foreach ($users as $user) {
            $name = $user->firstname . ' ' . $user->lastname;
            $score = $user->score;
            $leaderboardData[] = ['name' => $name, 'score' => $score];
        }

        return $leaderboardData;
    }

    /**
     * Retrieves the rank of an instructor based on their performance in a given year.
     *
     * This method joins multiple tables to gather data about the instructor, including the
     * departments they are associated with, and calculates their rank within each department.
     *
     * @param int $instructorId The ID of the instructor.
     * @param int $currentYear The year for which the ranking is being calculated.
     * @param float $score The performance score of the instructor.
     * @return array An array containing the rank, score, and standing percentage of the instructor
     *               within each department they are associated with.
     */
    private function getRank($instructorId, $currentYear, $score) {
        $deptIdsQuery = User::query();
        $deptIdsQuery->distinct()
        ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
        ->leftJoin('role_assignments', 'user_roles.id', '=', 'role_assignments.instructor_id')
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
        ->where('user_roles.id', $instructorId)
        ->where(function ($query) use ($currentYear) {
            $query->where('course_sections.year', $currentYear)
                ->orWhere('extra_hours.year', $currentYear);
        });


        $deptIdsQuery->select('areas.dept_id', 'users.firstname', 'users.lastname');

        $results = $deptIdsQuery->get();
        $deptIds = $results->pluck('dept_id')->unique();
        $name = $results->pluck('firstname')->first() . ' ' . $results->pluck('lastname')->first();
        $ranking = [];

        foreach ($deptIds as $dept) {
            $leaderboard = $this->leaderboardPrev($dept, $currentYear, true, null);
            $rank = array_search($name, array_column($leaderboard, 'name')) + 1;
            $standing = ($rank / count($leaderboard)) * 100;
            $rank = $this->addOrdinalSuffix($rank);
            $ranking[] = ['rank' => $rank, 'score' => $score, 'standing' => $standing];
        }

        return $ranking;
    }

    /**
     * Count the department assignments for the given areas.
     *
     * This method calculates the total number of service roles, extra hours,
     * and course sections for each area in the department for the current year.
     *
     * @param array $areas An array of areas within the department.
     * @param int $currentYear The current year.
     * @param array $area An array containing the name and id of a selected area.
     * @return array An array containing counts of service roles, extra hours, and course sections for the department and each area.
     */
    private function countDeptAssignments($areas, $currentYear, $area) {
        $deptAssignmentCount = [];

        if ($area && $area['id'] != null) {
            $serviceRoles = [];
            $rolesTotal = 0;
            $areaRoles = ServiceRole::where('area_id', $area['id'])->where('year', $currentYear)->where('archived', false)->get();

            foreach ($areaRoles as $role) {
                if ($role) {
                    $serviceRoles[] = ['name' => $role->name, 'hours' => $role->monthly_hours[date('F')]];
                    $rolesTotal++;
                }
            }

            $deptAssignmentCount[] = $rolesTotal;
            $deptAssignmentCount[] = $serviceRoles;

            $extraHours = [];
            $extraHoursTotal = 0;
            $allExtraHours = ExtraHour::where('area_id', $area['id'])->where('year', $currentYear)->where('month', date('n'))->where('archived', false)->get();

            foreach ($allExtraHours as $extraHrs) {
                if ($extraHrs) {
                    $extraHours[] = ['name' => $extraHrs->name, 'hours' => $extraHrs->hours];
                    $extraHoursTotal++;
                }
            }

            $deptAssignmentCount[] = $extraHoursTotal;
            $deptAssignmentCount[] = $extraHours;

            $courseSectionTotal = 0;
            $courseSections = [];
            $courses = CourseSection::where('area_id', $area['id'])->where('year', $currentYear)->where('archived', false)->get();

            foreach ($courses as $course) {
                $courseSectionTotal++;
            }

            $courses = CourseSection::where('area_id', $area['id'])->where('year', $currentYear)->where('archived', false)->get()->take(5);
            
            foreach ($courses as $course) {
                if ($course) {
                    $courseSections[] = $course->prefix . " " . $course->number;
                }
            }

            $deptAssignmentCount[] = $courseSectionTotal;
            $deptAssignmentCount[] = $courseSections;

            return $deptAssignmentCount;
        }

        $deptRolesTotal = 0;
        $areaRolesTotal = [];

        foreach ($areas as $dept_area) {
            $roles = ServiceRole::where('area_id', $dept_area->id)->where('year', $currentYear)->where('archived', false)->get();

            if ($roles) {
                $areaRolesTotal[] = [$dept_area->name, $roles->count()];
                $deptRolesTotal += $roles->count();
            }
        }

        $deptAssignmentCount[] = $deptRolesTotal;
        $deptAssignmentCount[] = $areaRolesTotal;

        $deptExtrasTotal = 0;
        $areaExtrasTotal = [];

        foreach ($areas as $dept_area) {
            $extras = ExtraHour::where('area_id', $dept_area->id)->where('year', $currentYear)->where('archived', false)->get();

            if ($extras) {
                $areaExtrasTotal[] = [$dept_area->name, $extras->count()];
                $deptExtrasTotal += $extras->count();
            }
        }

        $deptAssignmentCount[] = $deptExtrasTotal;
        $deptAssignmentCount[] = $areaExtrasTotal;

        $deptCoursesTotal = 0;
        $areaCoursesTotal = [];

        foreach ($areas as $dept_area) {
            $courses = CourseSection::where('area_id', $dept_area->id)->where('year', $currentYear)->where('archived', false)->get();

            if ($courses) {
                $areaCoursesTotal[] = [$dept_area->name, $courses->count()];
                $deptCoursesTotal += $courses->count();
            }
        }

        $deptAssignmentCount[] = $deptCoursesTotal;
        $deptAssignmentCount[] = $areaCoursesTotal;

        return $deptAssignmentCount;
    }

    /**
     * Count the assignments for the given instructor.
     *
     * This method calculates the total number of service roles, extra hours,
     * and course sections for the specified instructor for the current year.
     *
     * @param int $instructorRoleId The ID of the instructor.
     * @param bool $hasTarget Whether the instructor has a target assigned.
     * @param int $currentYear The current year.
     * @param int $currentMonth The current month.
     * @return array An array containing counts of service roles, extra hours, and course sections.
     */
    private function countAssignments($instructorRoleId, $hasTarget, $currentYear, $currentMonth) {
        $assignmentCount = [];

        $serviceRoles = [];
        $roleHoursTotal = 0;
        $assignedRoles = RoleAssignment::where('instructor_id', $instructorRoleId)->get();

        foreach ($assignedRoles as $assignedRole) {
            $role = ServiceRole::where('id', $assignedRole->service_role_id)->where('year', $currentYear)->where('archived', false)->first();

            if ($role) {
                $serviceRoles[] = ['name' => $role->name, 'hours' => $role->monthly_hours[$currentMonth]];
                $roleHoursTotal += $role->monthly_hours[$currentMonth];
            }
        }

        $assignmentCount[] = $serviceRoles;

        $extraHours = [];
        $extraHoursTotal = 0;

        $allExtraHours = ExtraHour::where('instructor_id', $instructorRoleId)->where('year', $currentYear)->where('month', date('n'))->where('archived', false)->get();

        foreach ($allExtraHours as $extraHrs) {
            if ($extraHrs) {
                $extraHours[] = ['name' => $extraHrs->name, 'hours' => $extraHrs->hours];
                $extraHoursTotal += $extraHrs->hours;
            }
        }

        $assignmentCount[] = $extraHours;

        $courseSections = [];
        $teaches = Teach::where('instructor_id', $instructorRoleId)->get()->take(5);

        foreach ($teaches as $teaching) {
            $course = CourseSection::where('id', $teaching->course_section_id)->where('year', $currentYear)->where('archived', false)->first();
            if ($course) {
                $courseSections[] = $course->prefix . " " . $course->number;
            }
        }

        $assignmentCount[] = $courseSections;

        if (!$hasTarget) {
            $assignmentCount[] = $roleHoursTotal;
            $assignmentCount[] = $extraHoursTotal;
        }

        return $assignmentCount;
    }

    /**
     * Retrieve the performance years for a department or an instructor.
     *
     * @param int $id The ID of the department or instructor.
     * @param bool $isDept Flag to indicate if the ID belongs to a department (true) or an instructor (false).
     * @return array An array of performance years.
     */
    private function getPerformanceYears($id, $isDept) {
        if ($isDept) {
            return DepartmentPerformance::where('dept_id', $id)
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->toArray();
        } else {
            return InstructorPerformance::where('instructor_id', $id)
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->toArray();
        }
    }

    /**
     * Retrieve the areas for a department.
     *
     * @param int $id The ID of the department.
     * @return array An array of area names and ids.
     */
    private function getAreas($id) {
        $dept_areas = Area::where('dept_id', $id)->get();
        $areas = [];

        // Add null id for department level filter
        $departmentName = Department::where('id', $id)->pluck('name')->first();
        $areas[] = ['name' => $departmentName, 'id' => null];

        foreach ($dept_areas as $area) {
            $areas[] = ['name' => $area->name, 'id' => $area->id];
        }

        return $areas;
    }

    /**
     * Create a line chart for department performance.
     *
     * This method prepares data for a line chart showing department performance
     * based on total hours for each month of the current year.
     *
     * @param array $totalHours An array of total hours for each entity (department and areas).
     * @param array $dataLabels An array of labels for each entity (department and areas).
     * @return string The JSON configuration for the Chart.js line chart.
     */
    private function deptLineChart($dataLabels, $totalHours) {
        $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $colors = [
            "rgba(37, 41, 150, 0.31)", 
            "rgba(29, 154, 202, 0.31)", 
            "rgba(249, 168, 37, 0.31)", 
            "rgba(241, 103, 69, 0.31)", 
            "rgba(124, 63, 88, 0.31)" ,
            "rgba(255, 127, 14, 0.31)",  
            "rgba(44, 160, 44, 0.31)",   
            "rgba(214, 39, 40, 0.31)",   
            "rgba(148, 103, 189, 0.31)", 
            "rgba(140, 86, 75, 0.31)",
            "rgba(127, 127, 127, 0.31)"  
        ];
        
        $borderColors = [
            "rgba(37, 41, 150, 0.7)", 
            "rgba(29, 154, 202, 0.7)", 
            "rgba(249, 168, 37, 0.7)", 
            "rgba(241, 103, 69, 0.7)", 
            "rgba(124, 63, 88, 0.7)",
            "rgba(255, 127, 14, 0.7)",
            "rgba(44, 160, 44, 0.7)",
            "rgba(214, 39, 40, 0.7)",  
            "rgba(148, 103, 189, 0.7)",
            "rgba(140, 86, 75, 0.7)",  
            "rgba(127, 127, 127, 0.7)" 
        ];

        $datasets = [];
        foreach ($totalHours as $index => $hours) {
            $color = $colors[$index % count($colors)];
            $borderColor = $borderColors[$index % count($borderColors)];

            $datasets[] = [
                "label" => $dataLabels[$index],
                "backgroundColor" => $color,
                "borderColor" => $borderColor,
                "data" => $hours
            ];
        }

        return app()
            ->chartjs->name("DepartmentLine")
            ->type("line")
            ->size(["width" => 600, "height" => 200])
            ->labels($labels)
            ->datasets($datasets)
            ->options([
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Annual Hours',
                        'font' => [
                            'size' => 18,
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create a pie chart for department performance.
     *
     * This method prepares data for a pie chart showing the distribution of
     * performance metrics across different areas within a department.
     *
     * @param array $areaTotals An array of total values for each area.
     * @param string $title The title of the chart.
     * @param string $entity The entity (e.g., service roles) the chart represents.
     * @param string $canvas The ID of the canvas element for the chart.
     * @return string The JSON configuration for the Chart.js pie chart.
     */
    private function departmentPieChart($areaTotals, $title, $entity, $canvas) {
        $colors = [
            "rgba(29, 154, 202, 0.7)", 
            "rgba(249, 168, 37, 0.7)", 
            "rgba(241, 103, 69, 0.7)", 
            "rgba(124, 63, 88, 0.7)" ,
            "rgba(255, 127, 14, 0.7)",
            "rgba(44, 160, 44, 0.7)",
            "rgba(214, 39, 40, 0.7)",  
            "rgba(148, 103, 189, 0.7)",
            "rgba(140, 86, 75, 0.7)",  
            "rgba(127, 127, 127, 0.7)" 
        ];
        $borderColors = [
            "rgba(29, 154, 202, 0.7)", 
            "rgba(249, 168, 37, 0.7)", 
            "rgba(241, 103, 69, 0.7)", 
            "rgba(124, 63, 88, 0.7)",
            "rgba(255, 127, 14, 0.7)",
            "rgba(44, 160, 44, 0.7)",
            "rgba(214, 39, 40, 0.7)",  
            "rgba(148, 103, 189, 0.7)",
            "rgba(140, 86, 75, 0.7)",  
            "rgba(127, 127, 127, 0.7)"  
        ];

        $labels = [];
        $data = [];
        foreach ($areaTotals as $index => $areaTotal) {
            $labels[] = $areaTotal[0]; 
            $data[] = $areaTotal[1]; 
        }

        $datasets = [
            [
                "label" => $entity,
                "backgroundColor" => $colors,
                "borderColor" => $borderColors,
                "data" => $data,
                'hoverOffset' => 4,
            ]
        ];

        return app()
            ->chartjs->name($canvas)
            ->type("doughnut")
            ->size(["width" => 200, "height" => 100])
            ->labels($labels)
            ->datasets($datasets)
            ->options([
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => $title,
                        'font' => [
                            'size' => 15,
                        ]
                    ],
                    'legend' => [
                        'display' => true,
                        'position' => 'right',
                    ]
                ],
                'radius' => '100%',
                'aspectRatio' => 2
            ]);
    }

    /**
     * Create a line chart for instructor performance.
     *
     * This method prepares data for a line chart showing instructor performance
     * based on total hours for each month of the current year. If the instructor
     * has a target, the target hours are also included.
     *
     * @param \App\Models\InstructorPerformance $performance The performance record of the instructor.
     * @param bool $hasTarget Whether the instructor has a target assigned.
     * @return string The JSON configuration for the Chart.js line chart.
     */
    private function instructorLineChart($performance, $hasTarget) {
        $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $totalHours = array_values(json_decode($performance->total_hours, true));

        if ($hasTarget) {
            $targetHours = [];
            for ($i = 0; $i < 12; $i++) {
                $targetHours[] = ($performance->target_hours) / 12;
            }

            return app()
                ->chartjs->name("InstructorLineTarget")
                ->type("line")
                ->size(["width" => 600, "height" => 200])
                ->labels($labels)
                ->datasets([
                    [
                        "label" => "Total Hours",
                        "backgroundColor" => "rgba(59, 71, 121, 1)",
                        "borderColor" => "rgba(37, 41, 150, 0.7)",
                        "data" => $totalHours
                    ],
                    [
                        "label" => "Target Hours",
                        'backgroundColor' => 'rgba(0, 0, 0, 0.12)',
                        'borderColor' => 'rgba(0, 0, 0, 0.25)',
                        "data" => $targetHours
                    ]
                ])
                ->options([
                    'plugins' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Annual Hours',
                            'font' => [
                                'size' => 18,
                            ]
                        ]
                    ]
                ]);
        }

        else {
            return app()
                ->chartjs->name("InstructorLine")
                ->type("line")
                ->size(["width" => 600, "height" => 200])
                ->labels($labels)
                ->datasets([
                    [
                        "label" => "Total Hours",
                        "backgroundColor" => "rgba(59, 71, 121, 1)",
                        "borderColor" => "rgba(37, 41, 150, 0.7)",
                        "data" => $totalHours
                    ]
                ])
                ->options([
                    'plugins' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Annual Hours',
                            'font' => [
                                'size' => 18,
                            ]
                        ]
                    ]
                ]);
        }
    }

    /**
     * Create a progress bar for instructor performance.
     *
     * This method prepares data for a progress bar showing the instructor's
     * progress towards their target for the current month.
     *
     * @param \App\Models\InstructorPerformance $performance The performance record of the instructor.
     * @param string $currentMonth The current month.
     * @return string The JSON configuration for the Chart.js progress bar.
     */
    private function instructorProgressBar($performance, $currentMonth) {
        $currentMonthHours = json_decode($performance->total_hours, true)[$currentMonth];
        $label = [$currentMonth . ' Hours'];
        $monthsHours = [$currentMonthHours];
        $hoursNeed = [0];
        if ((($performance->target_hours) / 12) > $currentMonthHours) {
            $hoursNeed = [(($performance->target_hours) / 12) - $currentMonthHours];
        }

        return app()
            ->chartjs->name("ProgressBar")
            ->type("bar")
            ->size(["width" => 400, "height" => 75])
            ->labels($label)
            ->datasets([
                [
                    "label" => "Current Hours",
                    "backgroundColor" => "rgba(59, 71, 121, 1)",
                    "borderColor" => "rgba(59, 71, 121, 0.31)",
                    "borderWidth" => 0,
                    "borderSkipped" => false,
                    "borderRadius" => 5,
                    "barPercentage" => 3,
                    "categoryPercentage" => 0.8,
                    "data" => $monthsHours
                ],
                [
                    "label" => "Hours Needed to Reach Target",
                    'backgroundColor' => 'rgba(0, 0, 0, 0.12)',
                    'borderColor' => 'rgba(0, 0, 0, 0.25)',
                    "borderWidth" => 0.1,
                    "borderSkipped" => false,
                    "borderRadius" => 3,
                    "barPercentage" => 2,
                    "categoryPercentage" => 0.8,
                    "data" => $hoursNeed
                ]
            ])
            ->options([
                'indexAxis' => 'y',
                'plugins' => [
                    'legend' => [
                        'display' => false
                    ],
                    'title' => [
                        'display' => true,
                        'text' => strval($label[0]) . " Target",
                        'font' => [
                            'size' => 18,
                        ]
                    ]
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'grid' => [
                            'display' => false,
                            'drawBorder' => false
                        ],
                        'ticks' => [
                            'display' => false
                        ],
                        'stacked' => true
                    ],
                    'x' => [
                        'beginAtZero' => true,
                        'grid' => [
                            'offset' => true,
                            'display' => true,
                            'drawBorder' => false
                        ],
                        'ticks' => [
                            'display' => true,
                            'autoSkip' => true,
                            'maxTicksLimit' => 2
                        ],
                        'title' => [
                            'display' => true,
                            'text' => strval(round(($monthsHours[0] / ($monthsHours[0] + $hoursNeed[0])) * 100)) . "% Completed"
                        ],
                        'stacked' => true,
                        'max' => $monthsHours[0] + $hoursNeed[0]
                    ]
                ],
                'layout' => [
                    'padding' => [
                        'top' => 10,
                        'bottom' => 10
                    ]
                ]
            ]);
    }

    /**
     * Create a pie chart for performance.
     *
     * This method prepares data for a pie chart showing the distribution of
     * performance metrics for a specified professor or area.
     *
     * @param array $enitityHours An array of total hours for each entity.
     * @param string $title The title of the chart.
     * @param string $entity The entity (e.g., service roles) the chart represents.
     * @param string $canvas The ID of the canvas element for the chart.
     * @return string The JSON configuration for the Chart.js pie chart.
     */
    private function performancePieChart($entityHours, $title, $entity, $canvas) {
        $colors = [
            "rgba(29, 154, 202, 0.7)", 
            "rgba(249, 168, 37, 0.7)", 
            "rgba(241, 103, 69, 0.7)", 
            "rgba(124, 63, 88, 0.7)" ,
            "rgba(44, 160, 44, 0.7)"
        ];
        $borderColors = [
            "rgba(29, 154, 202, 0.7)", 
            "rgba(249, 168, 37, 0.7)", 
            "rgba(241, 103, 69, 0.7)", 
            "rgba(124, 63, 88, 0.7)",
            "rgba(44, 160, 44, 0.7)", 
        ];

        $labels = [];
        $data = [];
        foreach ($entityHours as $index => $hours) {
            $labels[] = $hours['name']; 
            $data[] = $hours['hours']; 
        }

        $datasets = [
            [
                "label" => $entity,
                "backgroundColor" => $colors,
                "borderColor" => $borderColors,
                "data" => $data,
                'hoverOffset' => 4,
            ]
        ];

        return app()
            ->chartjs->name($canvas)
            ->type("doughnut")
            ->size(["width" => 200, "height" => 100])
            ->labels($labels)
            ->datasets($datasets)
            ->options([
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => $title,
                        'font' => [
                            'size' => 15,
                        ]
                    ],
                    'legend' => [
                        'display' => true,
                        'position' => 'right',
                    ]
                ],
                'radius' => '90%',
                'aspectRatio' => 2
            ]);
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