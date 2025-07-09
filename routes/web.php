<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QLearningController;
use App\Http\Controllers\TrixUploadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// login, register, logout
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('admin')) {
            return redirect('/admin/dashboard');
        }
        return redirect('/');
    });

    Route::get('/', fn () => view('user.index'))->name('homeUser');
    Route::get('/admin/dashboard', fn () => view('admin.index'))->name('adminIndex');
});

Route::get('/cek-type', function () {
    dd(get_class(auth()->user()));
});

// Halaman user
Route::get('/', [UserController::class, 'index'])->name('homeUser');
Route::get('/calendar-partial', [UserController::class, 'calendarPartial'])->name('calendarPartial');
Route::get('/instruksi', [UserController::class, 'instruksi'])->name('instruksiUser');
Route::get('/workout', [WorkoutController::class, 'index'])->name('workoutsUser');

Route::middleware('auth')->group(function () {
    Route::post('/kondisi/update', [UserController::class, 'updateKondisi'])->name('kondisiUpdate');
    Route::post('/preferensi/update', [UserController::class, 'updatePreferensi'])->name('preferensiUpdate');
    Route::post('/user/kondisi', [UserController::class, 'updateKondisi'])->name('updateKondisi');
    Route::post('/user/preferensi', [UserController::class, 'updatePreferensi'])->name('updatePreferensi');
    Route::get('/history', [UserController::class, 'history'])->name('historyUser');
    Route::post('/rekomendasi', [QLearningController::class, 'proses'])->name('qLearningProses');
    Route::post('/feedback', [QLearningController::class, 'feedback'])->name('qLearningFeedback');
});

// Halaman admin
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('adminIndex');
    
    Route::get('/workout', [AdminController::class, 'workout'])->name('adminWorkout');
    Route::post('/workout', [AdminController::class, 'storeWorkout'])->name('adminWorkoutStore');
    Route::put('/workout/{id}', [AdminController::class, 'updateWorkout'])->name('adminWorkoutUpdate');
    Route::delete('/workout/{id}', [AdminController::class, 'deleteWorkout'])->name('adminWorkoutDelete');
    
    Route::get('/user', [AdminController::class, 'user'])->name('adminUser');
    Route::post('/user', [AdminController::class, 'storeUser'])->name('adminUserStore');
    Route::put('/user/{id}', [AdminController::class, 'updateUser'])->name('adminUserUpdate');
    Route::delete('/user/{id}', [AdminController::class, 'deleteUser'])->name('adminUserDelete');

    Route::get('/history', [AdminController::class, 'history'])->name('adminHistory');
    
    Route::post('/trix-upload', [TrixUploadController::class, 'upload'])->name('trix.image.upload');
});