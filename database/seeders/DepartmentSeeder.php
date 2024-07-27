<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $table->id();
        // $table->string('name');
        // $table->timestamps();
        for ($i = 0; $i < 10; $i++) {
            Department::create([
                "name" => "Department $i",
            ]);
        }
    }
}
