<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Area;
use App\Models\Department;
use App\Models\InstructorPerformance;
use App\Models\UserRole;
use App\Models\CourseSection;
use App\Models\Teach;
use Livewire\Livewire;
use App\Livewire\Leaderboard;

/**
 * Class LeaderboardTest
 * 
 * Tests the functionality of the Leaderboard component.
 */
class LeaderboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the leaderboard page can be rendered.
     *
     * @return void
     */
    public function testLeaderboardCanBeRendered(): void {

        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();

        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);

        $response = $this->actingAs($user)->get('/leaderboard');

        $response->assertStatus(200);
    }

    /**
     * Test that instructors cannot access the leaderboard.
     *
     * @return void
     */
    public function testInstructorCannotAccessLeaderboard(): void {

        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();

        UserRole::factory()->create([
            'user_id' => $user->id,
            'role' => 'instructor',
        ]);

        $response = $this->actingAs($user)->get('/leaderboard');

        $response->assertStatus(403);
    }

    /**
     * Test that the leaderboard lists instructors.
     *
     * @return void
     */
    public function testLeaderboardCanListInstructors(): void {

        $dept = Department::factory()->create(['name' => 'CMPS']);
        Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $user1 = User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        $role1 = UserRole::factory()->create([
            'user_id' => $user1->id,
            'role' => 'instructor',
        ]);
        InstructorPerformance::factory()->create([
            'year' => date('Y'),
            'instructor_id' => $role1->id,
        ]);

        $user2 = User::factory()->create([
            'firstname' => 'Test2',
            'lastname' => 'User2',
            'email' => 'test2@example.com',
            'password' => 'password'
        ]);
        $role2 = UserRole::factory()->create([
            'user_id' => $user2->id,
            'role' => 'instructor',
        ]);
        InstructorPerformance::factory()->create([
            'year' => date('Y'),
            'instructor_id' => $role2->id,
        ]);

        $component = Livewire::test(Leaderboard::class);

        $component->assertSee($user1->firstname)
                  ->assertSee($user1->lastname);
        $component->assertSee($user2->firstname)
                  ->assertSee($user2->lastname);
    }

    /**
     * Test that the area filter works correctly.
     *
     * @return void
     */
    public function testAreaFilter(): void {

        $dept = Department::factory()->create(['name' => 'CMPS']);
        $area1 = Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $area2 = Area::factory()->create(['name' => 'Mathematics','dept_id' => $dept->id]);
        $user1 = User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        $role1 = UserRole::factory()->create([
            'user_id' => $user1->id,
            'role' => 'instructor',
        ]);
        InstructorPerformance::factory()->create([
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
            'role' => 'instructor',
        ]);
        InstructorPerformance::factory()->create([
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

        $this->actingAs($user1);

        $component = Livewire::test(Leaderboard::class)
            ->set('selectedAreas', ['Mathematics'])
            ->call('filter');

        $component->assertSee($user2->firstname)
                  ->assertSee($user2->lastname)
                  ->assertDontSee($user1->firstname)
                  ->assertDontSee($user1->lastname);
    }
}


