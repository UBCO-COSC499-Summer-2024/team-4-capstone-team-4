<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceRoleTest extends TestCase {
    use RefreshDatabase;

    /**
     * Test that the service roles page loads successfully for a department head.
     */
    public function test_department_head_can_access_service_roles_page(): void {
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);

        $response = $this->actingAs($user)->get('/svcroles');
        $response->assertStatus(200);
        $response->assertSee('Service Roles'); // Assuming 'Service Roles' text exists on the page
    }

    /**
     * Test that an unauthenticated user is redirected to the login page when accessing the service roles page.
     */
    public function test_unauthenticated_user_is_redirected_to_login(): void {
        $response = $this->get('/svcroles');
        $response->assertRedirect('/login');
    }

    /**
     * Test that an instructor can access the service roles page.
     */
    public function test_instructor_can_access_service_roles_page(): void {
        // $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            // 'department_id' => $dept->id,
            'role' => 'instructor',
        ]);

        $response = $this->actingAs($user)->get('/svcroles');
        $response->assertStatus(200);
        $response->assertSee('Service Roles');
    }

    /**
     * Test that a department staff can access the service roles page.
     */
    public function test_department_staff_can_access_service_roles_page(): void {
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_staff',
        ]);

        $response = $this->actingAs($user)->get('/svcroles');
        $response->assertStatus(200);
        $response->assertSee('Service Roles');
    }

    /**
     * Test that an admin can access the service roles page.
     */
    public function test_admin_can_access_service_roles_page(): void {
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/svcroles');
        $response->assertStatus(200);
        $response->assertSee('Service Roles');
    }

    /**
     * Test that a user with multiple roles including 'instructor' can access the service roles page.
     */
    public function test_user_with_multiple_roles_including_instructor_can_access_service_roles_page(): void {
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);

        $response = $this->actingAs($user)->get('/svcroles');
        $response->assertStatus(200);
        $response->assertSee('Service Roles'); // Assuming 'Service Roles' text exists on the page
    }

    /**
     * Test that a user with no roles cannot access the service roles page.
     */
    public function test_user_with_no_roles_cannot_access_service_roles_page(): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/svcroles');
        $response->assertStatus(401);
    }
}