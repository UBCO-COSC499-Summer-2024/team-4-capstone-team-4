<?php
namespace Database\Seeders;

use App\Models\Department;
use App\Models\CourseSection;
use App\Models\User;
use App\Models\Area;
use App\Models\UserRole;
use App\Models\ServiceRole;
use App\Models\AreaPerformance;
use App\Models\InstructorPerformance;
use App\Models\Teach;
use Illuminate\Database\Seeder;

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
                'score' => '500',
                'total_hours' => '20',
                'target_hours' => '200',
                'sei_avg' => '3.5',
                'month' => date('F'),
                'year' => '2024',
                'instructor_id' => $user->id,
            ]);
        }

        CourseSection::factory(10)->create();

        Teach::factory(5)->create();

        $instructor = User::factory()->create([
            'firstname' => 'Dr',
            'lastname' => 'Prof',
            'email' => 'instructor@example.com',
            'password' => 'password'
        ]);
        UserRole::factory()->create([
            'user_id' => $instructor->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
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
    }
}
