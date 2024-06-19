<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\CourseSection;
use App\Models\User;
use App\Models\Area;
use App\Models\UserRole;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            UserRole::create([
                'user_id' => $user->id,
                'department_id' => 1,
                'role' => 'instructor',
            ]);
        }

        //CourseSection::factory(10)->create();
    }
}
