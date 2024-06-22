<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // $table->string('name');
        // $table->foreignId('dept_id')->constrained('departments')->cascadeOnDelete();
        for ($i = 1; $i <= 10; $i++) {
            Area::create([
                "name" => "Area $i",
                // random dept id
                "dept_id" => Department::inRandomOrder()->first()->id
            ]);
        }
    }
}
