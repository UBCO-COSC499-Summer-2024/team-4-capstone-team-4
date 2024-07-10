<?php

namespace Database\Seeders;

use App\Models\AreaPerformance;
use App\Models\Department;
use App\Models\Area;
use App\Models\UserRole;
use App\Models\ServiceRole;
use App\Models\SeiData;
use App\Models\ServiceRole;
use App\Models\SeiData;
use App\Models\InstructorPerformance;
use App\Models\CourseSection;
use App\Models\User;
use App\Models\Teach;
use App\Models\RoleAssignment;
use App\Models\ExtraHour;
use App\Models\DepartmentPerformance;
use Illuminate\Database\Seeder;
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
        // Create CMPS department
        $dept = Department::factory()->create([
            'name' => 'CMPS',
        ]);

        // Create the 4 areas in CMPS department
        // Create the 4 areas in CMPS department
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

        // Create department head
        // Create department head
        $head = User::factory()->create([
            'firstname' => 'Dept',
            'lastname' => 'Head',
            'email' => 'head@example.com',
            'password' => 'password'
        ]);
        $headrole = UserRole::factory()->create([
        $headrole = UserRole::factory()->create([
            'user_id' => $head->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);

        // Create department staff
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

        // Create an example instructor
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
            'score' => 0,
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
        // Add course for the example instructor
        $instructor_courses = CourseSection::factory(2)->create([
            'year' => date('Y'),
        ]);
        foreach($instructor_courses as $c){
            Teach::factory()->create([
                'course_section_id' => $c->id,
                'instructor_id' => $instructorRole->id,
            ]);
            SeiData::factory()->create([
                'course_section_id' => $c->id,
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
        // Add service roles for the example instructor
        $instructor_svcroles = ServiceRole::factory(3)->create([
            'year' => date('Y'),
        ]);
        foreach ($instructor_svcroles as $role){
            RoleAssignment::factory()->create([
                'service_role_id' => $role->id,
                'assigner_id' =>  $headrole ->id,
                'instructor_id' => $instructorRole->id,
            ]);
            $this->updatePerformance($instructorRole->id, $role);
        }
        // Add extra hours for the example instructor
        $extrahour = ExtraHour::factory()->create([
            'year' => 2024,
            'month' => 7,
            'assigner_id' => $headrole->id,
            'instructor_id' => $instructorRole->id
        ]);
        $this->updatePerformance2($extrahour);

        // Create courses and assign instructors to them    
        $courses = CourseSection::factory(10)->create([
            'year' => date('Y'),
        ]);
        foreach($courses as $course){
            $user = User::factory()->create(
                ['password' => 'password']
            );

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

            Teach::factory()->create([
                'course_section_id' => $course->id,
                'instructor_id' => $role->id,
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
            "year" => date('Y'),
            "area_id" => Area::pluck('id')->random(),
        ]);
        foreach ($svcroles as $role){
            $instructor_id  = UserRole::where('role', 'instructor')->pluck('id')->random();
            RoleAssignment::factory()->create([
                'service_role_id' => $role->id,
                'assigner_id' =>  $headrole ->id,
                'instructor_id' => $instructor_id,
            ]);
            $this->updatePerformance($instructor_id, $role);
            //InstructorPerformance::updatePerformance($instructor_id, 2024);
            //AreaPerformance::updateAreaPerformance(2024);
            //DepartmentPerformance::updateDepartmentPerformance(2024);
        } 

        $extrahours = ExtraHour::factory(5)->create([
            'year' => 2024,
            'month' => 7,
            'assigner_id' => $headrole->id,
        ]);
        
        foreach ($extrahours as $hours){
            $this->updatePerformance2($hours);
            //InstructorPerformance::updatePerformance($instructor_id, 2024);
            //AreaPerformance::updateAreaPerformance(2024);
            //DepartmentPerformance::updateDepartmentPerformance(2024);  
        }  
    }

    private function updatePerformance($instructor_id, $role){
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
    }

    private function updatePerformance2($hours){
        $instructor_id  = $hours->instructor_id;
            $instructorPerformance = InstructorPerformance::where('instructor_id', $instructor_id)->where('year', 2024)->first();
            $existingHours = json_decode($instructorPerformance->total_hours, true);
            $existingHours['July'] += $hours->hours;
            $instructorPerformance->update([
                'total_hours' => json_encode($existingHours)
            ]);
    }
}