<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperAudit extends Model
{
    use HasFactory;

    protected $table = 'super_audits';

    
    // $table->bigIncrements('id');
    // $table->string('audit_user')->nullable();
    // $table->text('application_name')->nullable();
    // $table->string('client_addr', 45)->nullable();
    // $table->integer('client_port')->nullable();
    // $table->timestamp('occurred_at', 6)->useCurrent(); 
    // $table->text('statement_tag')->nullable(); 
    // $table->text('transaction_id')->nullable();
    // $table->text('query')->nullable();
    // $table->jsonb('params')->nullable(); 

    // // --- Additional Fields --- 

    // $table->text('session_id')->nullable();
    // $table->integer('pid')->nullable();
    // $table->text('user_query')->nullable();
    // $table->text('schema_name')->nullable();
    // $table->text('relation_name')->nullable();
    // $table->string('object_type')->nullable();
    // $table->string('command_tag')->nullable(); 
    // $table->integer('return_rows')->nullable();
    // $table->string('session_user')->nullable(); 
    // $table->text('security_label')->nullable();
    // $table->jsonb('context')->nullable(); 

    protected $fillable = [
        'audit_user',
        'application_name',
        'client_addr',
        'client_port',
        'occurred_at',
        'statement_tag',
        'transaction_id',
        'query',
        'params',
        'session_id',
        'pid',
        'user_query',
        'schema_name',
        'relation_name',
        'object_type',
        'command_tag',
        'return_rows',
        'session_user',
        'security_label',
        'context',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'params' => 'array',
        'context' => 'array',
    ];

    public function getParamsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setParamsAttribute($value)
    {
        $this->attributes['params'] = json_encode($value);
    }

    public function getContextAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setContextAttribute($value)
    {
        $this->attributes['context'] = json_encode($value);
    }
    
    public function getOccurredAtAttribute($value)
    {
        return $value ? $value->format('Y-m-d H:i:s') : null;
    }

    public function setOccurredAtAttribute($value)
    {
        $this->attributes['occurred_at'] = $value ? \Carbon\Carbon::parse($value) : null;
    }
}
