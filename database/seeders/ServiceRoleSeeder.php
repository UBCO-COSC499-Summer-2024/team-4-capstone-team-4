<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\ServiceRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $table->id();
        // $table->string('name');
        // $table->text('description');
        // $table->year('year');
        // $table->json('monthly_hours');
        // $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
        // $table->timestamps();
        for ($i = 1; $i <= 10; $i++) {
            ServiceRole::create([
                "name" => "Service Role $i",
                "description" => "Service Role $i description",
                "year" => 2024,
                "monthly_hours" => json_encode([
                    "jan" => 0,
                    "feb" => 0,
                    "mar" => 0,
                    "apr" => 0,
                    "may" => 0,
                    "jun" => 0,
                    "jul" => 0,
                    "aug" => 0,
                    "sep" => 0,
                    "oct" => 0,
                    "nov" => 0,
                    "dec" => 0,
                ]),
                "area_id" => Area::inRandomOrder()->first()->id
            ]);
        }
    }
}
