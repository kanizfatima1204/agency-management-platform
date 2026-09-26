<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
});
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/files', [ProjectFileController::class, 'index'])->name('files.index');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/projects', [ProjectController::class, 'store'])->middleware('role:admin');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::post('/projects/{project}/payments', [PaymentController::class, 'store']);
    Route::post('/projects/{project}/messages', [MessageController::class, 'store']);
    Route::post('/projects/{project}/files', [ProjectFileController::class, 'store']);
    Route::get('/projects/{project}/files/{file}', [ProjectFileController::class, 'download'])->name('project-files.download');
});
