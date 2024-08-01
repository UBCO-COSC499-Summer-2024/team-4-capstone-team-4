<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InstructorReportExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InstructorReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_report_page_can_be_rendered(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
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

        $response = $this->actingAs($user)->get('/instructor-report/'.$instructorRole->id);

        $response->assertStatus(200);
    }

    public function test_instructor_report_page_cannot_be_accessed_by_instructor(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        $instructorRole = UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);

        $response = $this->actingAs($user)->get('/instructor-report/'.$instructorRole->id);

        $response->assertStatus(403);
    }

    public function test_instructor_report_page_shows_error_when_instructor_id_is_invalid(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        $deptRole = UserRole::factory()->create([
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

        $response = $this->actingAs($user)->get('/instructor-report/hello');
        $response->assertStatus(400);

        $response2 = $this->actingAs($user)->get('/instructor-report/22');
        $response2->assertStatus(404);

        $response = $this->actingAs($user)->get('/instructor-report/'.$deptRole->id);
        $response2->assertStatus(404);
    }

    public function test_user_can_select_year(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $area1 = Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $area2 = Area::factory()->create(['name' => 'Mathematics','dept_id' => $dept->id]);
        
        $user = User::factory()->create();
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
        $perf = InstructorPerformance::factory()->create([
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
            'questions'=>json_encode([
                    'q1' => 5,
                    'q2' => 5,
                    'q3' => 2,
                    'q4' => 2,
                    'q5' => 3,
                    'q6' => 3,
                ]),
            'course_section_id'=> $course->id,
        ]);

        //create performance for last year (2023)
        $perf2 = InstructorPerformance::factory()->create([
            'year' => 2023,
            'instructor_id' => $instructorRole->id,
        ]);
        $course2 = CourseSection::factory()->create([
            'year' => 2023,
        ]);
        Teach::factory()->create([
            'course_section_id'=> $course2->id,
        ]);
        SeiData::factory()->create([
            'questions'=>json_encode([
                    'q1' => 4,
                    'q2' => 4,
                    'q3' => 4,
                    'q4' => 3,
                    'q5' => 3,
                    'q6' => 3,
                ]),
            'course_section_id'=> $course2->id,
        ]);

        $this->actingAs($user);

        $component = Livewire::test(ExportReport::class, ['instructor_id' => $instructorRole->id])
        ->set('year', date('Y'));

        $component->assertSee($course->prefix . $course->number)
        ->assertDontSee($course2->prefix. $course2->number)
        ->assertSee($perf->sei_avg)
        ->assertDontSee($perf2->sei_avg);

        $component = Livewire::test(ExportReport::class, ['instructor_id' => $instructorRole->id])
        ->set('year', 2023);

        $component->assertSee($course2->prefix . $course2->number)
        ->assertDontSee($course->prefix. $course->number)
        ->assertSee($perf2->sei_avg)
        ->assertDontSee($perf->sei_avg);

       
    }

    public function test_user_can_export_report_as_xlsx(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $area1 = Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $area2 = Area::factory()->create(['name' => 'Mathematics','dept_id' => $dept->id]);
        
        $user = User::factory()->create();
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

        Excel::fake();

        $component = Livewire::test(ExportReport::class, ['instructor_id' => $instructorRole->id])
        ->set('year', date('Y'));

        $response = $component->call('exportAsExcel');
        //dd($component);

        $this->assertNotNull($response);

        //Assert that the response is a file download
        $name = $instructor->firstname . " " . $instructor->lastname . "'s Report - " . date('Y');
        
        Excel::assertDownloaded($name.'.xlsx', function (InstructorReportExport $export) use ($instructor) {
            $exportData = $export->view()->getData();

            $this->assertEquals($instructor->firstname, $exportData['instructor']->user->firstname);
            $this->assertEquals(date('Y'), $exportData['year']);
            $this->assertCount(1, $exportData['courses']); 
            $this->assertNotNull($exportData['performance']);

            return true;
        });
       
    } 

    /* public function test_user_can_export_report_as_pdf(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $area1 = Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $area2 = Area::factory()->create(['name' => 'Mathematics','dept_id' => $dept->id]);
        
        $user = User::factory()->create();
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

        $name = $instructor->firstname . " " . $instructor->lastname . "'s Report - " . date('Y');

        Livewire::test(ExportReport::class, ['instructor_id' => $instructorRole->id])
        ->set('year', date('Y'))
        //->dispatch('pdfSaved', $name.'.pdf')
        ->call('handlePdfSaved', $name.'.pdf')
        ->assertDispatched('show-toast', function ($event, $params) use ($name) {
            //dd($params);
            return $params[0]['message'] === 'PDF ' . $name . ' has been saved successfully!' &&
                   $params[0]['type'] === 'success';
        });
       
    }  */

}