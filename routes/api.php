<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;
use App\Models\Doctor;
use App\Http\Controllers\DoctorController;

use App\Http\Controllers\Auth\AuthController;

Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Route::apiResource('doctor', DoctorController::class);
