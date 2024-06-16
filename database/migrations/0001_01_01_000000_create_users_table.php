<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('acces_code')->nullable();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('dept_id')->constrained('departments')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('area_id')->nullable()->constrained('areas')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->cascadeOnDelete();
            $table->enum('role', ['instructor', 'dept_head', 'dept_staff', 'admin']);
            $table->timestamps();
        });
    
        Schema::create('extra_hours', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->integer('hours');
            $table->foreignId('assigner_id')->constrained('user_roles')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('user_roles')->cascadeOnDelete();
            $table->timestamps();
        });
    
        Schema::create('service_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->year('year');
            $table->json('monthly_hours');
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->timestamps();
        });
    
        Schema::create('role_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_role_id')->constrained('service_roles')->cascadeOnDelete();
            $table->foreignId('assigner_id')->constrained('user_roles')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('user_roles')->cascadeOnDelete();
            $table->timestamps();
        });
    
        Schema::create('course_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->string('duration');
            $table->integer('enrolled');
            $table->integer('dropped');
            $table->integer('capacity');
            $table->timestamps();
        });
    
        Schema::create('sei_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained('course_sections')->cascadeOnDelete();
            $table->json('questions');
            $table->timestamps();
        });

        Schema::create('teaches', function (Blueprint $table) {
            $table->foreignId('course_section_id')->constrained('course_sections')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('user_roles')->cascadeOnDelete();
            $table->timestamps();
        });
    
        Schema::create('teaching_assistants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('rating');
            $table->timestamps();
        });
    
        Schema::create('assists', function (Blueprint $table) {
            $table->foreignId('course_section_id')->constrained('course_sections')->cascadeOnDelete();
            $table->foreignId('ta_id')->constrained('teaching_assistants')->cascadeOnDelete();
            $table->float('rating');
            $table->timestamps();
        });
    
        Schema::create('instructor_performance', function (Blueprint $table) {
            $table->id();
            $table->integer('score');
            $table->integer('total_hours');
            $table->integer('target_hours');
            $table->float('sei_avg');
            $table->year('year');
            $table->foreignId('instructor_id')->constrained('user_roles')->cascadeOnDelete();
            $table->timestamps();
        });
    
        Schema::create('area_performance', function (Blueprint $table) {
            $table->id();
            $table->integer('score');
            $table->integer('total_hours');
            $table->integer('target_hours');
            $table->float('sei_avg');
            $table->year('year');
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->timestamps();
        });
    
        Schema::create('department_performance', function (Blueprint $table) {
            $table->id();
            $table->integer('score');
            $table->integer('total_hours');
            $table->integer('target_hours');
            $table->float('sei_avg');
            $table->year('year');
            $table->foreignId('dept_id')->constrained('departments')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('extra_hours');
        Schema::dropIfExists('service_roles');
        Schema::dropIfExists('role_assignments');
        Schema::dropIfExists('course_sections');
        Schema::dropIfExists('sei_data');
        Schema::dropIfExists('teaches');
        Schema::dropIfExists('teaching_assistants');
        Schema::dropIfExists('assists');
        Schema::dropIfExists('instructor_performance');
        Schema::dropIfExists('area_performance');
        Schema::dropIfExists('department_performance');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
