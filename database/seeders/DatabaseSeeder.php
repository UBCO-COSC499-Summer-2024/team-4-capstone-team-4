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
        Department::factory()->create([
            'name' => 'CMPS',
        ]);

        Area::factory()->create([
            'name' => 'Computer Science',
            'dept_id' => 1,
        ]);

        Area::factory()->create([
            'name' => 'Mathematics',
            'dept_id' => 1,
        ]);

        Area::factory()->create([
            'name' => 'Physics',
            'dept_id' => 1,
        ]);

        Area::factory()->create([
            'name' => 'Statistics',
            'dept_id' => 1,
        ]);

        $users = User::factory(10)->create();
        foreach($users as $user) {
            UserRole::factory()->create([
                'user_id' => $user->id,
                'department_id' => 1,
                'role' => 'instructor',
            ]);
            InstructorPerformance::factory()->create([
                'score' => '500',
                'total_hours' => '20',
                'target_hours' => '200',
                'sei_avg' => '3.5',
                'year' => '2024',
                'instructor_id' => $user->id,
            ]);
        }

        CourseSection::factory(10)->create();

        Teach::factory(5)->create();
    }
}
