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
    public function showChart() {
        $currentMonth = date('F');
        $currentYear = date('Y');
        $userId = Auth::id();
        $userRoles = UserRole::where('user_id', $userId)->get();

        $dept = null;
        $isDeptHead = false;
        $isDeptStaff = false;
        $isInstructor = false;

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

            $areas = Area::where('dept_id', $dept)->get();
            foreach ($areas as $area) {
                $dataLabels[] = $area->name;
                $performance = AreaPerformance::where('area_id', $area->id)
                    ->where('year', $currentYear)
                    ->first();

                if ($performance === null) {
                    $this->createPerformance($area->id, "area", $currentYear);
                }

                $areaPerformances[] = $performance;
                $totalHours[] = array_values(json_decode($performance->total_hours, true));
            }

            $deptAssignmentCount = $this->countDeptAssignments($areas, $currentYear);
            $leaderboard = $this->leaderboardPrev($dept, $currentYear);

            $chart1 = $this->deptLineChart($dataLabels, $totalHours);
            $chart2 = $this->departmentPieChart($deptAssignmentCount[1], "Total Service Roles by Area", "Service Roles", "RolePieChart");
            $chart3 = $this->departmentPieChart($deptAssignmentCount[3], "Total Extra Hours by Area", "Extra Hours", "ExtraPieCHart");
            $chart4 = $this->departmentPieChart($deptAssignmentCount[5], "Total Course Sections by Area", "Course Sections", "CoursePieChart");

            if ($isInstructor) {
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

                $chart5 = $this->instructorLineChart($performance, $hasTarget);

                if ($hasTarget) {
                    $chart6 = $this->instructorProgressBar($performance, $currentMonth);
                    
                    return view('dashboard', compact('chart1', 'chart2', 'chart3', 'chart4', 'chart5', 'chart6', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'hasTarget', 
                                'assignmentCount', 'performance', 'deptAssignmentCount', 'deptPerformance', 'leaderboard'));
                } else {
                    return view('dashboard', compact('chart1', 'chart2', 'chart3', 'chart4', 'chart5', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'hasTarget',
                                'assignmentCount', 'performance', 'deptAssignmentCount', 'deptPerformance', 'leaderboard'));
                }
            } else {
                return view('dashboard', compact('chart1', 'chart2', 'chart3', 'chart4', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'deptAssignmentCount', 'deptPerformance', 'leaderboard'));
            }
        } elseif ($isInstructor) {
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

            $chart1 = $this->instructorLineChart($performance, $hasTarget);

            if ($hasTarget) {
                $chart2 = $this->instructorProgressBar($performance, $currentMonth);

                return view('dashboard', compact('chart1', 'chart2', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'hasTarget',
                                'assignmentCount', 'performance'));
            } else {
                return view('dashboard', compact('chart1', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'hasTarget',
                            'assignmentCount', 'performance'));
            }
        } else {
            return view('dashboard', compact('currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor'));
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
     * @return array An array containing the names and scores of instructors.
     */
    private function leaderboardPrev($deptId, $currentYear) {
        $usersQuery = User::query();
        $usersQuery->distinct()
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->leftJoin('teaches', 'user_roles.id', '=', 'teaches.instructor_id')
            ->leftJoin('course_sections', 'teaches.course_section_id', '=', 'course_sections.id')
            ->leftJoin('areas', 'course_sections.area_id', '=', 'areas.id')
            ->leftJoin('instructor_performance', function ($join) use ($currentYear) {
                $join->on('user_roles.id', '=', 'instructor_performance.instructor_id')
                    ->where('instructor_performance.year', $currentYear);
            })
            ->where('areas.dept_id', $deptId);

        $usersQuery->select('users.firstname', 'users.lastname', 'instructor_performance.score')
            ->orderBy('instructor_performance.score', 'desc')->take(5);

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
     * Count the department assignments for the given areas.
     *
     * This method calculates the total number of service roles, extra hours,
     * and course sections for each area in the department for the current year.
     *
     * @param array $areas An array of areas within the department.
     * @param int $currentYear The current year.
     * @return array An array containing counts of service roles, extra hours, and course sections for the department and each area.
     */
    private function countDeptAssignments($areas, $currentYear) {
        $deptAssignmentCount = [];

        $deptRolesTotal = 0;
        $areaRolesTotal = [];

        foreach ($areas as $area) {
            $roles = ServiceRole::where('area_id', $area->id)->where('year', $currentYear)->get();
            $areaRolesTotal[] = [$area->name, $roles->count()];
            $deptRolesTotal += $roles->count();
        }

        $deptAssignmentCount[] = $deptRolesTotal;
        $deptAssignmentCount[] = $areaRolesTotal;

        $deptExtrasTotal = 0;
        $areaExtrasTotal = [];

        foreach ($areas as $area) {
            $extras = ExtraHour::where('area_id', $area->id)->where('year', $currentYear)->get();
            $areaExtrasTotal[] = [$area->name, $extras->count()];
            $deptExtrasTotal += $extras->count();
        }

        $deptAssignmentCount[] = $deptExtrasTotal;
        $deptAssignmentCount[] = $areaExtrasTotal;

        $deptCoursesTotal = 0;
        $areaCoursesTotal = [];

        foreach ($areas as $area) {
            $courses = CourseSection::where('area_id', $area->id)->where('year', $currentYear)->get();
            $areaCoursesTotal[] = [$area->name, $courses->count()];
            $deptCoursesTotal += $courses->count();
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
            $role = ServiceRole::where('id', $assignedRole->service_role_id)->where('year', $currentYear)->first();
            $serviceRoles[] = $role->name;
            $roleHoursTotal += $role->monthly_hours[$currentMonth];
        }

        $assignmentCount[] = $serviceRoles;

        $extraHours = [];
        $extraHoursTotal = 0;

        $allExtraHours = ExtraHour::where('instructor_id', $instructorRoleId)->where('year', $currentYear)->where('month', date('n'))->get();

        foreach ($allExtraHours as $extraHrs) {
            $extraHours[] = $extraHrs->name;
            $extraHoursTotal += $extraHrs->hours;
        }

        $assignmentCount[] = $extraHours;

        $courseSections = [];
        $teaches = Teach::where('instructor_id', $instructorRoleId)->get();

        foreach ($teaches as $teaching) {
            $course = CourseSection::where('id', $teaching->course_section_id)->where('year', $currentYear)->first();
            $courseSections[] = $course->prefix . " " . $course->number;
        }

        $assignmentCount[] = $courseSections;

        if (!$hasTarget) {
            $assignmentCount[] = $roleHoursTotal;
            $assignmentCount[] = $extraHoursTotal;
        }

        return $assignmentCount;
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
     * @param string $entity The entity (e.g., department) the chart represents.
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
                        "backgroundColor" => "rgba(37, 41, 150, 0.31)",
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
                        "backgroundColor" => "rgba(37, 41, 150, 0.31)",
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
                    "backgroundColor" => "rgba(37, 41, 150, 0.7)",
                    "borderColor" => "rgba(37, 41, 150, 0.31)",
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
}