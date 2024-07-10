<?php

namespace Database\Seeders;

use App\Models\AreaPerformance;
use App\Models\Department;
use App\Models\Area;
use App\Models\UserRole;
use App\Models\ExtraHour;
use App\Models\InstructorPerformance;
use App\Models\DepartmentPerformance;
use App\Models\CourseSection;
use App\Models\User;
use App\Models\Teach;
use App\Models\RoleAssignment;
use App\Models\ExtraHour;
use App\Models\DepartmentPerformance;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create CMPS department
        $dept = Department::factory()->create([
            'name' => 'CMPS',
        ]);

        $areas = [
            'Computer Science',
            'Mathematics',
            'Physics',
            'Statistics'
        ];

        foreach ($areas as $area) {
            $areaInstance = Area::factory()->create([
                'name' => $area,
                'dept_id' => $dept->id,
            ]);

            // Create area performance for each area
            AreaPerformance::factory()->create([
                'total_hours' => json_encode(['January' => fake()->numberBetween(2000, 5000),
                        'February' => fake()->numberBetween(2000, 5000),
                        'March' => fake()->numberBetween(2000, 5000),
                        'April' => fake()->numberBetween(2000, 5000),
                        'May' => fake()->numberBetween(2000, 5000),
                        'June' => fake()->numberBetween(2000, 5000),
                        'July' => fake()->numberBetween(2000, 5000),
                        'August' => fake()->numberBetween(2000, 5000),
                        'September' => fake()->numberBetween(2000, 5000),
                        'October' => fake()->numberBetween(2000, 5000),
                        'November' => fake()->numberBetween(2000, 5000),
                        'December' => fake()->numberBetween(2000, 5000)]),
                'sei_avg' => fake()->randomFloat(2, 1, 5),
                'enrolled_avg' => fake()->numberBetween(0, 100),
                'dropped_avg' => fake()->numberBetween(0, 50),
                'year' => date('Y'),
                'area_id' => $areaInstance->id,
            ]);
        }

        $users = User::factory(10)->create();
        foreach($users as $user) {
            $role = UserRole::factory()->create([
                'user_id' => $user->id,
                'department_id' => $dept->id,
                'role' => 'instructor',
            ]);
            InstructorPerformance::factory()->create([
                'score' => fake()->numberBetween(50, 100),
                'total_hours' => json_encode(['January' => fake()->numberBetween(100, 500),
                'February' => fake()->numberBetween(100, 500),
                'March' => fake()->numberBetween(100, 500),
                'April' => fake()->numberBetween(100, 500),
                'May' => fake()->numberBetween(100, 500),
                'June' => fake()->numberBetween(100, 500),
                'July' => fake()->numberBetween(100, 500),
                'August' => fake()->numberBetween(100, 500),
                'September' => fake()->numberBetween(100, 500),
                'October' => fake()->numberBetween(100, 500),
                'November' => fake()->numberBetween(100, 500),
                'December' => fake()->numberBetween(100, 500),]),
                'target_hours' => fake()->numberBetween(40, 50),
                'sei_avg' => fake()->randomFloat(2, 1, 5),
                'enrolled_avg' => fake()->numberBetween(20, 100),
                'dropped_avg' => fake()->numberBetween(1, 10),
                'year' => date('Y'),
                'instructor_id' => $role->id,
            ]);
        }

        $courses = CourseSection::factory(25)->create();
        foreach($courses as $course){
            Teach::factory()->create([
                'course_section_id' => $course->id,
                'instructor_id' => UserRole::where('role', 'instructor')->pluck('id')->random(),
            ]);
            SeiData::factory()->create([
                'course_section_id'=> $course->id,
                'questions'=>json_encode([
                    'q1' => fake()->numberBetween(1,5),
                    'q2' => fake()->numberBetween(1,5),
                    'q3' => fake()->numberBetween(1,5),
                    'q4' => fake()->numberBetween(1,5),
                    'q5' => fake()->numberBetween(1,5),
                    'q6' => fake()->numberBetween(1,5),
                ]),
            ]);
        }

        $instructor = User::factory()->create([
            'firstname' => 'Dr',
            'lastname' => 'Prof',
            'email' => 'instructor@example.com',
            'password' => 'password'
        ]);
        $instructorRole = UserRole::factory()->create([
            'user_id' => $instructor->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);
        InstructorPerformance::factory()->create([
            'score' => fake()->numberBetween(50, 100),
            'total_hours' => json_encode(['January' => fake()->numberBetween(100, 500),
            'February' => fake()->numberBetween(100, 500),
            'March' => fake()->numberBetween(100, 500),
            'April' => fake()->numberBetween(100, 500),
            'May' => fake()->numberBetween(100, 500),
            'June' => fake()->numberBetween(100, 500),
            'July' => fake()->numberBetween(100, 500),
            'August' => fake()->numberBetween(100, 500),
            'September' => fake()->numberBetween(100, 500),
            'October' => fake()->numberBetween(100, 500),
            'November' => fake()->numberBetween(100, 500),
            'December' => fake()->numberBetween(100, 500),]),
            'target_hours' => null,
            'sei_avg' => fake()->randomFloat(2, 1, 5),
            'enrolled_avg' => fake()->numberBetween(20, 100),
            'dropped_avg' => fake()->numberBetween(1, 10),
            'year' => date('Y'),
            'instructor_id' =>  $instructorRole->id,
        ]);

        $head = User::factory()->create([
            'firstname' => 'Dept',
            'lastname' => 'Head',
            'email' => 'head@example.com',
            'password' => 'password'
        ]);
        $headrole = UserRole::factory()->create([
            'user_id' => $head->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);

        // Create department staff
        $staff = User::factory()->create([
            'firstname' => 'Dept',
            'lastname' => 'Staff',
            'email' => 'staff@example.com',
            'password' => 'password'
        ]);
        UserRole::factory()->create([
            'user_id' => $staff->id,
            'department_id' => $dept->id,
            'role' => 'dept_staff',
        ]);

        // Create admin user
        $admin = User::factory()->create([
            'firstname' => 'Dept',
            'lastname' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password'
        ]);
        UserRole::factory()->create([
            'user_id' => $admin->id,
            'department_id' => $dept->id,
            'role' => 'admin',
        ]);

        $this->call([
            // DepartmentSeeder::class,
            // AreaSeeder::class,
            ServiceRoleSeeder::class
        ]);

        DepartmentPerformance::factory()->create([
            'total_hours' => json_encode(['January' => fake()->numberBetween(10000, 20000),
                        'February' => fake()->numberBetween(10000, 20000),
                        'March' => fake()->numberBetween(10000, 20000),
                        'April' => fake()->numberBetween(10000, 20000),
                        'May' => fake()->numberBetween(10000, 20000),
                        'June' => fake()->numberBetween(10000, 20000),
                        'July' => fake()->numberBetween(10000, 20000),
                        'August' => fake()->numberBetween(10000, 20000),
                        'September' => fake()->numberBetween(10000, 20000),
                        'October' => fake()->numberBetween(10000, 20000),
                        'November' => fake()->numberBetween(10000, 20000),
                        'December' => fake()->numberBetween(10000, 20000)]),
            'sei_avg' => fake()->randomFloat(1, 5, 1),
            'enrolled_avg' => fake()->numberBetween(0, 100),
            'dropped_avg' => fake()->numberBetween(0, 50),
            'year' => date('Y'),
            'dept_id' => $dept->id,
        ]);

        ExtraHour::factory(25)->create();
    }
}
