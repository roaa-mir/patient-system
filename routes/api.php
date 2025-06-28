<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\Auth;
use App\Models\Doctor;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\DoctorController;
use App\Http\Controllers\Auth\PatientController;
use App\Http\Controllers\Auth\ClinicController;
use App\Http\Controllers\Auth\MedicationController;
use App\Http\Controllers\Auth\BillingController;
use App\Http\Controllers\Auth\AppointmentController;
use App\Http\Controllers\Auth\SpecialitieController;


use App\Http\Controllers\Auth\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected route for logout
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {

// USERS
Route::apiResource('users', UserController::class);

//PATIENTS
Route::get('/patients', [PatientController::class, 'index']);
Route::get('/patients/{patient}', [PatientController::class, 'show']);
Route::put('/patients/{patient}', [PatientController::class, 'update']);
Route::delete('/patients/{patient}', [PatientController::class, 'destroy']);
Route::get('/patients/{patient}/medications', [PatientController::class, 'medications']);
Route::get('/patients/{patient}/appointments', [PatientController::class, 'appointments']);
Route::get('/patients/{patient}/appointmentsdetails', [PatientController::class, 'appointments_clinic_doctor']);

// DOCTORS
    Route::apiResource('doctors', DoctorController::class);
    Route::get('doctors/{doctor}/appointments', [DoctorController::class, 'appointments']);
    ///Route::get('doctors/search', [DoctorController::class, 'searchDoctors']);
    //Route::get('doctors/speciality/{id}', [DoctorController::class, 'doctorsBySpecialitie']);// make it without auth
    Route::get('/doctors/{doctorId}/clinics', [DoctorController::class, 'getClinics']);
    Route::get('/doctors/{doctor}/details', [DoctorController::class, 'showdetails']);
    Route::put('/doctors/{doctor}', [DoctorController::class, 'update']);
    

//specialties
    Route::apiResource('specialities', SpecialitieController::class);


    //MEDICATIONS
Route::get('/medications', [MedicationController::class, 'index']);
Route::post('/medications', [MedicationController::class, 'store']);
Route::get('/medications/{medication}', [MedicationController::class, 'show']);
Route::put('/medications/{medication}', [MedicationController::class, 'update']);
Route::delete('/medications/{medication}', [MedicationController::class, 'destroy']);

//BILLINGS 


Route::post('appointments/{appointment}/billings', [BillingController::class, 'store']);
Route::get('appointments/{appointment}/billings', [BillingController::class, 'index']);
Route::get('billings/{billing}', [BillingController::class, 'show']);
Route::put('billings/{billing}', [BillingController::class, 'update']);
Route::delete('billings/{billing}', [BillingController::class, 'destroy']);
    
// CLINICS
    Route::apiResource('clinics', ClinicController::class);
    Route::post('/clinics/{doctor}', [ClinicController::class, 'store']);
    Route::put('/clinics/{clinic}', [ClinicController::class, 'update']);
    Route::delete('/clinics/{clinic}', [ClinicController::class, 'destroy']);
    Route::get('/clinics/{clinic}', [ClinicController::class, 'show']);
    Route::get('clinics/{clinic}/doctors', [ClinicController::class, 'doctors']);
    Route::get('clinics/{clinic}/appointments', [ClinicController::class, 'appointments']);
    Route::delete('/clinics/{clinic}/doctors/{doctor}', [ClinicController::class, 'detachClinicFromDoctor']);

 //APPOINTMENTs
 Route::get('/appointments', [AppointmentController::class, 'index']);
 Route::get('/appointments/{appointment}', [AppointmentController::class, 'show']);//show all details for appointment
//patient creates appointment //doctor creates appointment
Route::post('/appointments/patients/{patient}', [AppointmentController::class, 'storeForPatient']);
Route::post('/appointments/doctors/{doctor}', [AppointmentController::class, 'storeForDoctor']);

// //show all appointments for a specific patient
Route::get('/appointments/patients/{patient}', [AppointmentController::class, 'showAllForPatient']);
// //show all appointments for a specific doctor
Route::get('/appointments/doctors/{doctor}', [AppointmentController::class, 'showAllForDoctor']);

Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy']);
Route::patch('/appointments/{appointment}', [AppointmentController::class, 'update']);
Route::get('/appointments/{appointment}/medication', [AppointmentController::class, 'medication']);
Route::get('/appointments/{appointment}/billing', [AppointmentController::class, 'billing']);

// Cancel appointment (accessible by doctor or patient)
//Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel']);



});

Route::get('doctors/speciality/{id}', [DoctorController::class, 'doctorsBySpecialitie']);
//Route::get('doctors/search', [DoctorController::class, 'searchDoctors']);