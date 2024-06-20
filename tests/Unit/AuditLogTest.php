<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\AuditLog;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating an AuditLog instance.
     */
    public function test_create_audit_log(): void
    {
        $auditLog = AuditLog::factory()->create();

        $this->assertDatabaseHas('audit_logs', [
            'log_id' => $auditLog->log_id,
        ]);
    }

    /**
     * Test setting attributes.
     */
    public function test_set_attributes(): void
    {
        $attributes = [
            'user_id' => 1,
            'user_alt' => 'user123',
            'action' => 'create',
            'description' => 'Created a new record',
            'table_name' => 'users',
            'operation_type' => 'insert',
            'old_value' => json_encode(['name' => 'Old Name']),
            'new_value' => json_encode(['name' => 'New Name']),
            'timestamp' => now(),
        ];

        $auditLog = AuditLog::create($attributes);

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $auditLog->$key);
        }
    }

    /**
     * Test JSON encoding and decoding for old_value and new_value.
     */
    public function test_json_encoding_decoding(): void
    {
        $oldValue = ['name' => 'Old Name'];
        $newValue = ['name' => 'New Name'];

        $auditLog = AuditLog::factory()->create([
            'old_value' => json_encode($oldValue),
            'new_value' => json_encode($newValue),
        ]);

        $this->assertEquals($oldValue, json_decode($auditLog->old_value, true));
        $this->assertEquals($newValue, json_decode($auditLog->new_value, true));
    }

    /**
     * Test nullable fields.
     */
    public function test_nullable_fields(): void
    {
        $auditLog = AuditLog::factory()->create([
            'user_id' => null,
            'description' => null,
            'old_value' => null,
            'new_value' => null,
        ]);

        $this->assertNull($auditLog->user_id);
        $this->assertNull($auditLog->description);
        $this->assertNull($auditLog->old_value);
        $this->assertNull($auditLog->new_value);
    }

    /**
     * Test required fields.
     */
    public function test_required_fields(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        AuditLog::create([
            'user_alt' => null,
            'action' => null,
            'table_name' => null,
            'operation_type' => null,
        ]);
    }
}
