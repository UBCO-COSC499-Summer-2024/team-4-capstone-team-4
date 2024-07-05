 <?php

use App\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffEditModeController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseDetailsController;
use App\Http\Middleware\CheckRole;

// Route::get('/', function () {
//     return view('auth.login');
// });

Route::get('auth/{provider}', [AuthController::class, 'redirectToProvider'])->name('auth.provider');
Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('auth.provider.callback');

// for / if user is not logged in, redirect to auth.login else /dashboard
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', [ChartController::class, 'showChart'])->name('main');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
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
    Route::get('/staff-edit-mode', function(){
        return view('staff-edit-mode');
    })->name('staff-edit-mode');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/notifications', function () {
        return view('notifications');
    })->name('notifications');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/help', function () {
        return view('help');
    })->name('help');
});

// leaderboard and /performance routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckRole::class.':admin,dept_head,dept_staff'
])->group(function () {
    Route::get('/leaderboard', function () {
        return view('leaderboard');
    })->name('leaderboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/performance', function () {
        return view('performance');
    })->name('performance');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/courses', function () {
        return view('courses');
    })->name('courses');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/svcroles', function () {
        return view('svcroles');
    })->name('svcroles');
});

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
    Route::get('course-details/{id?}', [CourseDetailsController::class, 'show'])->name('course-details');
});

Route::get('/privacy-policy', function () {
    return view('policy');
})->name('privacy-policy');

Route::get('/tos', function () {
    return view('terms');
})->name('tos');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckRole::class.':admin,dept_head,dept_staff'
])->group(function () {
    Route::get('/import', function () {
        return view('import');
    })->name('import');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/assign-courses', function () {
        return view('assign-courses');
    })->name('assign-courses');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [ChartController::class, 'showChart'])->name('dashboard');
});
