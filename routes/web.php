<?php

use App\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffEditModeController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseDetailsController;
use App\Http\Controllers\UploadFileController;
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
    Route::get('/dashboard', [ChartController::class, 'showChart'])->name('dashboard');
    Route::get('/notifications', function () {
        return view('notifications');
    })->name('notifications');
    Route::get('/help', function () {
        return view('help');
    })->name('help');
    Route::get('/performance', function () {
        return view('performance');
    })->name('performance');
    Route::get('/courses', function () {
        return view('courses');
    })->name('courses');
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

// Routes for specific roles
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckRole::class.':admin,dept_head,dept_staff'
])->group(function () {
    Route::get('/staff', function() {
        return view('staff');
    })->name('staff');
    Route::get('/leaderboard', function () {
        return view('leaderboard');
    })->name('leaderboard');
    Route::get('/import', function () {
        return view('import');
    })->name('import');
    Route::get('/upload-file', [UploadFileController::class, 'showUploadFile'])->name('upload.file.show');
    Route::post('/upload-file', [UploadFileController::class, 'upload'])->name('upload.file');
    Route::get('/requests', function () {
        return view('svcrole.requests');
    })->name('service.requests');
    Route::get('/audits', [AuditLogController::class, 'index'])->name('audits');
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
    Route::get('/course-details', [CourseDetailsController::class, 'show'])->name('course-details');
    Route::post('/course-details/save', [CourseDetailsController::class, 'save'])->name('course-details.save');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
     CheckRole::class.':admin,dept_head,dept_staff',
])->group(function () {
    Route::get('/instructor-report/{instructor_id}', function ($instructor_id) {
        return view('instructor-report', ['instructor_id' => $instructor_id]);
    })->name('instructor-report');
    Route::get('/preview', function () {
        return view('preview');
    })->name('preview');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckRole::class.':admin,dept_head,dept_staff',
])->group(function () {
    Route::get('/performance/{instructor_id}', [ChartController::class, 'showChart'])->name('performance');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckRole::class.':admin,dept_head,dept_staff',
])->group(function () {
    Route::get('/dashboard/{switch}', [ChartController::class, 'showChart'])->name('switch-dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('/courses')->group(function () {
    Route::get('/details/{user}', [CourseDetailsController::class, 'show'])->where('user', '[0-9]+')->name('courses.details.id');
    Route::post('/details/save', [CourseDetailsController::class, 'save'])->name('courses.details.save');
    Route::post('/assign-course', [CourseDetailsController::class, 'assignCourse'])->name('assign-course');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckRole::class.':admin,dept_head,dept_staff'
])->prefix('/staff')->group(function () {
    Route::get('/{user}', [CourseDetailsController::class, 'show'])->where('user', '[0-9]+')->name('staff.id');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
     CheckRole::class.':admin,dept_head,dept_staff',
])->group(function () {
    Route::get('/dept-report', function () {
        return view('dept-report');
    })->name('dept-report');
});
