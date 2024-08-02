<?php

namespace Tests\Unit;

use App\Models\ApprovalType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_approval_type()
    {
        $approvalType = ApprovalType::factory()->create();
        $this->assertEquals(1, ApprovalType::count());
    }
}
