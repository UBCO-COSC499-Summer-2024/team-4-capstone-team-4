<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->boolean('approved')->default(false);
            $table->rememberToken();
            $table->boolean('active')->default(true);
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('archived')->default(false);
            $table->timestamps();
        });

        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('dept_id')->constrained('departments')->cascadeOnDelete();
            $table->boolean('archived')->default(false);
            $table->timestamps();
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->cascadeOnDelete();
            $table->enum('role', ['instructor', 'dept_head', 'dept_staff', 'admin']);
            $table->timestamps();
        });

        Schema::create('extra_hours', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->integer('hours');
            $table->year('year');
            $table->integer('month');
            $table->string('room')->nullable();
            $table->foreignId('assigner_id')->constrained('user_roles')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('user_roles')->cascadeOnDelete();
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->boolean('archived')->default(false);
            $table->timestamps();
        });

        Schema::create('service_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable()->default('Default Description');
            $table->year('year')->default(date('Y'));
            $table->json('monthly_hours');
            $table->string('room')->nullable();
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->timestamps();
            $table->boolean('archived')->default(false);
            $table->unique(['name', 'area_id']);
        });

        Schema::create('role_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_role_id')->constrained('service_roles')->cascadeOnDelete();
            $table->foreignId('assigner_id')->constrained('user_roles')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('user_roles')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['service_role_id', 'instructor_id']);
        });

        Schema::create('course_sections', function (Blueprint $table) {
            $table->id();
            $table->string('prefix');
            $table->string('number');
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->integer('enroll_start');
            $table->integer('enroll_end');
            $table->integer('dropped');
            $table->integer('capacity');
            $table->year('year');
            $table->string('term');
            $table->string('session');
            $table->string('section');
            $table->string('room');
            $table->string('time_start');
            $table->string('time_end');
            $table->boolean('archived')->default(false);
            $table->timestamps();
        });

        Schema::create('sei_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained('course_sections')->cascadeOnDelete();
            $table->json('questions');
            $table->boolean('archived')->default(false);
            $table->timestamps();
        });

        Schema::create('teaches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained('course_sections')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('user_roles')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('teaching_assistants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('rating')->nullable();
            $table->timestamps();
        });

        Schema::create('assists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained('course_sections')->cascadeOnDelete();
            $table->foreignId('ta_id')->constrained('teaching_assistants')->cascadeOnDelete();
            $table->float('rating')->nullable();
            $table->timestamps();
        });

        Schema::create('instructor_performance', function (Blueprint $table) {
            $table->id();
            $table->integer('score');
            $table->json('total_hours');
            $table->integer('target_hours')->nullable();
            $table->float('sei_avg');
            $table->float('enrolled_avg');
            $table->float('dropped_avg');
            $table->year('year');
            $table->foreignId('instructor_id')->constrained('user_roles')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('area_performance', function (Blueprint $table) {
            $table->id();
            $table->json('total_hours');
            $table->float('sei_avg');
            $table->float('enrolled_avg');
            $table->float('dropped_avg');
            $table->year('year');
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('department_performance', function (Blueprint $table) {
            $table->id();
            $table->json('total_hours');
            $table->float('sei_avg');
            $table->float('enrolled_avg');
            $table->float('dropped_avg');
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

        Schema::create('auth_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('provider');
            $table->string('provider_id');
            $table->string('token')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->primary()->constrained('users')->cascadeOnDelete();
            $table->foreignId('auth_method_id')->nullable()->constrained('auth_methods')->cascadeOnDelete();
            $table->string('theme')->nullable()->default('light');
            $table->string('timezone')->nullable()->default(date_default_timezone_get());
            $table->string('locale')->nullable()->default('en');
            $table->string('language')->nullable()->default('en');
            $table->jsonb('custom')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('user_alt')->nullable();
            $table->string('action')->nullable();
            $table->text('description')->nullable();
            $table->string('table_name')->nullable();
            $table->string('operation_type')->nullable();
            $table->jsonb('old_value')->nullable();
            $table->jsonb('new_value')->nullable();
            $table->timestamp('timestamp')->useCurrent();
            $table->timestamps();
        });

        Schema::create('approval_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('approvals_required')->default(1);
            $table->timestamps();
        });

        Schema::create('approval_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approval_type_id')->constrained('approval_types')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('status_id')->constrained('approval_statuses')->cascadeOnDelete();
            $table->text('details')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('user_roles')->cascadeOnDelete();
            $table->foreignId('rejected_by')->nullable()->constrained('user_roles')->cascadeOnDelete();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('approval_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approval_id')->constrained('approvals')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('status_id')->constrained('approval_statuses')->cascadeOnDelete();
            $table->text('remarks')->nullable();
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();
        });

        // for pgaudit
        Schema::create('super_audits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('audit_user')->nullable();
            $table->text('application_name')->nullable();
            $table->string('client_addr', 45)->nullable();
            $table->integer('client_port')->nullable();
            $table->timestamp('occurred_at', 6)->useCurrent();
            $table->text('statement_tag')->nullable();
            $table->text('transaction_id')->nullable();
            $table->text('query')->nullable();
            $table->jsonb('params')->nullable();
            $table->text('session_id')->nullable();
            $table->integer('pid')->nullable();
            $table->text('user_query')->nullable();
            $table->text('schema_name')->nullable();
            $table->text('relation_name')->nullable();
            $table->string('object_type')->nullable();
            $table->string('command_tag')->nullable();
            $table->integer('return_rows')->nullable();
            $table->string('session_user')->nullable();
            $table->text('security_label')->nullable();
            $table->jsonb('context')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('role_assignments');
        Schema::dropIfExists('extra_hours');
        Schema::dropIfExists('service_roles');
        Schema::dropIfExists('assists');
        Schema::dropIfExists('teaches');
        Schema::dropIfExists('sei_data');
        Schema::dropIfExists('teaching_assistants');
        Schema::dropIfExists('course_sections');
        Schema::dropIfExists('instructor_performance');
        Schema::dropIfExists('area_performance');
        Schema::dropIfExists('department_performance');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('auth_methods');
        Schema::dropIfExists('super_audits');
        Schema::dropIfExists('approval_histories');
        Schema::dropIfExists('approvals');
        Schema::dropIfExists('approval_statuses');
        Schema::dropIfExists('approval_types');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('users');
    }
};
