<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\CourseSection;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->get('/{inst', function () {
        return view('dashboard');
    });

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');