<?php

namespace App\Http\Controllers\Auth;
use App\Models\Appointment;
use \App\Models\Patient;
use App\Models\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
   //Display a listing  //
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor', 'clinic'])->get();

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }

    //show all details for an appointment //
    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'clinic', 'medications', 'billing']);

        return response()->json([
            'success' => true,
            'data' => $appointment
        ]);
    }


//store by patient //
public function storeForPatient(Request $request, Patient $patient)
{
    $validated = $request->validate([
        'doctor_id' => 'required|exists:doctors,id',
        'clinic_id' => 'required|exists:clinics,id',
        'date' => 'required|date',
        'time' => 'required',
        'status' => 'required|in:scheduled,completed,cancelled',
        'description' => 'nullable|string',
    ]);

    $appointment = $patient->appointments()->create($validated);

    return response()->json([
        'success' => true,
        'data' => $appointment->load(['doctor', 'clinic']),
    ]);
}

//store by doctor //
    public function storeForDoctor(Request $request, Doctor $doctor)
{
    $validated = $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'clinic_id' => 'required|exists:clinics,id',
        'date' => 'required|date',
        'time' => 'required|date_format:H:i',
        'status' => 'required|in:scheduled,completed,cancelled',
        'description' => 'nullable|string',
    ]);

    $appointment = $doctor->appointments()->create($validated + [
    'doctor_id' => $doctor->id,
]);


    return response()->json([
        'success' => true,
        'data' => $appointment->load(['patient', 'clinic']),
    ]);
}

//show all appointments for a specific patient//
    public function showAllForPatient(Patient $patient)
    {
        // Get all appointments for the patient with related data
        $appointments = $patient->appointments()->with(['doctor', 'clinic', 'medications', 'billing'])->get();

        $appointments->load(['patient', 'doctor', 'clinic', 'medications', 'billing']);
        return response()->json([
           'success' => true,
           'data' => $appointments,
        ]);
    }

    //show all appointments for a specific doctor//
    public function showAllForDoctor(Doctor $doctor)
    {
        // Get all appointments for the patient with related data
        $appointments = $doctor->appointments()->with(['patient', 'clinic', 'medications', 'billing'])->get();

        return response()->json([
           'success' => true,
           'data' => $appointments,
        ]);
    }
    //Returns the billing information related to a specific appointment.
    public function billing(Appointment $appointment){

         $billing = $appointment->billing;

         return response()->json([
           'success' => true,
           'data' => $billing
         ]);
    }

    public function cancel(Appointment $appointment, Request $request)
{
    $user = $request->user();
    $appointment->load(['patient', 'doctor']);

    $isPatient = optional($appointment->patient)->user_id === $user->id;
    $isDoctor = optional($appointment->doctor)->user_id === $user->id;

    if (!($isPatient || $isDoctor)) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized to cancel this appointment.'
        ], 403);
    }

    $appointment->status = 'canceled';
    $appointment->save();

    return response()->json([
        'success' => true,
        'message' => 'Appointment canceled successfully.',
        'data' => $appointment,
    ]);
}



    // //delete //
    // public function destroy(Appointment $appointment)
    // {
    //     $appointment->delete();

    //     return response()->json([
    //         'success' => true,
    //         'data' => null,
    //         'message' => 'Appointment deleted successfully'
    //     ]);
        
    // }
    //store create
    public function store(Request $request){
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'clinic_id' => 'required|exists:clinics,id',
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required|in:scheduled,completed,cancelled',
            'description' => 'nullable|string',
            'billing_id' => ''
        ]);

        $appointment = Appointment::create($validated);

        return response()->json([
            'success' => true,
            'data' => $appointment
        ]);

    }

    //update for specific id
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'sometimes|exists:patients,id',
            'doctor_id' => 'sometimes|exists:doctors,id',
            'clinic_id' => 'sometimes|exists:clinics,id',
            'date' => 'sometimes|date',
            'time' => 'sometimes',
            'status' => 'sometimes|in:scheduled,completed,cancelled',
            'description' => 'nullable|string',
            'billing_id' => ''
        ]);

        $appointment->update($validated);

        return response()->json([
            'success' => true,
            'data' => $appointment
        ]);
    }



    //Returns all medications related to a specific appointment.
    public function medications(Appointment $appointment){

        $medications = $appointment->medications()->get();

        return response()->json([
           'success' => true,
           'data' => $medications
        ]);
    }
    

    

    

}
