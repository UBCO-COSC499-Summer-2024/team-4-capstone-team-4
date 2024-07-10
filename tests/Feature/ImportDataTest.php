<?php

namespace Tests\Feature;

use App\Livewire\ImportTabs;
use App\Models\Area;
use App\Models\CourseSection;
use App\Models\Department;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Livewire\Livewire;
use Tests\TestCase;

class ImportDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_page_can_be_rendered(): void
    {
        $user = User::factory()->create();

        UserRole::factory()->create([
            'user_id' => $user->id,
            'role' => 'dept_head',
        ]);

        $this->actingAs($user);
        $response = $this->get('/import');

        $response->assertStatus(200);

        $response->assertSeeLivewire('import-tabs');
    }

    public function test_import_sei_form_component_can_render_when_active_tab_is_sei(): void
    {
        Livewire::test(ImportTabs::class)
            ->set('activeTab' , '')
            ->call('setActiveTab', 'sei')
            ->assertSeeLivewire('import-sei-form');
    }

    public function test_import_workday_form_component_can_render_when_active_tab_is_workday(): void
    {
        Livewire::test(ImportTabs::class)
            ->set('activeTab' , '')
            ->call('setActiveTab', 'workday')
            ->assertSeeLivewire('import-workday-form');
    }

    public function test_import_file_component_can_render_when_active_tab_is_file(): void
    {
        Livewire::test(ImportTabs::class)
            ->set('activeTab' , '')
            ->call('setActiveTab', 'file')
            ->assertSeeLivewire('import-file');
    }

    public function test_form_renders_correctly(): void
    {
        // Create a user and assign a role
        $user = User::factory()->create();
        UserRole::factory()->create(['user_id' => $user->id, 'role' => 'admin']);
        $this->actingAs($user);

        // Test the form rendering
        Livewire::test('import-workday-form')
            ->assertSee('Course Name')
            ->assertSee('Section')
            ->assertSee('Area')
            ->assertSee('Session')
            ->assertSee('Term')
            ->assertSee('Year')
            ->assertSee('Enrolled')
            ->assertSee('Dropped')
            ->assertSee('Capacity');
    }

    public function test_valid_data_is_inserted_into_database(): void
    {

        $this->seed();
        // Create a user and assign a role
        $user = User::factory()->create();
        UserRole::factory()->create(['user_id' => $user->id, 'role' => 'admin']);
        $this->actingAs($user);

        // Test the form submission
        Livewire::test('import-workday-form')
            ->set('rows.0.course_name', 'COSC123')
            ->set('rows.0.section', '001')
            ->set('rows.0.area_id', 1)
            ->set('rows.0.session', 'W')
            ->set('rows.0.term', '1')
            ->set('rows.0.year', 2024)
            ->set('rows.0.enrolled', 30)
            ->set('rows.0.dropped', 5)
            ->set('rows.0.capacity', 40)
            ->call('handleSubmit')
            ->assertHasNoErrors();

        // $dept = Department::factory()->create([
        //     'name' => 'CMPS',
        // ]);

        // $area = Area::factory()->create([
        //     'name' => 'Computer Science',
        //     'dept_id' => $dept->id
        // ]);

        

        Livewire::test('import-workday-form')
            ->set('rows.0.course_name', 'COSC123')
            ->set('rows.0.section', '001')
            ->set('rows.0.area_id', 1)
            ->set('rows.0.session', 'W')
            ->set('rows.0.term', '1')
            ->set('rows.0.year', 2024)
            ->set('rows.0.enrolled', 30)
            ->set('rows.0.dropped', 5)
            ->set('rows.0.capacity', 40)
            ->call('handleSubmit')
            ->assertHasNoErrors();

            $courseSections = CourseSection::all();
            Log::info('Course Sections:', $courseSections->toArray());

        // Assert the data is in the database
        $this->assertDatabaseHas('course_sections', [
            'name' => 'COSC123',
            'section' => '001',
            'area_id' => 1,
            'session' => 'W',
            'term' => '1',
            'year' => 2024,
            'enrolled' => 30,
            'dropped' => 5,
            'capacity' => 40,
        ]);
    }

    public function test_validation_errors_are_handled(): void
    {
        // Create a user and assign a role
        $user = User::factory()->create();
        UserRole::factory()->create(['user_id' => $user->id, 'role' => 'admin']);
        $this->actingAs($user);

        // Test the form submission with invalid data
        Livewire::test('import-workday-form')
            ->set('rows.0.course_name', '')
            ->set('rows.0.section', '')
            ->set('rows.0.area_id', '')
            ->set('rows.0.session', '')
            ->set('rows.0.term', '')
            ->set('rows.0.year', '')
            ->set('rows.0.enrolled', '')
            ->set('rows.0.dropped', '')
            ->set('rows.0.capacity', '')
            ->call('handleSubmit')
            ->assertHasErrors([
                'rows.0.course_name' => 'required',
                'rows.0.section' => 'required',
                'rows.0.area_id' => 'required',
                'rows.0.session' => 'required',
                'rows.0.term' => 'required',
                'rows.0.year' => 'required',
                'rows.0.enrolled' => 'required',
                'rows.0.dropped' => 'required',
                'rows.0.capacity' => 'required',
            ]);
    }
}
