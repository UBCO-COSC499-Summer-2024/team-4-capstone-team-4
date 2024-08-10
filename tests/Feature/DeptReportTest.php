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
use App\Models\DepartmentPerformance;
use App\Models\AreaPerformance;
use Livewire\Livewire;
use App\Livewire\ExportDeptReport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DeptReportExport;

class DeptReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_dept_report_page_can_be_rendered(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'dept_head',
        ]);

        $response = $this->actingAs($user)->get('/dept-report');

        $response->assertStatus(200);
    }

    public function test_dept_report_page_cannot_be_accessed_by_instructor(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'instructor',
        ]);

        $response = $this->actingAs($user)->get('/dept-report');

        $response->assertStatus(403);
    }

    public function test_user_can_select_year(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $area1 = Area::factory()->create(['name' => 'Computer Science', 'dept_id' => $dept->id]);
        $area2 = Area::factory()->create(['name' => 'Mathematics', 'dept_id' => $dept->id]);
        
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

        // Create performance for this year (2024)
        $currentYear = date('Y');
        $deptPerf = DepartmentPerformance::factory()->create([
            'dept_id' => $dept->id,
            'year' => $currentYear,
            'sei_avg' => 4.3,
            'enrolled_avg' => 70.3,
            'dropped_avg' => 20.6,
        ]);
        $areaPerf1 = AreaPerformance::factory()->create([
            'area_id' => $area1->id,
            'year' => $currentYear,
            'sei_avg' => 4.9,
            'enrolled_avg' => 71.7,
            'dropped_avg' => 21.3,
        ]);
        $areaPerf2 = AreaPerformance::factory()->create([
            'area_id' => $area2->id,
            'year' => $currentYear,
            'sei_avg' => 4.2,
            'enrolled_avg' => 72.5,
            'dropped_avg' => 22.5,
        ]);

        // Create performance for last year (2023)
        $lastYear = $currentYear - 1;
        $deptPerf2 = DepartmentPerformance::factory()->create([
            'dept_id' => $dept->id,
            'year' => $lastYear,
            'sei_avg' => 3.1,
            'enrolled_avg' => 62.1,
            'dropped_avg' => 32.3,
        ]);
        $areaPerf3 = AreaPerformance::factory()->create([
            'area_id' => $area1->id,
            'year' => $lastYear,
            'sei_avg' => 3.5,
            'enrolled_avg' => 85.4,
            'dropped_avg' => 29.2,
        ]);
        $areaPerf4 = AreaPerformance::factory()->create([
            'area_id' => $area2->id,
            'year' => $lastYear,
            'sei_avg' => 3.6,
            'enrolled_avg' => 82.9,
            'dropped_avg' => 31.8,
        ]);

        // Authenticate as user
        $this->actingAs($user);

        // Test for current year
        $component = Livewire::test(ExportDeptReport::class)
            ->set('year', $currentYear);

        $component->assertSee((string)$deptPerf->enrolled_avg)
            ->assertSee((string)$deptPerf->dropped_avg)
            ->assertSee((string)$deptPerf->sei_avg)
            ->assertSee((string)$areaPerf1->enrolled_avg)
            ->assertSee((string)$areaPerf1->dropped_avg)
            ->assertSee((string)$areaPerf1->sei_avg)
            ->assertSee((string)$areaPerf2->enrolled_avg)
            ->assertSee((string)$areaPerf2->dropped_avg)
            ->assertSee((string)$areaPerf2->sei_avg)
            ->assertDontSee((string)$deptPerf2->enrolled_avg)
            ->assertDontSee((string)$deptPerf2->dropped_avg)
            ->assertDontSee((string)$deptPerf2->sei_avg)
            ->assertDontSee((string)$areaPerf3->enrolled_avg)
            ->assertDontSee((string)$areaPerf3->dropped_avg)
            ->assertDontSee((string)$areaPerf3->sei_avg)
            ->assertDontSee((string)$areaPerf4->enrolled_avg)
            ->assertDontSee((string)$areaPerf4->dropped_avg)
            ->assertDontSee((string)$areaPerf4->sei_avg);

        // Test for last year
        $component2 = Livewire::test(ExportDeptReport::class)
            ->set('year', $lastYear);   

        $component2->assertSee((string)$deptPerf2->enrolled_avg)
            ->assertSee((string)$deptPerf2->dropped_avg)
            ->assertSee((string)$deptPerf2->sei_avg)
            ->assertSee((string)$areaPerf3->enrolled_avg)
            ->assertSee((string)$areaPerf3->dropped_avg)
            ->assertSee((string)$areaPerf3->sei_avg)
            ->assertSee((string)$areaPerf4->enrolled_avg)
            ->assertSee((string)$areaPerf4->dropped_avg)
            ->assertSee((string)$areaPerf4->sei_avg)
            ->assertDontSee((string)$deptPerf->enrolled_avg)
            ->assertDontSee((string)$deptPerf->dropped_avg)
            ->assertDontSee((string)$deptPerf->sei_avg)
            ->assertDontSee((string)$areaPerf1->enrolled_avg)
            ->assertDontSee((string)$areaPerf1->dropped_avg)
            ->assertDontSee((string)$areaPerf1->sei_avg)
            ->assertDontSee((string)$areaPerf2->enrolled_avg)
            ->assertDontSee((string)$areaPerf2->dropped_avg)
            ->assertDontSee((string)$areaPerf2->sei_avg);
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
        $deptPerf = DepartmentPerformance::factory()->create([
            'dept_id' => $dept->id,
            'year' => date('Y'),
            'sei_avg' => 2.1,
            'enrolled_avg'=> 70,
            'dropped_avg' => 20,
        ]);
        $areaPerf1 = AreaPerformance::factory()->create([
            'area_id' => $area1->id,
            'year' => date('Y'),
            'sei_avg' => 2.2,
            'enrolled_avg'=> 71,
            'dropped_avg' => 21,
        ]);
        $areaPerf2 = AreaPerformance::factory()->create([
            'area_id' => $area2->id,
            'year' => date('Y'),
            'sei_avg' => 2.3,
            'enrolled_avg'=> 72,
            'dropped_avg' => 22,
        ]);

        // Authenticate as user
        $this->actingAs($user);

        Excel::fake();

        $component = Livewire::test(ExportDeptReport::class)
        ->set('year', date('Y'));

        $response = $component->call('exportAsExcel');

        $this->assertNotNull($response);

        //Assert that the response is a file download
        $name = $dept->name. " Department Report - " . date('Y');
        
        Excel::assertDownloaded($name.'.xlsx', function (DeptReportExport $export) use ($dept) {
            $exportData = $export->view()->getData();

            $this->assertEquals($dept->name, $exportData['dept']->name);
            $this->assertEquals(date('Y'), $exportData['year']);
            $this->assertCount(2, $exportData['areas']); 
            $this->assertNotNull($exportData['deptPerformance']);

            return true;
        });
       
    } 

}