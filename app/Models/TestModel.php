<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use HasFactory;

    protected $table = 'test_table'; // Specify the table name

    protected $fillable = [
        'customer_id',
        'firstname',
        'lastname',
    
        // Add other columns if necessary
    ];
}
