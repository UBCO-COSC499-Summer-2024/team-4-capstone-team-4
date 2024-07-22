<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Area;
use App\Models\Department;
use App\Models\InstructorPerformance;
use App\Models\UserRole;
use App\Models\CourseSection;
use App\Models\Teach;
use Livewire\Livewire;
use App\Livewire\StaffList;

class StaffListTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_page_can_be_rendered(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);

        $response = $this->actingAs($user)->get('/staff');

        $response->assertStatus(200);
    }

    public function test_staff_pages_cannot_be_accessed_by_instructor(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);

        $response = $this->actingAs($user)->get('/staff');

        $response->assertStatus(403);
    }

    public function test_staff_page_shows_a_list_of_instructors(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);

        $user1 =  User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        $role1 = UserRole::factory()->create([
                'user_id' => $user1->id,
                'department_id' => $dept->id,
                'role' => 'instructor',
            ]);
        InstructorPerformance::factory()->create([
            'year' => date('Y'),
            'instructor_id' => $role1->id,
        ]);

        $user2 =  User::factory()->create([
            'firstname' => 'Test2',
            'lastname' => 'User2',
            'email' => 'test2@example.com',
            'password' => 'password'
        ]);
        $role2 = UserRole::factory()->create([
                'user_id' => $user2->id,
                'department_id' => $dept->id,
                'role' => 'instructor',
            ]);
        InstructorPerformance::factory()->create([
            'year' => date('Y'),
            'instructor_id' => $role2->id,
        ]);

        CourseSection::factory()->create();
        Teach::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(StaffList::class);

        $component->assertSee($user1->firstname)
                  ->assertSee($user1->lastname);
        $component->assertSee($user2->firstname)
                  ->assertSee($user2->lastname);

    }

    public function test_user_can_add_target_hours():void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);

        $instructor = User::factory()->create();
        $role = UserRole::factory()->create([
            'user_id' =>  $instructor->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);
        InstructorPerformance::factory()->create([
            'year' => date('Y'),
            'instructor_id' => $role->id,
        ]);

        $this->actingAs($user);

        Livewire::test(StaffList::class)
        ->set('selectedYear', date('Y'))
        ->set('staffCheckboxes', [$instructor->email])
        ->set('hours', 200)
        ->call('submit');
        //->assertRedirect('/staff');

        $this->assertDatabaseHas('instructor_performance', [
            'instructor_id' => $role->id,
            'target_hours' => 200,
            'year' => date('Y')
        ]);

    }

    public function test_user_can_edit_target_hours():void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);
        $instructor = User::factory()->create();
        $role = UserRole::factory()->create([
            'user_id' => $instructor->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);
        InstructorPerformance::factory()->create([
            'target_hours' => 200,
            'year' => date('Y'),
            'instructor_id' => $role->id,
        ]);

        $this->actingAs($user);

        Livewire::test(StaffList::class)
        ->set('selectedYear', date('Y'))
        ->call('update', $instructor->email, 400)
        ->call('save');

        $this->assertDatabaseHas('instructor_performance', [
            'instructor_id' => $role->id,
            'target_hours' => 400,
            'year' => date('Y')
        ]);

    }

    public function test_can_search_for_user():void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);
        $instructor = User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        $role = UserRole::factory()->create([
            'user_id' => $instructor->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);
        InstructorPerformance::factory()->create([
            'year' => date('Y'),
            'instructor_id' => $role->id,
        ]);

        $this->actingAs($user);

        $component = Livewire::test(StaffList::class)
        ->set('searchTerm', 'Test');

        $component->assertSee($instructor->firstname)
                  ->assertSee($instructor->lastname);
    }

    public function test_can_filter_by_subarea():void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $area1 = Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $area2 = Area::factory()->create(['name' => 'Mathematics','dept_id' => $dept->id]);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);
        $user1 =  User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        $role1 = UserRole::factory()->create([
                'user_id' => $user1->id,
                'department_id' => $dept->id,
                'role' => 'instructor',
            ]);
        InstructorPerformance::factory()->create([
            'year' => date('Y'),
            'instructor_id' => $role1->id,
        ]);

        $user2 =  User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password'
        ]);
        $role2 = UserRole::factory()->create([
                'user_id' => $user2->id,
                'department_id' => $dept->id,
                'role' => 'instructor',
            ]);
        InstructorPerformance::factory()->create([
            'year' => date('Y'),
            'instructor_id' => $role2->id,
        ]);

        $course1 = CourseSection::factory()->create([
            'area_id'=>$area1->id
        ]);
        $course2 = CourseSection::factory()->create([
            'area_id'=>$area2->id
        ]);
        Teach::factory()->create([
            'course_section_id'=> $course1->id,
            'instructor_id' => $role1->id,
        ]);
        Teach::factory()->create([
            'course_section_id'=> $course2->id,
            'instructor_id' => $role2->id,
        ]);

        $this->actingAs($user);

        $component = Livewire::test(StaffList::class)
        ->set('selectedAreas', ['Mathematics'])
        ->call('filter');

        $component->assertSee($user2->firstname)
                  ->assertSee($user2->lastname)
                  ->assertDontSee($user1->firstname)
                  ->assertDontSee($user1->lastname);
    }

    public function test_user_can_sort_staff_list(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $area1 = Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $area2 = Area::factory()->create(['name' => 'Mathematics','dept_id' => $dept->id]);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);
    
        $user1 = User::factory()->create([
            'firstname' => 'Adam',
            'lastname' => 'Smith',
            'email' => 'adam.smith@example.com',
            'password' => 'password'
        ]);
        $role1 = UserRole::factory()->create([
            'user_id' => $user1->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);
        InstructorPerformance::factory()->create([
            'score' => 80,
            'total_hours' => json_encode([
                'January' => 120,
                'February' => 120,
                'March' => 120,
                'April' => 120,
                'May' => 120,
                'June' => 120,
                'July' => 120,
                'August' => 120,
                'September' => 120,
                'October' => 120,
                'November' => 120,
                'December' => 120,
            ]),
            'target_hours' => 1400,
            'year' => date('Y'),
            'instructor_id' => $role1->id,
        ]);
    
        $user2 = User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password'
        ]);
        $role2 = UserRole::factory()->create([
            'user_id' => $user2->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);
        InstructorPerformance::factory()->create([
            'score' => 75,
            'total_hours' => json_encode([
                'January' => 140,
                'February' => 140,
                'March' => 140,
                'April' => 140,
                'May' => 140,
                'June' => 140,
                'July' => 140,
                'August' => 140,
                'September' => 140,
                'October' => 140,
                'November' => 140,
                'December' => 140,
            ]),
            'target_hours' => 1150,
            'year' => date('Y'),
            'instructor_id' => $role2->id,
        ]);
    
        $course1 = CourseSection::factory()->create([
            'area_id' => $area1->id
        ]);
        $course2 = CourseSection::factory()->create([
            'area_id' => $area2->id
        ]);
        Teach::factory()->create([
            'course_section_id' => $course1->id,
            'instructor_id' => $role1->id,
        ]);
        Teach::factory()->create([
            'course_section_id' => $course2->id,
            'instructor_id' => $role2->id,
        ]);
    
        $this->actingAs($user);
    
        // Initialize Livewire test instance
        $component = Livewire::test(StaffList::class);
    
        // Sorting by first name
        $component->set('sortDirection', 'asc')
            ->set('sortField', 'firstname')
            ->assertSeeInOrder([$user1->firstname, $user2->firstname]);
    
        $component->set('sortDirection', 'desc')
            ->set('sortField', 'firstname')
            ->assertSeeInOrder([$user2->firstname, $user1->firstname]);
    
        // Sorting by area
        $component->set('sortDirection', 'asc')
            ->set('sortField', 'area')
            ->assertSeeInOrder([$user1->firstname, $user2->firstname]);
    
        $component->set('sortDirection', 'desc')
            ->set('sortField', 'area')
            ->assertSeeInOrder([$user2->firstname, $user1->firstname]);
    
        // Sorting by completed hours
        $component->set('sortDirection', 'asc')
            ->set('sortField', 'total_hours')
            ->assertSeeInOrder([$user1->firstname, $user2->firstname]);
    
        $component->set('sortDirection', 'desc')
            ->set('sortField', 'total_hours')
            ->assertSeeInOrder([$user2->firstname, $user1->firstname]);
    
        // Sorting by target hours
        $component->set('sortDirection', 'asc')
            ->set('sortField', 'target_hours')
            ->assertSeeInOrder([$user2->firstname, $user1->firstname]);
    
        $component->set('sortDirection', 'desc')
            ->set('sortField', 'target_hours')
            ->assertSeeInOrder([$user1->firstname, $user2->firstname]);
    
        // Sorting by rating
        $component->set('sortDirection', 'asc')
            ->set('sortField', 'score')
            ->assertSeeInOrder([$user2->firstname, $user1->firstname]);
    
        $component->set('sortDirection', 'desc')
            ->set('sortField', 'score')
            ->assertSeeInOrder([$user1->firstname, $user2->firstname]);
    }
    

    public function test_user_can_select_month(){
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);

        $instructor = User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        $role = UserRole::factory()->create([
            'user_id' => $instructor->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);

        $totalHours = [
            'January' => 120,
            'February' => 200,
            'March' => 230,
            'April' => 340,
            'May' => 150,
            'June' => 160,
            'July' => 170,
            'August' => 180,
            'September' => 190,
            'October' => 105,
            'November' => 110,
            'December' => 115,
        ];
        $perf1 = InstructorPerformance::factory()->create([
            'total_hours' => json_encode($totalHours),
            'year' => date('Y'),
            'instructor_id' => $role->id,
        ]);

        $this->actingAs($user);

        $selectedMonth = date('F');
        $selectedYear = date('Y');

        $component = Livewire::test(StaffList::class)
            ->set('selectedYear', $selectedYear)
            ->set('selectedMonth', $selectedMonth)
            ->assertSee($totalHours[$selectedMonth])
            ->assertDontSee($totalHours['January']);

        $selectedMonth = 'January';

        $component->set('selectedMonth', $selectedMonth)
            ->assertSee($totalHours[$selectedMonth])
            ->assertDontSee($totalHours[date('F')]);
    }

    public function test_user_can_select_year(){
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);

        $instructor = User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        $role = UserRole::factory()->create([
            'user_id' => $instructor->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);
        $perf1 = InstructorPerformance::factory()->create([
            'target_hours' => 1500,
            'year' => date('Y'),
            'instructor_id' => $role->id,
        ]);
        $perf2 = InstructorPerformance::factory()->create([
            'target_hours' => 1200,
            'year' => date('Y')-1,
            'instructor_id' => $role->id,
        ]);

        $this->actingAs($user);

        Livewire::test(StaffList::class)
        ->set('selectedYear', date('Y'))
        ->assertSee($perf1->target_hours)
        ->assertDontSee($perf2->target_hours);

        Livewire::test(StaffList::class)
        ->set('selectedYear', date('Y')-1)
        ->assertSee($perf2->target_hours)
        ->assertDontSee($perf1->target_hours);
    }
}
