<?php

namespace Tests\Unit;

use App\Models\Area;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use App\Models\ServiceRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceRoleTest extends TestCase {
    use RefreshDatabase;
    /**
     * Test if service_roles table exists.
     *
     * @return void
     */
    public function test_service_role_table_exists() {
        $this->assertTrue(
            Schema::hasTable('service_roles'),
            'Service_Role table does not exist'
        );
    }

    /**
     * Test creating a service role.
     *
     * @return void
     */
    public function test_service_role_can_be_created() {
        // Create an area
        $area = Area::factory()->create();

        // Create a service role
        $serviceRole = ServiceRole::factory()->create();([
            'area_id'=> $area->id,
        ]);

        // Assert that the service role model exists
        $this->assertModelExists($serviceRole);

        // Assert that the service role was created successfully 
        $this->assertInstanceOf(ServiceRole::class, $serviceRole);

        // Assert that the service role has an area
        $this->assertNotEmpty($serviceRole->area_id);  

        // Assert that the service role has a name
        $this->assertNotEmpty($serviceRole->name); 
        
        // Assert that the service role has a description
        $this->assertNotEmpty($serviceRole->description); 

        // Assert that the service role has a year
        $this->assertNotEmpty($serviceRole->year); 

        // Assert that the service role has monthly hours
        $this->assertNotEmpty($serviceRole->monthly_hours); 
    }
}
