<?php

namespace Database\Seeders;

use App\Models\CourseSection;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@example.com',
            'acces_code' => 'admin'
        ]);
    }
}
