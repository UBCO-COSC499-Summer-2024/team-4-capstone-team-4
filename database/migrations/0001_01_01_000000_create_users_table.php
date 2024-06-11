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
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });

        Schema::create('isinstructor', function (Blueprint $table) {
            $table->foreignId('uid');
            $table->foreignId('did');
        });

        Schema::create('isdepthead', function (Blueprint $table) {
            $table->foreignId('uid');
            $table->foreignId('did');
        });

        Schema::create('isdeptstaff', function (Blueprint $table) {
            $table->foreignId('uid');
            $table->foreignId('did');
        });

        Schema::create('isadmin', function (Blueprint $table) {
            $table->foreignId('uid');
            $table->foreignId('did');
        });

        Schema::create('dept', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('area', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('did');
        });

        Schema::create('extrahours', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('desc');
            $table->integer('hours');
            $table->foreignId('assigner');
            $table->foreignId('iid');
        });

        Schema::create('servicerole', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('desc');
            $table->date('year');
            $table->integer('janhrs');
            $table->integer('febhrs');
            $table->integer('marhrs');
            $table->integer('aprhrs');
            $table->integer('mayhrs');
            $table->integer('junhrs');
            $table->integer('julhrs');
            $table->integer('aughrs');
            $table->integer('sephrs');
            $table->integer('octhrs');
            $table->integer('novnhrs');
            $table->integer('dechrs');
            $table->foreignId('aid');
        });

        Schema::create('hasrole', function (Blueprint $table) {
            $table->foreignId('rid');
            $table->foreignId('assigner');
            $table->foreignId('iid');
        });

        Schema::create('teaches', function (Blueprint $table) {
            $table->foreignId('cid');
            $table->foreignId('iid');
        });

        Schema::create('coursesection', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('area');
            $table->string('duration');
            $table->integer('enrolled');
            $table->integer('dropped');
            $table->integer('capacity');
        });

        Schema::create('seidata', function (Blueprint $table) {
            $table->foreignId('cid')->unique();
            $table->float('q1im');
            $table->float('q2im');
            $table->float('q3im');
            $table->float('q4im');
            $table->float('q5im');
            $table->float('q6im');
        });

        Schema::create('teachingassistant', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('rating');
        });

        Schema::create('assists', function (Blueprint $table) {
            $table->foreignId('cid');
            $table->foreignId('taid');
            $table->float('rating');
        });

        Schema::create('instructorperformance', function (Blueprint $table) {
            $table->id();
            $table->integer('score');
            $table->integer('totalhours');
            $table->float('seiavg');
            $table->date('year');
            $table->foreignId('iid');
        });

        Schema::create('areaperformance', function (Blueprint $table) {
            $table->id();
            $table->integer('score');
            $table->integer('totalhours');
            $table->float('seiavg');
            $table->date('year');
            $table->foreignId('aid');
        });

        Schema::create('deptperformance', function (Blueprint $table) {
            $table->id();
            $table->integer('score');
            $table->integer('totalhours');
            $table->float('seiavg');
            $table->date('year');
            $table->foreignId('did');
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
        Schema::dropIfExists('isinstructor');
        Schema::dropIfExists('isdepthead');
        Schema::dropIfExists('isdeptstaff');
        Schema::dropIfExists('isadmin');
        Schema::dropIfExists('dept');
        Schema::dropIfExists('area');
        Schema::dropIfExists('extrahours');
        Schema::dropIfExists('servicerole');
        Schema::dropIfExists('hasrole');
        Schema::dropIfExists('teaches');
        Schema::dropIfExists('coursesection');
        Schema::dropIfExists('seidata');
        Schema::dropIfExists('teachingassistant');
        Schema::dropIfExists('assists');
        Schema::dropIfExists('instructorperformance');
        Schema::dropIfExists('areaperformance');
        Schema::dropIfExists('deptperformance');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
