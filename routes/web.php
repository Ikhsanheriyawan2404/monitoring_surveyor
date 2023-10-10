<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\SurveyorController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Users

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
    ->middleware('auth');

Route::get('users/create', [UsersController::class, 'create'])
    ->name('users.create')
    ->middleware('auth');

Route::post('users', [UsersController::class, 'store'])
    ->name('users.store')
    ->middleware('auth');

Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware('auth');

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth');

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware('auth');

// Branches

Route::get('branches', [BranchController::class, 'index'])
    ->name('branches')
    ->middleware('auth');

Route::get('branches/create', [BranchController::class, 'create'])
    ->name('branches.create')
    ->middleware('auth');

Route::post('branches', [BranchController::class, 'store'])
    ->name('branches.store')
    ->middleware('auth');

Route::get('branches/{branch}/edit', [BranchController::class, 'edit'])
    ->name('branches.edit')
    ->middleware('auth');

Route::put('branches/{branch}', [BranchController::class, 'update'])
    ->name('branches.update')
    ->middleware('auth');

Route::delete('branches/{branch}', [BranchController::class, 'destroy'])
    ->name('branches.destroy')
    ->middleware('auth');

Route::put('branches/{branch}/restore', [BranchController::class, 'restore'])
    ->name('branches.restore')
    ->middleware('auth');

// Surveyors

Route::get('surveyors', [SurveyorController::class, 'index'])
    ->name('surveyors')
    ->middleware('auth');

Route::get('surveyors/create', [SurveyorController::class, 'create'])
    ->name('surveyors.create')
    ->middleware('auth');

Route::post('surveyors', [SurveyorController::class, 'store'])
    ->name('surveyors.store')
    ->middleware('auth');

Route::get('surveyors/{surveyor}/edit', [SurveyorController::class, 'edit'])
    ->name('surveyors.edit')
    ->middleware('auth');

Route::put('surveyors/{surveyor}', [SurveyorController::class, 'update'])
    ->name('surveyors.update')
    ->middleware('auth');

Route::delete('surveyors/{surveyor}', [SurveyorController::class, 'destroy'])
    ->name('surveyors.destroy')
    ->middleware('auth');

Route::put('surveyors/{surveyor}/restore', [SurveyorController::class, 'restore'])
    ->name('surveyors.restore')
    ->middleware('auth');

// Tasks

Route::get('tasks', [TaskController::class, 'index'])
    ->name('tasks')
    ->middleware('auth');

Route::get('tasks/create', [TaskController::class, 'create'])
    ->name('tasks.create')
    ->middleware('auth');

Route::post('tasks', [TaskController::class, 'store'])
    ->name('tasks.store')
    ->middleware('auth');

Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])
    ->name('tasks.edit')
    ->middleware('auth');

Route::put('tasks/{task}', [TaskController::class, 'update'])
    ->name('tasks.update')
    ->middleware('auth');

Route::delete('tasks/{task}', [TaskController::class, 'destroy'])
    ->name('tasks.destroy')
    ->middleware('auth');

Route::put('tasks/{task}/restore', [TaskController::class, 'restore'])
    ->name('tasks.restore')
    ->middleware('auth');

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');
