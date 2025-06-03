<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\Auth;
use App\Models\Doctor;

use App\Http\Controllers\Auth\DoctorController;
use App\Http\Controllers\Auth\PatientController;
use App\Http\Controllers\Auth\ClinicController;
use App\Http\Controllers\Auth\AppointmentController;
use App\Http\Controllers\Auth\SpecialitieController;


use App\Http\Controllers\Auth\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected route for logout
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

//patient routes
Route::get('/patients', [PatientController::class, 'index']);
Route::get('/patients/{patient}', [PatientController::class, 'show']);
Route::put('/patients/{patient}', [PatientController::class, 'update']);
Route::delete('/patients/{patient}', [PatientController::class, 'destroy']);
Route::get('/patients/{patient}/medications', [PatientController::class, 'medications']);
Route::get('/patients/{patient}/appointments', [PatientController::class, 'appointments']);
Route::get('/patients/{patient}/appointmentsdetails', [PatientController::class, 'appointments_clinic_doctor']);

//Appointment
Route::post('/patients/{patient}/appointments', [AppointmentController::class, 'storeForPatient']);


// DOCTORS
    Route::apiResource('doctors', DoctorController::class);
    Route::get('doctors/{doctor}/appointments', [DoctorController::class, 'appointments']);
    Route::get('doctors/search', [DoctorController::class, 'searchDoctors']);
    Route::get('doctors/speciality/{id}', [DoctorController::class, 'doctorsBySpecialitie']);

//Route::apiResource('specialities', SpecialitieController::class);

//Clinics
// //Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('clinics', ClinicController::class);
// //});

Route::middleware('auth:doctor')->group(function () {
    Route::post('/clinics', [ClinicController::class, 'store']);
});

