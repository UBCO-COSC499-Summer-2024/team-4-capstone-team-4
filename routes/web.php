 <?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseDetailsController;


Route::get('auth/{provider}', [AuthController::class, 'redirectToProvider'])->name('auth.provider');
Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('auth.provider.callback');

// for / if user is not logged in, redirect to auth.login else /dashboard
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', [ChartController::class, 'showChart'])->name('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/staff', function () {
        return view('staff');
    })->name('staff');
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

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
    Route::get('/course-details', [CourseDetailsController::class, 'show'])->name('course-details');
    Route::post('/course-details/save', [CourseDetailsController::class, 'save'])->name('course-details.save');
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
])->group(function () {
    Route::get('/import', function () {
        return view('import');
    })->name('import');
});
