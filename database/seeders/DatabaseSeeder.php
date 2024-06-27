<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CourseSection;
use App\Models\UserRole;
use App\Models\Teach;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users with roles and course sections
        User::factory(10)->create()->each(function ($user) {
            $role = UserRole::create([
                'user_id' => $user->id,
                'role' => 'instructor',
                'department_id' => 1 // Assuming department_id 1 exists
            ]);

            $courseSections = CourseSection::factory(2)->create();
            foreach ($courseSections as $section) {
                Teach::create([
                    'instructor_id' => $role->id,
                    'course_section_id' => $section->id
                ]);
            }
        });
    }
}
