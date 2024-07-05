<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\CourseSection;
use App\Models\User;
use App\Models\Area;
use App\Models\UserRole;
use App\Models\ServiceRole;
use App\Models\SeiData;
use App\Models\AreaPerformance;
use App\Models\InstructorPerformance;
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
            'total_hours' => json_encode([
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
            ]),
            'sei_avg' => 0,
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

        $users = User::factory(10)->create();
        foreach($users as $user) {
            $role = UserRole::factory()->create([
                'user_id' => $user->id,
                'department_id' => $dept->id,
                'role' => 'instructor',
            ]);
            InstructorPerformance::factory()->create([
                'total_hours' => json_encode([
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
                ]),
                'sei_avg' => 0,
                'year' => date('Y'),
                'instructor_id' => $role->id,
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

        $svcroles = ServiceRole::factory(10)->create([
            "year" => 2024,
            "area_id" => Area::inRandomOrder()->first()->id
        ]);
        foreach ($svcroles as $role){
            $instructor_id  = UserRole::where('role', 'instructor')->pluck('id')->random();
            RoleAssignment::factory()->create([
                'service_role_id' => $role->id,
                'assigner_id' =>  $headrole ->id,
                'instructor_id' => $instructor_id,
            ]);
            $instructorPerformance = InstructorPerformance::where('instructor_id', $instructor_id)->where('year', 2024)->first();
            $existingMonthlyHours = json_decode($instructorPerformance->total_hours, true);
            $updatedMonthlyHours = [
                'January' => $existingMonthlyHours['January'] + $role->monthly_hours['January'],
                'February' => $existingMonthlyHours['February'] + $role->monthly_hours['February'],
                'March' => $existingMonthlyHours['March'] + $role->monthly_hours['March'],
                'April' => $existingMonthlyHours['April'] + $role->monthly_hours['April'],
                'May' => $existingMonthlyHours['May'] + $role->monthly_hours['May'],
                'June' => $existingMonthlyHours['June'] + $role->monthly_hours['June'],
                'July' => $existingMonthlyHours['July'] + $role->monthly_hours['July'],
                'August' => $existingMonthlyHours['August'] + $role->monthly_hours['August'],
                'September' => $existingMonthlyHours['September'] + $role->monthly_hours['September'],
                'October' => $existingMonthlyHours['October'] + $role->monthly_hours['October'],
                'November' => $existingMonthlyHours['November'] + $role->monthly_hours['November'],
                'December' => $existingMonthlyHours['December'] + $role->monthly_hours['December'],
            ];
        
            $instructorPerformance->update([
                'total_hours' => json_encode($updatedMonthlyHours),
            ]);
            InstructorPerformance::updatePerformance($instructor_id, 2024);
            AreaPerformance::updateAreaPerformance(2024);
            DepartmentPerformance::updateDepartmentPerformance(2024);
        }

        $extrahours = ExtraHour::factory(5)->create([
            'year' => 2024,
            'month' => 7,
            'assigner_id' =>  $headrole->id,
            'instructor_id' => UserRole::where('role', 'instructor')->pluck('id')->random(),
            'area_id' => Area::pluck('id')->random(),
        ]);
        foreach ($extrahours as $hours){
            $instructor_id  = $hours->instructor_id;
            $instructorPerformance = InstructorPerformance::where('instructor_id', $instructor_id)->where('year', 2024)->first();
            $existingHours = json_decode($instructorPerformance->total_hours, true);
            $existingHours['july'] += $hours->hours;
            $instructorPerformance->update([
                'total_hours' => json_encode($existingHours)
            ]);
            InstructorPerformance::updatePerformance($instructor_id, 2024);
            AreaPerformance::updateAreaPerformance(2024);
            DepartmentPerformance::updateDepartmentPerformance(2024);  
        }
    }
}