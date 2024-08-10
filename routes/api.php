<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceRoleController;
use App\Models\CourseSection;
use App\Models\User;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/service-roles', [ServiceRoleController::class, 'index'])->name('api.service-roles');
Route::get('/instructors', function () {
    // Fetch all instructors
    return User::all(['id', 'firstname', 'lastname']);
});

Route::get('/user/{id}/courses', function ($id) {
    // Fetch courses for a specific instructor
    return CourseSection::all();
});
