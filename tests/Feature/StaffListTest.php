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
    
    public function test_staff_page_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/staff');

        $response->assertStatus(200);
    }

    public function test_staff_edit_mode_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/staff-edit-mode');

        $response->assertStatus(200);
    }

    public function test_staff_page_shows_a_list_of_instructors(): void
    {
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
        ->set('selectedAreas', ['Mathematics']);

        $component->assertSee($user2->firstname)
                  ->assertSee($user2->lastname)
                  ->assertDontSee($user1->firstname)
                  ->assertDontSee($user1->lastname);
    }
}
