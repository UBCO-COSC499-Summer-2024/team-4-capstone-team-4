<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Area;
use App\Models\Department;
use App\Models\InstructorPerformance;
use App\Models\UserRole;
use App\Models\CourseSection;
use App\Models\Teach;
use App\Models\SeiData;
use Livewire\Livewire;
use App\Livewire\ExportReport;


class ExportReportTest extends DuskTestCase
{
    use DatabaseMigrations;
   
    public function test_user_can_export_report_as_pdf(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $area1 = Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $area2 = Area::factory()->create(['name' => 'Mathematics','dept_id' => $dept->id]);
        
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);

        $instructor = User::factory()->create();
        $instructorRole = UserRole::factory()->create([
            'user_id' => $instructor->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);
        //create performance for this year (2024)
        InstructorPerformance::factory()->create([
            'year' => date('Y'),
            'instructor_id' => $instructorRole->id,
        ]);
        $course = CourseSection::factory()->create([
            'year' => date('Y'),
        ]);
        Teach::factory()->create([
            'course_section_id'=> $course->id,
        ]);
        SeiData::factory()->create([
            'course_section_id'=> $course->id,
        ]);

        $this->browse(function (Browser $browser) use ($user,$instructor, $instructorRole){
            $browser->visit('/login')
            ->type('email', $user->email)
            ->type('password', 'password')
            ->press('Login')
                        ->visit('/instructor-report/' . $instructorRole->id)
                        ->waitForText('Export', 10) 
                        ->assertSee('Export')
                        ->click('#exportButton')
                        ->pause(2000);
            $browser->screenshot('report.png');

            $name = $instructor->firstname . " " . $instructor->lastname . "'s Report - " . date('Y');

            //$browser->assertDownloaded($name . '.xlsx');
        });
       
    }
}
