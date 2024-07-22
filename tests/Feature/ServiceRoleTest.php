<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceRoleTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_page_load(): void {
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);
        $response = $this->actingAs($user)->get('/svcroles');

        $response->assertStatus(200);
    }

    public function test_page_load_unauthorized(): void {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/svcroles');

        $response->assertStatus(403);
    }

    public function test_page_load_unauthenticated(): void {
        $response = $this->get('/svcroles');

        $response->assertStatus(302);
    }

    public function test_page_as_instructor(): void {
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);
        $response = $this->actingAs($user)->get('/svcroles');

        $response->assertStatus(403);
    }

    
}
