<?php

use App\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffEditModeController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseDetailsController;
use App\Http\Middleware\CheckRole;

// Auth routes
Route::get('auth/{provider}', [AuthController::class, 'redirectToProvider'])->name('auth.provider');
Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('auth.provider.callback');

// Routes for authenticated and verified users
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', [ChartController::class, 'showChart'])->name('main');
<<<<<<< HEAD
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckRole::class.':admin,dept_head,dept_staff'
])->group(function () {
    Route::get('/staff', function(){
        return view('staff');
    })->name('staff');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckRole::class.':admin,dept_head,dept_staff'
])->group(function () {
    Route::get('/staff/edit', function(){
        return view('staff-edit-mode');
    })->name('staff.edit');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
=======
    Route::get('/dashboard', [ChartController::class, 'showChart'])->name('dashboard');
>>>>>>> aa212c44c23fcfeca0950bb775186ed014c42ce5
    Route::get('/notifications', function () {
        return view('notifications');
    })->name('notifications');
    Route::get('/help', function () {
        return view('help');
    })->name('help');
    Route::get('/performance', function () {
        return view('performance');
    })->name('performance');
<<<<<<< HEAD
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckRole::class.':admin,dept_head,dept_staff'
])->group(function () {
    Route::get('course-details/user_id', [CourseDetailsController::class, 'show'])->where('user_id', '[0-9]+')->name('course-details')->middleware(CheckRole::class.':admin,dept_head,instructor');
    Route::post('course-details/save', [CourseDetailsController::class, 'save'])->name('course-details.save');
    Route::post('assign-course', [CourseDetailsController::class, 'assignCourse'])->name('assign-course');
    Route::get('course-details/search', [CourseDetailsController::class, 'search'])->name('course-details.search');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
=======
    Route::get('/courses', function () {
        return view('courses');
    })->name('courses');
>>>>>>> aa212c44c23fcfeca0950bb775186ed014c42ce5
    Route::get('/svcroles', function () {
        return view('svcroles');
    })->name('svcroles');
    Route::get('/assign-courses', function () {
        return view('assign-courses');
    })->name('assign-courses');
    Route::get('/privacy-policy', function () {
        return view('policy');
    })->name('privacy-policy');
    Route::get('/tos', function () {
        return view('terms');
    })->name('tos');
});

<<<<<<< HEAD
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('/svcroles')->group(function () {
    // Add-Svcrole (for testing purposes, will be changed to modal later)
    Route::get('/add', function () {
        return view('svcroles');
    })->name('svcroles.add');

    // Manage-Svcroles
    Route::get('/manage', function () {
        return view('svcroles');
    })->name('svcroles.manage');

    // manage/id
    Route::get('/manage/{id}', function () {
        return view('svcroles');
    })->name('svcroles.manage.id');

    // Requests
    Route::get('/requests', function () {
        return view('svcroles');
    })->name('svcroles.requests');

    // Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('svcroles.logs');
})->group(function(){
    return view('svcroles');
    })->name('svcroles.audit-logs');

Route::get('/privacy-policy', function () {
    return view('policy');
})->name('privacy-policy');

Route::get('/tos', function () {
    return view('terms');
})->name('tos');

=======
// Routes for specific roles
>>>>>>> aa212c44c23fcfeca0950bb775186ed014c42ce5
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckRole::class.':admin,dept_head,dept_staff'
])->group(function () {
    Route::get('/staff', function() {
        return view('staff');
    })->name('staff');
    Route::get('/staff/edit', function() {
        return view('staff-edit-mode');
    })->name('staff.edit');
    Route::get('/leaderboard', function () {
        return view('leaderboard');
    })->name('leaderboard');
    Route::get('/import', function () {
        return view('import');
    })->name('import');
});

// Svcroles routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('/svcroles')->group(function () {
    Route::get('/add', function () {
        return view('svcroles');
    })->name('svcroles.add');
    Route::get('/manage', function () {
        return view('svcroles');
    })->name('svcroles.manage');
    Route::get('/manage/{id}', function () {
        return view('svcroles');
    })->name('svcroles.manage.id');
    Route::get('/requests', function () {
        return view('svcroles');
    })->name('svcroles.requests');
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('svcroles.logs');
    Route::get('/course-details', [CourseDetailsController::class, 'show'])->name('course-details');
    Route::post('/course-details/save', [CourseDetailsController::class, 'save'])->name('course-details.save');
    Route::post('/assign-course', [CourseDetailsController::class, 'assignCourse'])->name('assign-course');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/instructor-report/{instructor_id}', function ($instructor_id) {
        return view('instructor-report', ['instructor_id' => $instructor_id]);
    })->name('instructor-report');
});

