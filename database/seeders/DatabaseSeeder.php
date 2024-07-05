<?php

namespace Database\Seeders;

use App\Models\ServiceRole;
use App\Models\SeiData;
use App\Models\AreaPerformance;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Area;
use App\Models\UserRole;
use App\Models\InstructorPerformance;
use App\Models\CourseSection;
use App\Models\User;
use App\Models\Teach;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $dept = Department::factory()->create([
            'name' => 'CMPS',
        ]);

        Area::factory()->create([
            'name' => 'Computer Science',
            'dept_id' => $dept->id,
        ]);

        Area::factory()->create([
            'name' => 'Mathematics',
            'dept_id' => $dept->id,
        ]);

        Area::factory()->create([
            'name' => 'Physics',
            'dept_id' => $dept->id,
        ]);

        Area::factory()->create([
            'name' => 'Statistics',
            'dept_id' => $dept->id,
        ]);

        $users = User::factory(10)->create();
        foreach($users as $user) {
            UserRole::factory()->create([
                'user_id' => $user->id,
                'department_id' => $dept->id,
                'role' => 'instructor',
            ]);
            InstructorPerformance::factory()->create([
                'year' => date('Y'),
                'instructor_id' => $user->id,
            ]);
        }

        $courses = CourseSection::factory(5)->create();
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
            'year' => date('Y'),
            'instructor_id' =>  $instructorRole->id,
        ]);
        $head = User::factory()->create([
            'firstname' => 'Dept',
            'lastname' => 'Head',
            'email' => 'head@example.com',
            'password' => 'password'
        ]);
        UserRole::factory()->create([
            'user_id' => $head->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);

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
    }
}