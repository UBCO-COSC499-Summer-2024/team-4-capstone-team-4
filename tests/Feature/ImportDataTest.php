<?php

namespace Tests\Feature;

use App\Livewire\ImportTabs;
use App\Models\Area;
use App\Models\CourseSection;
use App\Models\Department;
use App\Models\SeiData;
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

    public function test_workday_form_renders_correctly(): void
    {
        // Create a user and assign a role
        $user = User::factory()->create();
        UserRole::factory()->create(['user_id' => $user->id, 'role' => 'dept_head']);
        $this->actingAs($user);

        // Test the form rendering
        Livewire::test('import-workday-form')
            ->assertSee('Number')
            ->assertSee('Section')
            ->assertSee('Area')
            ->assertSee('Session')
            ->assertSee('Term')
            ->assertSee('Year')
            ->assertSee('Enrolled')
            ->assertSee('Dropped')
            ->assertSee('Capacity');
    }

    public function test_valid_data_is_inserted_workday(): void
    {

        $this->seed();
        // Create a user and assign a role
        $user = User::factory()->create();
        UserRole::factory()->create(['user_id' => $user->id, 'role' => 'dept_head']);
        $this->actingAs($user);

        $dept = Department::factory()->create([
            'name' => 'CMPS',
        ]);

        // Create an area
        $area = Area::factory()->create([
            'name' => 'Computer Science',
            'dept_id' => $dept->id,
        ]);

        // Test the form submission
        Livewire::test('import-workday-form')
            ->set('rows.0.number', '123')
            ->set('rows.0.section', '001')
            ->set('rows.0.area_id', $area->id)
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
            'number' => '123',
            'section' => '001',
            'area_id' => $area->id,
            'session' => 'W',
            'term' => '1',
            'year' => 2024,
            'enrolled' => 30,
            'dropped' => 5,
            'capacity' => 40,
        ]);
    }

    public function test_validation_errors_are_handled_workday(): void
    {
        // Create a user and assign a role
        $user = User::factory()->create();
        UserRole::factory()->create(['user_id' => $user->id, 'role' => 'dept_head']);
        $this->actingAs($user);

        // Test the form submission with invalid data
        Livewire::test('import-workday-form')
            ->set('rows.0.number', '')
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
                'rows.0.number' => 'required',
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

    public function test_sei_form_renders_correctly(): void
    {
        // Create a user and assign a role
        $user = User::factory()->create();
        UserRole::factory()->create(['user_id' => $user->id, 'role' => 'dept_head']);
        $this->actingAs($user);

        $dept = Department::factory()->create([
            'name' => 'CMPS',
        ]);

        // Create an area
        $area = Area::factory()->create([
            'name' => 'Computer Science',
            'dept_id' => $dept->id,
        ]);

        // Create a course section
        $course = CourseSection::factory()->create([
            'prefix' => 'COSC',
            'number' => '123',
            'area_id' => $area->id,
            'year' => 2010,
            'enrolled' => 50,
            'dropped' => 5,
            'capacity' => 100,
            'term' => '2',
            'session' => 'W',
            'section' => '001',
        ]);

        // Test the form rendering
        Livewire::test('import-sei-form')
            ->assertSee('cid')
            ->assertSee('q1')
            ->assertSee('q2')
            ->assertSee('q3')
            ->assertSee('q4')
            ->assertSee('q5')
            ->assertSee('q6');
    }


    public function test_valid_data_is_inserted_sei_data(): void
    {
        $this->seed();

        // Create a user and assign a role
        $user = User::factory()->create();
        UserRole::factory()->create(['user_id' => $user->id, 'role' => 'dept_head']);
        $this->actingAs($user);

        // Create a department
        $dept = Department::factory()->create([
            'name' => 'CMPS',
        ]);

        // Create an area
        $area = Area::factory()->create([
            'name' => 'Computer Science',
            'dept_id' => $dept->id,
        ]);

        // Create a course section
        $course = CourseSection::factory()->create([
            'prefix' => 'COSC',
            'number' => '123',
            'area_id' => $area->id,
            'year' => 2010,
            'enrolled' => 50,
            'dropped' => 5,
            'capacity' => 100,
            'term' => '2',
            'session' => 'W',
            'section' => '001',
        ]);

        // Submit the form
        Livewire::test('import-sei-form')
            ->set('rows.0.cid', $course->id)
            ->set('rows.0.q1', 4.5)
            ->set('rows.0.q2', 4.0)
            ->set('rows.0.q3', 3.5)
            ->set('rows.0.q4', 4.0)
            ->set('rows.0.q5', 4.2)
            ->set('rows.0.q6', 4.3)
            ->call('handleSubmit')
            ->assertHasNoErrors();

        // Assert that the non-JSON fields are correctly inserted
        $this->assertDatabaseHas('sei_data', [
            'course_section_id' => $course->id,
        ]);

        // Fetch the inserted record and decode the JSON field
        $seiData = SeiData::where('course_section_id', $course->id)->first();
        $questions = json_decode($seiData->questions, true);

        // Assert the JSON data matches the expected values
        $this->assertEquals([
            'q1' => 4.5,
            'q2' => 4.0,
            'q3' => 3.5,
            'q4' => 4.0,
            'q5' => 4.2,
            'q6' => 4.3,
        ], $questions);
    }

    public function test_validation_errors_are_handled_sei(): void
    {

        $user = User::factory()->create();
        UserRole::factory()->create(['user_id' => $user->id, 'role' => 'dept_head']);
        $this->actingAs($user);

        Livewire::test('import-sei-form')
            ->set('rows.0.cid', '')
            ->set('rows.0.q1', '')
            ->set('rows.0.q2', '')
            ->set('rows.0.q3', '')
            ->set('rows.0.q4', '')
            ->set('rows.0.q5', '')
            ->set('rows.0.q6', '')
            ->call('handleSubmit')
            ->assertHasErrors([
                'rows.0.cid' => 'required',
                'rows.0.q1' => 'required',
                'rows.0.q2' => 'required',
                'rows.0.q3' => 'required',
                'rows.0.q4' => 'required',
                'rows.0.q5' => 'required',
                'rows.0.q6' => 'required',
            ]);
    }
}
