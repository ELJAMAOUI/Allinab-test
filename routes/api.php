<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MembersController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Get all members
Route::get('/members', [MembersController::class, 'index']);
 // Get a specific member by ID
Route::get('/members/{id}', [MembersController::class, 'show']);
// Create a new member
Route::post('/members', [MembersController::class, 'create']);
// Update a member by ID
Route::put('/members/{id}', [MembersController::class, 'update']);
// delete a member by ID
Route::delete('/members/{id}', [MembersController::class, 'destroy']);
