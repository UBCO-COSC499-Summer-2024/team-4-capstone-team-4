<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;
    // $table->id('log_id');
    // $table->unsignedBigInteger('user_id')->nullable();
    // $table->string('user_alt');
    // $table->string('action');
    // $table->text('description')->nullable();
    // $table->string('table_name');
    // $table->string('operation_type');
    // $table->jsonb('old_value')->nullable();
    // $table->jsonb('new_value')->nullable();
    // $table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
    // $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
    // $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

    protected $table = "audit_logs";
    protected $fillable = [
        'user_id',
        'user_alt',
        'action',
        'description',
        'table_name',
        'operation_type',
        'old_value',
        'new_value',
        'timestamp',
    ];

    public function audit($action, $description, $userId = null, $table = null, $op = null, $oldData = [], $newData = []) {
        $user = null;
        if ($userId) {
            $user = User::find($userId);
        }
        $user_alt = $user ? $user->getName() : 'system';
        AuditLog::create([
            'user_id' => $userId ?? null,
            'user_alt' => $user_alt,
            'action' => $action,
            'description' => $description,
            'table_name' => $table,
            'operation_type' => $op,
            'old_value' => json_encode($oldData),
            'new_value' => json_encode($newData),
            'timestamp' => now(),
        ]);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
