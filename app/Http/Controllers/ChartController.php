<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRole;
use App\Models\Department;
use App\Models\Area;
use App\Models\AreaPerformance;
use App\Models\DepartmentPerformance;
use App\Models\RoleAssignment;
use App\Models\ServiceRole;
use App\Models\ExtraHours;
use App\Models\CourseSection;
use App\Models\Teach;
use App\Models\InstructorPerformance;

class ChartController extends Controller {
    public function showChart() {
        // Extract the current month to determine the dashboard display
        $currentMonth = date('F');
        $currentYear = date('Y');

        // Use the user id to determine which dashboard to show
        $userId = Auth::id();
        $userRoles = UserRole::where('user_id', $userId)->get();

        // Initialize variables
        $dept = null;
        $isDeptHead = false;
        $isDeptStaff = false;
        $isInstructor = false;

        // Determine if the user has a 'dept_head' role and retrieve the role ID
        $deptHeadRole = $userRoles->firstWhere('role', 'dept_head');
        if ($deptHeadRole) {
            $deptHeadRoleId = $deptHeadRole->id;
            $dept = $deptHeadRole->department_id;
            $isDeptHead = true;
        }

        // Determine if the user has a 'dept_staff' role and retrieve the role ID
        $deptStaffRole = $userRoles->firstWhere('role', 'dept_staff');
        if ($deptStaffRole) {
            $deptStaffRoleId = $deptStaffRole->id;
            $dept = $deptStaffRole->department_id;
            $isDeptStaff = true;
        }

        // Determine if the user has an 'instructor' role and retrieve the role ID
        $instructorRole = $userRoles->firstWhere('role', 'instructor');
        if ($instructorRole) {
            $instructorRoleId = $instructorRole->id;
            $isInstructor = true;
        }

        if ($isDeptHead || $isDeptStaff) {
            // Fetch the department performance for the current year
            $deptPerformance = DepartmentPerformance::where('dept_id', $dept)
                ->where('year', $currentYear)
                ->first();

            // Create a new performance if one does not exist yet
            if ($deptPerformance === null) {
                $deptPerformance = new DepartmentPerformance();
                $deptPerformance->dept_id = $dept;
                $deptPerformance->year = $currentYear;
                $deptPerformance->sei_avg = 0;
                $deptPerformance->enrolled_avg = 0;
                $deptPerformance->dropped_avg = 0;
                $deptPerformance->capacity_avg = 0;
                $deptPerformance->total_hours = json_encode([
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
                $deptPerformance->save();
            }

            // Fetch department performance averages for the current year
            $deptSeiAvg = $deptPerformance->sei_avg;
            $deptEnrolledAvg = $deptPerformance->enrolled_avg;
            $deptDroppedAvg = $deptPerformance->dropped_avg;
            $deptCapacityAvg = $deptPerformance->capacity_avg;
            $deptMonthHours = json_decode($deptPerformance->total_hours, true)[$currentMonth];
        
            // Fetch the performance for each area within the department for the current year
            $dataLabels = [];
            $areaPerformances = [];
        
            // Get department name
            $deptName = Department::where('id', $dept)->value('name');
            $dataLabels[] = $deptName;

            // Prepare total hours data
            $totalHours = [];
            $departmentHours = json_decode($deptPerformance->total_hours, true);
            $totalHours[] = array_values($departmentHours); // Convert to simple array of hours
        
            // Fetch areas and their performances
            $areas = Area::where('dept_id', $dept)->get();
            foreach ($areas as $area) {
                $dataLabels[] = $area->name;
                $performance = AreaPerformance::where('area_id', $area->id)
                    ->where('year', $currentYear)
                    ->first();
                
                // Create a new performance if one does not exist yet
                if ($performance === null) {
                    $performance = new AreaPerformance();
                    $performance->area_id = $area->id;
                    $performance->year = $currentYear;
                    $performance->sei_avg = 0;
                    $performance->enrolled_avg = 0;
                    $performance->dropped_avg = 0;
                    $performance->capacity_avg = 0;
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

                $areaPerformances[] = $performance;
                $totalHours[] = array_values(json_decode($performance->total_hours, true)); // Convert to simple array of hours
            }

            // Fetch total department and area service roles for the current year
            $deptRolesTotal = 0;
            $areaRolesTotal = [];

            foreach ($areas as $area) {
                $roles = ServiceRole::where('area_id', $area->id)->where('year', $currentYear)->get();
                $areaRolesTotal[] = [$area->name, $roles->count()];
                $deptRolesTotal = $deptRolesTotal + $roles->count();
            }

            // Fetch total department and area extra hours for the current year
            $deptExtrasTotal = 0;
            $areaExtrasTotal = [];

            foreach ($areas as $area) {
                $extras = ExtraHours::where('area_id', $area->id)->where('year', $currentYear)->get();
                $areaExtrasTotal[] = [$area->name, $extras->count()];
                $deptExtrasTotal = $deptExtrasTotal + $extras->count();
            }

            // Fetch total department and area course sections for the current year
            $deptCoursesTotal = 0;
            $areaCoursesTotal = [];

            foreach ($areas as $area) {
                $courses = CourseSection::where('area_id', $area->id)->where('year', $currentYear)->get();
                $areaCoursesTotal[] = [$area->name, $courses->count()];
                $deptCoursesTotal = $deptCoursesTotal + $courses->count();
            }
        
            // Labels for months
            $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        
            // Define colors for datasets
            $colors = [
                "rgba(37, 41, 150, 0.31)",
                "rgba(37, 150, 41, 0.31)",
                "rgba(150, 41, 37, 0.31)",
                "rgba(150, 150, 41, 0.31)",
                "rgba(41, 150, 150, 0.31)"
            ];
            $borderColors = [
                "rgba(37, 41, 150, 0.7)",
                "rgba(37, 150, 41, 0.7)",
                "rgba(150, 41, 37, 0.7)",
                "rgba(150, 150, 41, 0.7)",
                "rgba(41, 150, 150, 0.7)"
            ];
        
            // Create datasets for the chart
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
        
            // Create the chart configuration
            $chart1 = app()
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
            
            if ($isInstructor) {

                // Fetch the instructor performance for the current year
                $performance = InstructorPerformance::where('instructor_id', $instructorRoleId)
                    ->where('year', $currentYear)
                    ->first();
                
                if ($performance === null) {
                    $performance = new InstructorPerformance();
                    $performance->instructor_id = $instructorRoleId;
                    $performance->year = $currentYear;
                    $performance->sei_avg = 0;
                    $performance->enrolled_avg = 0;
                    $performance->dropped_avg = 0;
                    $performance->capacity_avg = 0;
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
                    $performance->target_hours = null; // Set target to null
                    $performance->save();
                }

                // Fetch the instructor service roles for the current year
                $serviceRoles = [];
                $roleHoursTotal = 0;
                $assignedRoles = RoleAssignment::where('instructor_id', $instructorRoleId)
                    ->get();

                foreach ($assignedRoles as $assignedRole) {
                    $role = ServiceRole::where('id', $assignedRole->service_role_id);
                    if ($role->year === $currentYear) {
                        $serviceRoles[] = $role->name;
                        $roleHoursTotal = $roleHoursTotal + json_decode($role->monthly_hours, true)[$currentMonth];
                    }
                }

                // Fetch the instructor extra hours for the current year
                $extraHours = [];
                $extraHoursTotal = 0;

                $allExtraHours = ExtraHours::where('instructor_id', $instructorRoleId)
                    ->get();

                foreach ($allExtraHours as $extraHrs) {
                    if ($extraHrs->year === $currentYear) {
                        $extraHours[] = $extraHrs->name;
                        $extraHoursTotal = $extraHoursTotal + $extraHrs->hours;
                    }
                }

                // Fetch the instructor course sections for the current year
                $courseSections = [];
                $teaches = Teach::where('instructor_id', $instructorRoleId)
                    ->get();

                foreach ($teaches as $teaching) {
                    $course = CourseSection::where('id', $teaching->course_section_id);
                    if ($course->year === $currentYear) {
                        $courseSections[] = $course->name;
                    }
                }

                // Fetch instructor performance averages for the current year
                $seiAvg = $performance->sei_avg;
                $enrolledAvg = $performance->enrolled_avg;
                $droppedAvg = $performance->dropped_avg;
                $capacityAvg = $performance->capacity_avg;

                // Get the total instructor hours for the year
                $totalHours = array_values(json_decode($performance->total_hours, true));
                $currentMonthHours = json_decode($performance->total_hours, true)[$currentMonth];

                // Check if the target is set
                $hasTarget = false;
                if ($performance->target_hours !== null) {
                    $hasTarget = true;
                }

                if ($hasTarget) {
                    // Line Chart with Target Data
                    $targetHours = [];
                    for ($i = 0; $i < 12; $i++) {
                        $targetHours[] = ($performance->target_hours) / 12;
                    }

                    $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

                    $chart2 = app()
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

                    // Progress Bar Data
                    $label = [$currentMonth . ' Hours'];
                    $monthsHours = [$currentMonthHours];
                    $hoursNeed = [(($performance->target_hours) / 12) - $currentMonthHours];
                    if ($hoursNeed < 0) {
                        $hoursNeed = 0;
                    }

                    $chart3 = app()
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
                    
                    return view('dashboard', compact('chart1', 'chart2', 'chart3', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'hasTarget', 
                                'courseSections', 'extraHours', 'serviceRoles', 'seiAvg', 'enrolledAvg', 'droppedAvg', 'capacityAvg', 'deptCoursesTotal', 'areaCoursesTotal', 
                                'deptExtrasTotal', 'areaExtrasTotal', 'deptRolesTotal', 'areaRolesTotal', 'deptSeiAvg', 'deptEnrolledAvg', 'deptDroppedAvg', 'deptCapacityAvg', 'deptMonthHours'));
                }

                else {
                    // Line Chart without Target Data
                    $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

                    $chart2 = app()
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
                    
                    return view('dashboard', compact('chart1', 'chart2', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'hasTarget',
                                'courseSections', 'extraHours', 'serviceRoles', 'seiAvg', 'enrolledAvg', 'droppedAvg', 'capacityAvg', 'currentMonthHours', 'roleHoursTotal', 
                                'extraHoursTotal', 'deptCoursesTotal', 'areaCoursesTotal', 'deptExtrasTotal', 'areaExtrasTotal', 'deptRolesTotal', 'areaRolesTotal', 'deptSeiAvg', 
                                'deptEnrolledAvg', 'deptDroppedAvg', 'deptCapacityAvg', 'deptMonthHours'));
                }

            }

            else {
                return view('dashboard', compact('chart1', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'deptCoursesTotal', 'areaCoursesTotal', 
                            'deptExtrasTotal', 'areaExtrasTotal', 'deptRolesTotal', 'areaRolesTotal', 'deptSeiAvg', 'deptEnrolledAvg', 'deptDroppedAvg', 'deptCapacityAvg', 'deptMonthHours'));
            }
        }        

        elseif ($isInstructor) {
            // Fetch the instructor performance for the current year
            $performance = InstructorPerformance::where('instructor_id', $instructorRoleId)
                ->where('year', $currentYear)
                ->first();

            if ($performance === null) {
                $performance = new InstructorPerformance();
                $performance->instructor_id = $instructorRoleId;
                $performance->year = $currentYear;
                $performance->sei_avg = 0;
                $performance->enrolled_avg = 0;
                $performance->dropped_avg = 0;
                $performance->capacity_avg = 0;
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
                $performance->target_hours = null; // Set target to null
                $performance->save();
            }

            // Fetch the instructor service roles for the current year
            $serviceRoles = [];
            $roleHoursTotal = 0;
            $assignedRoles = RoleAssignment::where('instructor_id', $instructorRoleId)
                ->get();

            foreach ($assignedRoles as $assignedRole) {
                $role = ServiceRole::where('id', $assignedRole->service_role_id)->where('year', $currentYear)->first();
                $serviceRoles[] = $role->name;
                $roleHoursTotal = $roleHoursTotal + json_decode($role->monthly_hours, true)[$currentMonth];
            }

            // Fetch the instructor extra hours for the current year
            $extraHours = [];
            $extraHoursTotal = 0;

            $allExtraHours = ExtraHours::where('instructor_id', $instructorRoleId)
                ->get();

            foreach ($allExtraHours as $extraHrs) {
                if ($extraHrs->year === $currentYear) {
                    $extraHours[] = $extraHrs->name;
                    $extraHoursTotal = $extraHoursTotal + $extraHrs->hours;
                }
            }

            // Fetch the instructor course sections for the current year
            $courseSections = [];
            $teaches = Teach::where('instructor_id', $instructorRoleId)
                ->get();

            foreach ($teaches as $teaching) {
                $course = CourseSection::where('id', $teaching->course_section_id)->where('year', $currentYear)->first();
                $courseSections[] = $course->name;
            }

            // Fetch instructor performance averages for the current year
            $seiAvg = $performance->sei_avg;
            $enrolledAvg = $performance->enrolled_avg;
            $droppedAvg = $performance->dropped_avg;
            $capacityAvg = $performance->capacity_avg;

            // Get the total instructor hours for the year
            $totalHours = array_values(json_decode($performance->total_hours, true));
            $currentMonthHours = json_decode($performance->total_hours, true)[$currentMonth];

            // Check if the target is set
            $hasTarget = false;
            if ($performance->target_hours !== null) {
                $hasTarget = true;
            }

            if ($hasTarget) {
                // Line Chart with Target Data
                $targetHours = [];
                for ($i = 0; $i < 12; $i++) {
                    $targetHours[] = ($performance->target_hours) / 12;
                }

                $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

                $chart1 = app()
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

                // Progress Bar Data
                $label = [$currentMonth . ' Hours'];
                $monthsHours = [$currentMonthHours];
                $hoursNeed = [(($performance->target_hours) / 12) - $currentMonthHours];
                if ($hoursNeed < 0) {
                    $hoursNeed = 0;
                }

                $chart2 = app()
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
                
                return view('dashboard', compact('chart1', 'chart2', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'hasTarget',
                                'courseSections', 'extraHours', 'serviceRoles', 'seiAvg', 'enrolledAvg', 'droppedAvg', 'capacityAvg'));
            }

            else {
                // Line Chart without Target Data
                $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

                $chart1 = app()
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
                
                return view('dashboard', compact('chart1', 'currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor', 'hasTarget',
                                'courseSections', 'extraHours', 'serviceRoles', 'seiAvg', 'enrolledAvg', 'droppedAvg', 'capacityAvg', 'currentMonthHours', 'roleHoursTotal', 'extraHoursTotal'));
            }
        }

        else {
            return view('dashboard', compact('currentMonth', 'userRoles', 'isDeptHead', 'isDeptStaff', 'isInstructor'));
        }
    }
}
