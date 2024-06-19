<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    public function audit($action, $description, $userId = null, $table = null, $op = null, $oldData = [], $newData = [])
    {
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
}
