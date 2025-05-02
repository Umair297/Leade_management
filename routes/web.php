<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Auth\RegisterController;

// Case Routes
Route::delete('/cases/bulk-delete', [CaseController::class, 'bulkDelete'])->name('cases.bulkDelete');
Route::delete('/cases/bulk-delete', [CaseController::class, 'bulkDeletes'])->name('cases.bulkDelete');
Route::get('/cases/upload', [CaseController::class, 'index'])->name('cases.index');
Route::post('/cases/upload', [CaseController::class, 'upload'])->name('cases.upload');
Route::get('/cases/create', [CaseController::class, 'create'])->name('cases.create');
Route::get('cases/{case}/edit', [CaseController::class, 'edit'])->name('cases.edit');
Route::put('/cases/{case}', [CaseController::class, 'update'])->name('cases.update');
Route::put('/cases/{id}/update-status', [CaseController::class, 'updateStatus'])->name('cases.updateStatus');
Route::delete('cases/{case}', [CaseController::class, 'destroy'])->name('cases.destroy');
Route::put('/cases/{id}/follow-up', [CaseController::class, 'followUp'])->name('cases.followUp');

// In routes/web.php
Route::get('/notifications', [CaseController::class, 'getFollowUpNotifications']);
Route::post('/cases/{id}/clear-notification', [CaseController::class, 'clearNotification']);
Route::post('/cases/clear-all-notifications', [CaseController::class, 'clearAllNotifications']);
// Assignment Routes

Route::post('assignments/store', [CaseController::class, 'storeAssignment'])->name('assignments.store');
Route::get('/assign/calendar', [AssignmentController::class, 'calendar'])->name('assign.calendar'); // Calendar view
Route::get('/assign/events', [AssignmentController::class, 'getEvents'])->name('assign.events');   // Fetch events dynamically
Route::get('/assign', [AssignmentController::class, 'index'])->name('assign.index'); // Display assignment list
Route::post('/assign', [AssignmentController::class, 'assign'])->name('assignments.assign'); // Assign case

// Authentication and Home Routes
Auth::routes(['register' => false]);  // Disable registration routes
Route::get('/register', function () {
    return view('error');  
});

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// User Routes
Route::get('/users', [RegisterController::class, 'index'])->name('users.index');
Route::get('users/create', [RegisterController::class, 'create'])->name('users.create');
Route::post('/users', [RegisterController::class, 'store'])->name('users.store');
Route::get('users/{user}/edit', [RegisterController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [RegisterController::class, 'update'])->name('users.update');
Route::delete('users/{user}', [RegisterController::class, 'destroy'])->name('users.destroy');
