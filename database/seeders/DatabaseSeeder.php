<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\CourseSection;
use App\Models\User;
use App\Models\Area;
use App\Models\UserRole;
use App\Models\InstructorPerformance;
use App\Models\Teach;
use Illuminate\Database\Seeder;
use App\Models\SeiData;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a single department
        $dept = Department::factory()->create([
            'name' => 'CMPS',
        ]);

        // Manually create areas
        $areas = ['Computer Science', 'Mathematics', 'Physics', 'Statistics'];
        $createdAreas = [];
        foreach ($areas as $area) {
            $createdAreas[] = Area::create([
                'name' => $area,
                'dept_id' => $dept->id,
            ]);
        }

        // Create users and their roles
        $users = User::factory(10)->create();
        foreach ($users as $user) {
            $role = UserRole::factory()->create([
                'user_id' => $user->id,
                'department_id' => $dept->id,
                'role' => 'instructor',
            ]);

            InstructorPerformance::factory()->create([
                'year' => date('Y'),
                'instructor_id' => $role->id,
            ]);

            // Create course sections and teach records
            $courseSections = CourseSection::factory(2)->create()->each(function ($section) use ($createdAreas) {
                // Assign random area to each course section
                $section->area_id = $createdAreas[array_rand($createdAreas)]->id;
                $section->save();
            });

            foreach ($courseSections as $section) {
                Teach::factory()->create([
                    'instructor_id' => $role->id,
                    'course_section_id' => $section->id,
                ]);

                SeiData::factory()->create([
                    'course_section_id' => $section->id,
                    'questions' => json_encode([
                        'q1' => rand(1, 5),
                        'q2' => rand(1, 5),
                        'q3' => rand(1, 5),
                        'q4' => rand(1, 5),
                        'q5' => rand(1, 5),
                        'q6' => rand(1, 5),
                    ]),
                ]);
            }
        }

        // Create specific users with defined roles
        $specificUsers = [
            ['Dr', 'Prof', 'instructor@example.com', 'instructor', 'instructor'],
            ['Dept', 'Head', 'head@example.com', 'dept_head', null],
            ['Dept', 'Staff', 'staff@example.com', 'dept_staff', null],
            ['Dept', 'Admin', 'admin@example.com', 'admin', null]
        ];

        foreach ($specificUsers as $userInfo) {
            $user = User::factory()->create([
                'firstname' => $userInfo[0],
                'lastname' => $userInfo[1],
                'email' => $userInfo[2],
                'password' => bcrypt('password'),
            ]);

            $role = UserRole::factory()->create([
                'user_id' => $user->id,
                'department_id' => $dept->id,
                'role' => $userInfo[3],
            ]);

            if ($userInfo[4] === 'instructor') {
                InstructorPerformance::factory()->create([
                    'year' => date('Y'),
                    'instructor_id' => $role->id,
                ]);

                // Create course sections and teach records
                $courseSections = CourseSection::factory(2)->create()->each(function ($section) use ($createdAreas) {
                    // Assign random area to each course section
                    $section->area_id = $createdAreas[array_rand($createdAreas)]->id;
                    $section->save();
                });

                foreach ($courseSections as $section) {
                    Teach::factory()->create([
                        'instructor_id' => $role->id,
                        'course_section_id' => $section->id,
                    ]);

                    SeiData::factory()->create([
                        'course_section_id' => $section->id,
                        'questions' => json_encode([
                            'q1' => rand(1, 5),
                            'q2' => rand(1, 5),
                            'q3' => rand(1, 5),
                            'q4' => rand(1, 5),
                            'q5' => rand(1, 5),
                            'q6' => rand(1, 5),
                        ]),
                    ]);
                }
            }
        }
    }
}
