<?php

use App\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffEditModeController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseDetailsController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\ServiceRoleController;
use App\Http\Middleware\ApplyUserSettings;
use App\Http\Controllers\UploadFileController;
use App\Http\Middleware\CheckRole;
use App\Models\ServiceRole;

// Auth routes

Route::middleware([
    ApplyUserSettings::class,
])->prefix('/auth')->group(function () {
    Route::get('/{provider}', [AuthController::class, 'redirectToProvider'])->name('auth.provider');
    Route::get('/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('auth.provider.callback');
});
// Route::get('auth/{provider}', [AuthController::class, 'redirectToProvider'])->name('auth.provider');
// Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('auth.provider.callback');

// Routes for authenticated and verified users
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    ApplyUserSettings::class
])->group(function () {
    Route::get('/', [ChartController::class, 'showChart'])->name('main');
    Route::get('/dashboard', [ChartController::class, 'showChart'])->name('dashboard');
    Route::get('/notifications', function () {
        return view('notifications');
    })->name('notifications');
    Route::get('/help', function () {
        return view('help');
    })->name('help');
    Route::prefix('/help')->group(function () {
        Route::get('/{topic}', [HelpController::class, 'showHelpPage'])->name('help.topic');
    });
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
    ApplyUserSettings::class,
    CheckRole::class.':admin,dept_head,dept_staff',
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
    Route::get('/upload-file/workday', [UploadFileController::class, 'showUploadFileWorkday'])->name('upload.file.show.workday');
    Route::post('/upload-file/workday', [UploadFileController::class, 'uploadWorkday'])->name('upload.file.workday');
    Route::get('/upload-file/sei-data', [UploadFileController::class, 'showUploadFileSei'])->name('upload.file.show.sei');
    Route::post('/upload-file/sei-data', [UploadFileController::class, 'uploadSei'])->name('upload.file.sei');
    Route::post('/upload/svcroles', [UploadFileController::class, 'uploadSvcRoles'])->name('upload.svcroles');
    Route::get('/requests', function () {
        return view('service-requests');
    })->name('service.requests');
    Route::get('/audits', [AuditLogController::class, 'index'])->name('audits');
});

// Svcroles routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    ApplyUserSettings::class,
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
    Route::get('/manage/{eid}/exports/{eformat}', [ServiceRoleController::class, 'export'])
        ->name('svcroles.export.id');
    // test route for exports.pdf.servicerole
    Route::get('/manage/{id}/preview', function ($id) {
        $serviceRole = ServiceRole::find($id ?? 1)->load('area', 'instructors', 'extraHours');
        if (!$serviceRole) {
            abort(404);
        }
        return view('exports.pdf.servicerole', [
            'serviceRole' => $serviceRole
        ]);
    })->name('exports.pdf.preview');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    ApplyUserSettings::class,
    CheckRole::class.':admin,dept_head,dept_staff,instructor',
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
    Route::post('/create-ta', [CourseDetailsController::class, 'createTA'])->name('createTA');
    Route::get('/export/pdf', [CourseDetailsController::class, 'exportPDF'])->name('export.pdf');
    Route::get('/export/csv', [CourseDetailsController::class, 'exportCSV'])->name('export.csv');
});

    Route::get('/api/teaching-assistants', [CourseDetailsController::class, 'getTeachingAssistants']);
    Route::get('/api/instructors', [CourseDetailsController::class, 'getInstructors']);
    Route::get('/api/courses/instructor/{id}', [CourseDetailsController::class, 'getCoursesByInstructor']);
    Route::post('/api/assign-ta', [CourseDetailsController::class, 'assignTA'])->name('assignTA');
    Route::post('/api/assignTA', [CourseDetailsController::class, 'save'])->name('assignTA');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    ApplyUserSettings::class,
    CheckRole::class.':admin,dept_head,dept_staff',
])->group(function () {
    Route::get('/dept-report', function () {
        return view('dept-report');
    })->name('dept-report');
});
