<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'provider',
        'provider_id',
        'access_token',
        'user_id',
        '',
}
