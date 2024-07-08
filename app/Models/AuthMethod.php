<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'avatar',
        'token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getProvider($value)
    {
        return ucfirst($value);
    }

    public function setProvider($value)
    {
        $this->attributes['provider'] = strtolower($value);
    }
}
