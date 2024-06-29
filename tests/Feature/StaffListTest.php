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
        $user =  User::factory()->create([
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
            'score' => '0',
            'total_hours' => '0',
            'target_hours' => '0',
            'sei_avg' => '0',
            'year' => '2024',
            'instructor_id' => $role->id,
        ]);

        CourseSection::factory()->create();
        Teach::factory()->create();

        //$response = $this->actingAs($user)->get('/staff');

        $component = Livewire::test(StaffList::class);

        $component->assertSee($user->firstname)
                  ->assertSee($user->lastname);

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
}
