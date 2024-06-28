<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StaffListTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_staff_page_can_be_rendered(): void
    {
        $response = $this->get('/staff');

        $response->assertStatus(200);
    }

    public function test_staff_edit_mode_can_be_rendered(): void
    {
        $response = $this->get('/staff-edit-mode');

        $response->assertStatus(200);
    }

    public function test_staff_page_shows_a_list_of_instructors(): void
    {
        $dept = \App\Models\Department::factory()->create(['name' => 'CMPS']);
        \App\Models\Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $user =  \App\Models\User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        \App\Models\UserRole::factory()->create([
                'user_id' => $user->id,
                'department_id' => 1,
                'role' => 'instructor',
            ]);
        \App\Models\InstructorPerformance::factory()->create([
            'score' => '0',
            'total_hours' => '0',
            'target_hours' => '0',
            'sei_avg' => '0',
            'year' => '2024',
            'instructor_id' => $user->id,
        ]);

        \App\Models\CourseSection::factory()->create();
        \App\Models\Teach::factory()->create();

        $response = $this->get('/staff');

        $response->assertSeeLivewire('staff-list::index');
        $response->assertSeeInOrder($user->pluck('firstname')->all());
    }

    public function test_user_can_add_target_hours():void
    {
        $user = \App\Models\User::factory()->create();
        \App\Models\UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => 1,
            'role' => 'instructor',
        ]);
        \App\Models\InstructorPerformance::factory()->create([
            'instructor_id' => $user->id,
        ]);
        $response = $this->post('/staff', [
            'staffCheckboxes' => [$user->email],
            'hours' => 200,
        ]);
        $response->assertRedirect('/staff');
        $this->assertDatabaseHas('instructor_performance', [
            'instructor_id' => $user->id,
            'target_hours' => 200,
        ]);

    }

    public function test_can_search_for_user():void{
        $user = \App\Models\User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        \App\Models\UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => 1,
            'role' => 'instructor',
        ]);
        \App\Models\InstructorPerformance::factory()->create([
            'instructor_id' => $user->id,
        ]);
        $response = $this->post('/staff', [
            'searchTerm' => 'Test'
        ]);
        $response->assertSeeLivewire('staff-list::index');
        $response->assertSeeInOrder($user->pluck('firstname')->all());
    }
}
