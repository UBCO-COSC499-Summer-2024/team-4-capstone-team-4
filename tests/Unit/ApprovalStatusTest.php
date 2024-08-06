<?php

namespace Tests\Unit;

use App\Models\ApprovalStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_approval_status()
    {
        $approvalStatus = ApprovalStatus::factory()->create();
        $this->assertEquals(1, ApprovalStatus::count());
    }
}
