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
use App\Livewire\StaffListEditMode;

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

    public function test_staff_edit_mode_can_be_rendered(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);

        $response = $this->actingAs($user)->get('/staff-edit-mode');

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

        $response2 = $this->actingAs($user)->get('/staff-edit-mode');

        $response2->assertStatus(403);
    }

    public function test_staff_page_shows_a_list_of_instructors(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
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

        $component = Livewire::test(StaffList::class);

        $component->assertSee($user1->firstname)
                  ->assertSee($user1->lastname);
        $component->assertSee($user2->firstname)
                  ->assertSee($user2->lastname);

    }

    public function test_user_can_add_target_hours():void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        $role = UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);
        InstructorPerformance::factory()->create([
            'year' => date('Y'),
            'instructor_id' => $role->id,
        ]);

        $this->actingAs($user);

        Livewire::test(StaffList::class)
        ->set('staffCheckboxes', [$user->email])
        ->set('hours', 200)
        ->call('submit');
        //->assertRedirect('/staff');

        $this->assertDatabaseHas('instructor_performance', [
            'instructor_id' => $role->id,
            'target_hours' => 200,
        ]);

    }

    public function test_user_can_edit_target_hours():void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        $role = UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);
        InstructorPerformance::factory()->create([
            'target_hours' => 200,
            'year' => date('Y'),
            'instructor_id' => $role->id,
        ]);

        $this->actingAs($user);

        Livewire::test(StaffListEditMode::class)
        ->call('update', $user->email, 400)
        ->call('save');

        $this->assertDatabaseHas('instructor_performance', [
            'instructor_id' => $role->id,
            'target_hours' => 400,
        ]);

    }

    public function test_can_search_for_user():void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        $role = UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);
        InstructorPerformance::factory()->create([
            'instructor_id' => $role->id,
        ]);

        $this->actingAs($user);

        $component = Livewire::test(StaffList::class)
        ->set('searchTerm', 'Test');

        $component->assertSee($user->firstname)
                  ->assertSee($user->lastname);
    }

    public function test_can_filter_by_subarea():void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $area1 = Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $area2 = Area::factory()->create(['name' => 'Mathematics','dept_id' => $dept->id]);
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

        $this->actingAs($user1);

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
        
        $user1 =  User::factory()->create([
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
                'January' => 20,
                'February' => 20,
                'March' => 20,
                'April' => 20,
                'May' => 20,
                'June' => 20,
                'July' => 20,
                'August' => 20,
                'September' => 20,
                'October' => 20,
                'November' => 20,
                'December' => 20,
            ]),
            'target_hours' => 400,
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
            'score' => 75,
            'total_hours' => json_encode([
                'January' => 40,
                'February' => 40,
                'March' => 40,
                'April' => 40,
                'May' => 40,
                'June' => 40,
                'July' => 40,
                'August' => 40,
                'September' => 40,
                'October' => 40,
                'November' => 40,
                'December' => 40,
            ]),
            'target_hours' => 150,
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

        $this->actingAs($user1);

        //sorting by first name
        Livewire::test(StaffList::class)
        ->set('sortDirection', 'asc')
        ->set('sortField', 'firstname')
        ->assertSeeInOrder([$user1->firstname, $user2->firstname]);

        Livewire::test(StaffList::class)
        ->set('sortDirection', 'desc')
        ->set('sortField', 'firstname')
        ->assertSeeInOrder([$user2->firstname, $user1->firstname]);

        //sorting by subarea
        Livewire::test(StaffList::class)
        ->set('sortDirection', 'asc')
        ->set('sortField', 'area')
        ->assertSeeInOrder([$user1->firstname, $user2->firstname]);

        Livewire::test(StaffList::class)
        ->set('sortDirection', 'desc')
        ->set('sortField', 'area')
        ->assertSeeInOrder([$user2->firstname, $user1->firstname]);

        //sorting by completed hours
        Livewire::test(StaffList::class)
        ->set('sortDirection', 'asc')
        ->set('sortField', 'total_hours')
        ->assertSeeInOrder([$user1->firstname, $user2->firstname]);

        Livewire::test(StaffList::class)
        ->set('sortDirection', 'desc')
        ->set('sortField', 'total_hours')
        ->assertSeeInOrder([$user2->firstname, $user1->firstname]);

        //sorting by target hours
        Livewire::test(StaffList::class)
        ->set('sortDirection', 'asc')
        ->set('sortField', 'target_hours')
        ->assertSeeInOrder([$user2->firstname, $user1->firstname]);

        Livewire::test(StaffList::class)
        ->set('sortDirection', 'desc')
        ->set('sortField', 'target_hours')
        ->assertSeeInOrder([$user1->firstname, $user2->firstname]);

        //sorting by rating
        Livewire::test(StaffList::class)
        ->set('sortDirection', 'asc')
        ->set('sortField', 'score')
        ->assertSeeInOrder([$user2->firstname, $user1->firstname]);

        Livewire::test(StaffList::class)
        ->set('sortDirection', 'desc')
        ->set('sortField', 'score')
        ->assertSeeInOrder([$user1->firstname, $user2->firstname]);

    }
}
