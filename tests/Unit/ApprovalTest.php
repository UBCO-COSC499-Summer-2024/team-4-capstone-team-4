<?php

namespace Tests\Unit;

use App\Models\Approval;
use App\Models\ApprovalStatus;
use App\Models\ApprovalType;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_approval()
    {
        $approval = Approval::factory()->create();
        $this->assertEquals(1, Approval::count());
    }

    public function test_can_update_approval()
    {
        $approval = Approval::factory()->create();
        $data = [
            'details' => 'This is a new detail',
        ];
        $approval->update($data);
        $this->assertDatabaseHas('approvals', $data);
    }

    public function test_can_delete_approval()
    {
        $approval = Approval::factory()->create();
        $approval->delete();
        $this->assertEquals(0, Approval::count());
    }

    public function test_can_list_approvals()
    {
        Approval::factory(2)->create();
        $this->assertEquals(2, Approval::count());
    }

    public function test_can_show_approval()
    {
        $approval = Approval::factory()->create();
        $this->assertIsInt($approval->id);
    }

    public function test_can_get_approval_user()
    {
        $approval = Approval::factory()->create();
        $this->assertIsInt($approval->user->id);
    }

    public function test_can_get_approval_type()
    {
        $approval = Approval::factory()->create();
        $this->assertIsInt($approval->approvalType->id);
    }

    public function test_can_get_approval_status()
    {
        $approval = Approval::factory()->create();
        $this->assertIsInt($approval->status->id);
    }

    public function test_can_get_approval_approved_by()
    {
        $approval = Approval::factory()->create();
        $this->assertIsInt($approval->approvedBy->id);
    }

    public function test_can_get_approval_rejected_by()
    {
        $approval = Approval::factory()->create();
        $this->assertIsInt($approval->rejectedBy->id);
    }

    public function test_can_get_approval_histories()
    {
        $approval = Approval::factory()->create();
        $this->assertIsInt($approval->histories->count());
    }

    public function test_can_check_approval_status()
    {
        $approval = Approval::factory()->create();
        $this->assertIsInt($approval->checkStatus());
    }

    public function test_can_get_approval_approved_count()
    {
        $approval = Approval::factory()->create();
        $this->assertIsInt($approval->approvedCount());
    }

    public function test_can_get_approval_required_approvals()
    {
        $approval = Approval::factory()->create();
        $this->assertIsInt($approval->requiredApprovals());
    }

    public function test_can_get_approval_is_approved()
    {
        $approval = Approval::factory()->create();
        $this->assertIsBool($approval->isApproved());
    }

    public function test_can_get_approval_is_rejected()
    {
        $approval = Approval::factory()->create();
        $this->assertIsBool($approval->isRejected());
    }

    public function test_can_get_approval_is_intermediate()
    {
        $approval = Approval::factory()->create();
        $this->assertIsBool($approval->isIntermediate());
    }

    public function test_can_get_approval_is_pending()
    {
        $approval = Approval::factory()->create();
        $this->assertIsBool($approval->isPending());
    }

    public function test_can_get_approval_is_cancelled()
    {
        $approval = Approval::factory()->create();
        $this->assertIsBool($approval->isCancelled());
    }
}
