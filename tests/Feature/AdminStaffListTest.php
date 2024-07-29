<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Area;
use App\Models\Department;
use App\Models\InstructorPerformance;
use App\Models\UserRole;
use App\Models\CourseSection;
use App\Models\Teach;
use Livewire\Livewire;
use App\Livewire\StaffList;
use Illuminate\Support\Facades\Hash;

class AdminStaffListTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_staff_page_can_be_rendered(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/staff');

        $response->assertStatus(200);
    }

    public function test_admin_staff_page_shows_a_list_of_instructors(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'admin',
        ]);

        $user1 =  User::factory()->create([
            'firstname' => 'Adam',
            'lastname' => 'Smith',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        $instructor= UserRole::factory()->create([
                'user_id' => $user1->id,
                'department_id' => null,
                'role' => 'instructor',
            ]);

        $user1 =  User::factory()->create([
            'firstname' => 'Test2',
            'lastname' => 'User2',
            'email' => 'test2@example.com',
            'password' => 'password'
        ]);
        $head = UserRole::factory()->create([
                'user_id' => $user1->id,
                'department_id' => $dept->id,
                'role' => 'dept_head',
            ]);

        $this->actingAs($user);

        Livewire::test(StaffList::class)
        ->assertSee($user1->firstname)
        ->assertSee($user1->lastname)
        ->assertSee($user1->firstname)
        ->assertSee($user1->lastname)
        ->assertSee('Instructor')
        ->assertSee($head->role);

    }

    public function test_can_search_for_user():void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'admin',
        ]);
        
        $user1 =  User::factory()->create([
            'firstname' => 'Dr',
            'lastname' => 'Prof',
            'email' => 'instructor@example.com',
            'password' => 'password'
        ]);
        $instructor= UserRole::factory()->create([
                'user_id' => $user1->id,
                'department_id' => null,
                'role' => 'instructor',
            ]);

        $user2 =  User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'head@example.com',
            'password' => 'password'
        ]);
        $head = UserRole::factory()->create([
                'user_id' => $user2->id,
                'department_id' => $dept->id,
                'role' => 'dept_head',
            ]);

        $this->actingAs($user);

        Livewire::test(StaffList::class)
        ->set('searchTerm', 'Dr Prof')
        ->assertSee($user1->firstname)
        ->assertSee($user1->lastname)
        ->assertDontSee($user2->firstname)
        ->assertDontSee($user2->lastname);
    }

    public function test_can_filter_by_department():void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $dept2 = Department::factory()->create(['name' => 'Management']);
        $area1 = Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $area2 = Area::factory()->create(['name' => 'Finance','dept_id' => $dept2->id]);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'admin',
        ]);
        $user1 =  User::factory()->create([
            'firstname' => 'Adam',
            'lastname' => 'Smith',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        $role1 = UserRole::factory()->create([
                'user_id' => $user1->id,
                'department_id' => null,
                'role' => 'instructor',
            ]);

        $user2 =  User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password'
        ]);
        $role2 = UserRole::factory()->create([
                'user_id' => $user2->id,
                'department_id' => $dept2->id,
                'role' => 'dept_head',
            ]);

        $course1 = CourseSection::factory()->create([
            'area_id'=>$area1->id
        ]);
        Teach::factory()->create([
            'course_section_id'=> $course1->id,
            'instructor_id' => $role1->id,
        ]);

        $this->actingAs($user);

        Livewire::test(StaffList::class)
        ->set('selectedDepts', ['CMPS'])
        ->call('filter')
        ->assertSee($user1->firstname)
        ->assertSee($user1->lastname)
        ->assertSeeHtml('<div class="flex items-center justify-center h-full">CMPS</div>')
        ->assertDontSee($user2->firstname)
        ->assertDontSee($user2->lastname)
        ->assertDontSeeHtml('<div class="flex items-center justify-center h-full">Management</div>');

        Livewire::test(StaffList::class)
        ->set('selectedDepts', ['Management'])
        ->call('filter')
        ->assertSee($user2->firstname)
        ->assertSee($user2->lastname)
        ->assertSeeHtml('<div class="flex items-center justify-center h-full">Management</div>')
        ->assertDontSee($user1->firstname)
        ->assertDontSee($user1->lastname)
        ->assertDontSeeHtml('<div class="flex items-center justify-center h-full">CMPS</div>');
    }

    public function test_can_filter_by_roles():void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $area1 = Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'admin',
        ]);
        $user1 =  User::factory()->create([
            'firstname' => 'Adam',
            'lastname' => 'Smith',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        $role1 = UserRole::factory()->create([
                'user_id' => $user1->id,
                'department_id' => null,
                'role' => 'instructor',
            ]);

        $user2 =  User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password'
        ]);
        $role2 = UserRole::factory()->create([
                'user_id' => $user2->id,
                'department_id' => $dept->id,
                'role' => 'dept_head',
            ]);

        $course1 = CourseSection::factory()->create([
            'area_id'=>$area1->id
        ]);
        Teach::factory()->create([
            'course_section_id'=> $course1->id,
            'instructor_id' => $role1->id,
        ]);

        $this->actingAs($user);

        Livewire::test(StaffList::class)
        ->set('selectedRoles', ['instructor'])
        ->call('filter')
        ->assertSee($user1->firstname)
        ->assertSee($user1->lastname)
        ->assertSeeHtml('<div class="flex items-center justify-center h-full">Instructor</div>')
        ->assertDontSee($user2->firstname)
        ->assertDontSee($user2->lastname)
        ->assertDontSeeHtml('<div class="flex items-center justify-center h-full">Department Head</div>');

        Livewire::test(StaffList::class)
        ->set('selectedRoles', ['dept_head'])
        ->call('filter')
        ->assertSee($user2->firstname)
        ->assertSee($user2->lastname)
        ->assertSeeHtml('<div class="flex items-center justify-center h-full">Department Head</div>')
        ->assertDontSee($user1->firstname)
        ->assertDontSee($user1->lastname)
        ->assertDontSeeHtml('<div class="flex items-center justify-center h-full">Instructor</div>');
    }

    public function test_can_filter_by_status():void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $area1 = Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'admin',
        ]);
        $user1 =  User::factory()->create([
            'firstname' => 'Adam',
            'lastname' => 'Smith',
            'email' => 'test@example.com',
            'password' => 'password',
            'active' => true
        ]);
        $role1 = UserRole::factory()->create([
                'user_id' => $user1->id,
                'department_id' => null,
                'role' => 'instructor',
            ]);

        $user2 =  User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'active' => false
        ]);
        $role2 = UserRole::factory()->create([
                'user_id' => $user2->id,
                'department_id' => $dept->id,
                'role' => 'dept_head',
            ]);

        $course1 = CourseSection::factory()->create([
            'area_id'=>$area1->id
        ]);
        Teach::factory()->create([
            'course_section_id'=> $course1->id,
            'instructor_id' => $role1->id,
        ]);

        $this->actingAs($user);

        Livewire::test(StaffList::class)
        ->set('selectedStatus', ['true'])
        ->call('filter')
        ->assertSee($user1->firstname)
        ->assertSee($user1->lastname)
        ->assertSeeHtml('<div class="flex items-center justify-center h-full">Instructor</div>')
        ->assertDontSee($user2->firstname)
        ->assertDontSee($user2->lastname)
        ->assertDontSeeHtml('<div class="flex items-center justify-center h-full">Department Head</div>');

        Livewire::test(StaffList::class)
        ->set('selectedStatus', ['false'])
        ->call('filter')
        ->assertSee($user2->firstname)
        ->assertSee($user2->lastname)
        ->assertSeeHtml('<div class="flex items-center justify-center h-full">Department Head</div>')
        ->assertDontSee($user1->firstname)
        ->assertDontSee($user1->lastname)
        ->assertDontSeeHtml('<div class="flex items-center justify-center h-full">Instructor</div>');
    }

    public function test_admin_can_sort_staff_list(): void{
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $dept2 = Department::factory()->create(['name' => 'Management']);
        $area1 = Area::factory()->create(['name' => 'Computer Science','dept_id' => $dept->id]);
        $area2 = Area::factory()->create(['name' => 'Finance','dept_id' => $dept2->id]);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'admin',
        ]);
        $user1 =  User::factory()->create([
            'firstname' => 'Tom',
            'lastname' => 'Miller',
            'email' => 'test@example.com',
            'password' => 'password',
            'active' => false,
        ]);
        $role1 = UserRole::factory()->create([
                'user_id' => $user1->id,
                'department_id' => null,
                'role' => 'instructor',
            ]);

        $user2 =  User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'active' => true,
        ]);
        $role2 = UserRole::factory()->create([
                'user_id' => $user2->id,
                'department_id' => $dept2->id,
                'role' => 'dept_head',
            ]);

        $course1 = CourseSection::factory()->create([
            'area_id'=>$area1->id
        ]);
        Teach::factory()->create([
            'course_section_id'=> $course1->id,
            'instructor_id' => $role1->id,
        ]);

        $this->actingAs($user);
    
        // Initialize Livewire test instance
        $component = Livewire::test(StaffList::class);
    
        // Sorting by first name
        $component->set('sortDirection', 'asc')
            ->set('sortField', 'firstname')
            ->assertSeeInOrder([$user2->firstname, $user1->firstname]);
    
        $component->set('sortDirection', 'desc')
            ->set('sortField', 'firstname')
            ->assertSeeInOrder([$user1->firstname, $user2->firstname]);
    
        // Sorting by department
        $component->set('sortDirection', 'asc')
            ->set('sortField', 'dept')
            ->assertSeeInOrder([$user1->firstname, $user2->firstname]);
    
        $component->set('sortDirection', 'desc')
            ->set('sortField', 'dept')
            ->assertSeeInOrder([$user2->firstname, $user1->firstname]);
    
        // Sorting by role
        $component->set('sortDirection', 'asc')
            ->set('sortField', 'role')
            ->assertSeeInOrder([$user2->firstname, $user1->firstname]);
    
        $component->set('sortDirection', 'desc')
            ->set('sortField', 'role')
            ->assertSeeInOrder([$user1->firstname, $user2->firstname]);
    
        // Sorting by status
        $component->set('sortDirection', 'asc')
            ->set('sortField', 'active')
            ->assertSeeInOrder([$user1->firstname, $user2->firstname]);
    
        $component->set('sortDirection', 'desc')
            ->set('sortField', 'active')
            ->assertSeeInOrder([$user2->firstname, $user1->firstname]);
    
    }

    public function test_admin_can_add_new_user(){
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        Livewire::test(StaffList::class)
            ->set('firstname', 'John')
            ->set('lastname', 'Doe')
            ->set('email', 'johndoe@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('user_roles', ['instructor'])
            ->call('addUser')
            ->assertSee('John Doe');

        // Check if the user is added to the users table
        $this->assertDatabaseHas('users', [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
        ]);

        // Verify the password
        $user = User::where('email', 'johndoe@example.com')->first();
        $this->assertTrue(Hash::check('password', $user->password));

        // Check if the user role is added to the user_roles table
        $this->assertDatabaseHas('user_roles', [
            'user_id' => $user->id,
            'department_id' => null,
            'role' => 'instructor',
        ]);
    }

    public function test_admin_can_edit_single_user(){
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $user1 =  User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'active' => true
        ]);
        $role = UserRole::factory()->create([
                'user_id' => $user1->id,
                'department_id' => null,
                'role' => 'instructor',
            ]);

        //disable the user and remove instructor role   
        Livewire::test(StaffList::class)
        ->set('enabledUsers', [])
        ->set('instructors', [])
        ->call('editStaff', $user1->id);

        // Check if the user is disbaled
        $this->assertDatabaseHas('users', [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'active' => false
        ]);

        // Check if the user role is removed
        $this->assertDatabaseMissing('user_roles', [
            'user_id' => $user1->id,
            'department_id' => null,
            'role' => 'instructor',
        ]);
    } 
    
    public function test_admin_can_delete_single_user(){
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $user1 =  User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'active' => true
        ]);
        $role = UserRole::factory()->create([
                'user_id' => $user1->id,
                'department_id' => null,
                'role' => 'instructor',
            ]);

        //delete the user  
        Livewire::test(StaffList::class)
        ->call('deleteStaff', $user1->id);

        // Check if the user is removed
        $this->assertDatabaseMissing('users', [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'active' => true
        ]);

        // Check if the user role is removed
        $this->assertDatabaseMissing('user_roles', [
            'user_id' => $user1->id,
            'department_id' => null,
            'role' => 'instructor',
        ]);
    } 

    public function test_admin_can_bulk_edit(){
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $user1 =  User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'active' => true
        ]);
        $role1 = UserRole::factory()->create([
                'user_id' => $user1->id,
                'department_id' => null,
                'role' => 'instructor',
            ]);


        $user2 =  User::factory()->create([
            'firstname' => 'Adam',
            'lastname' => 'Smith',
            'email' => 'adamsmith@example.com',
            'password' => 'password',
            'active' => false
        ]);
        $role3 = UserRole::factory()->create([
                'user_id' => $user2->id,
                'department_id' => null,
                'role' => 'dept_head',
            ]);

        //set up  
        Livewire::test(StaffList::class)
        ->set('prevEnabledUsers', [$user1->id])
        ->set('prevInstructors', [$user1->id])
        ->set('prevDeptHeads', [$user2->id])
        ->set('enabledUsers', [$user2->id])
        ->set('instructors', [])
        ->set('deptHeads', [$user1->id, $user2->id])
        ->call('edit');

        // Check if the user1 is disabled
        $this->assertDatabaseHas('users', [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'active' => false
        ]);

        // Check if the user2 is enabled
        $this->assertDatabaseHas('users', [
            'firstname' => 'Adam',
            'lastname' => 'Smith',
            'email' => 'adamsmith@example.com',
            'active' => true
        ]);

        // Check if the user1 is no longer instructor
        $this->assertDatabaseMissing('user_roles', [
            'user_id' => $user1->id,
            'department_id' => null,
            'role' => 'instructor',
        ]);

        // Check if the user1 is dept head
        $this->assertDatabaseHas('user_roles', [
            'user_id' => $user1->id,
            'department_id' => null,
            'role' => 'dept_head',
        ]);
    } 

    public function test_admin_can_bulk_delete(){
        $dept = Department::factory()->create(['name' => 'CMPS']);
        $user = User::factory()->create();
        UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $user1 =  User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'active' => true
        ]);
        $role1 = UserRole::factory()->create([
                'user_id' => $user1->id,
                'department_id' => null,
                'role' => 'instructor',
            ]);


        $user2 =  User::factory()->create([
            'firstname' => 'Adam',
            'lastname' => 'Smith',
            'email' => 'adamsmith@example.com',
            'password' => 'password',
            'active' => false
        ]);
        $role3 = UserRole::factory()->create([
                'user_id' => $user2->id,
                'department_id' => null,
                'role' => 'dept_head',
            ]);

        //set up  
        Livewire::test(StaffList::class)
        ->set('staffCheckboxes', [$user1->email, $user2->email])
        ->call('delete');

         // Check if the users are removed
         $this->assertDatabaseMissing('users', [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'active' => true
        ]);
        $this->assertDatabaseMissing('users', [
            'firstname' => 'Adam',
            'lastname' => 'Smith',
            'email' => 'adamsmith@example.com',
            'active' => true
        ]);

    } 

}
