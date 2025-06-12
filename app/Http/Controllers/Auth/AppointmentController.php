<?php

namespace App\Http\Controllers\Auth;
use App\Models\Appointment;
use \App\Models\Patient;
use App\Models\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AppointmentController extends Controller
{
      use AuthorizesRequests;
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
    // Check if the selected clinic belongs to the selected doctor
    $isDoctorInClinic = DB::table('clinic_doctor')
        ->where('doctor_id', $validated['doctor_id'])
        ->where('clinic_id', $validated['clinic_id'])
        ->exists();
    if (!$isDoctorInClinic) {
        return response()->json([
            'success' => false,
            'message' => 'This doctor is not assigned to the selected clinic.'
        ], 403);
    }

    // Check if the patient already has an appointment with this doctor on the same date
    $alreadyExists = $patient->appointments()
        ->where('doctor_id', $validated['doctor_id'])
        ->where('date', $validated['date'])
        ->exists();

    if ($alreadyExists) {
        return response()->json([
            'success' => false,
            'message' => 'You already have an appointment with this doctor on this date.'
        ], 409);
    }

    //create the appointment
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
    


    //delete // Only allow doctor/patient who owns the appointment to delete//
    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $appointment->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Appointment deleted successfully'
        ]);
        
    }


    //update for specific id
    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $validated = $request->validate([
            'date' => 'sometimes|date',
            'time' => 'sometimes',
            'status' => 'sometimes|in:scheduled,completed,cancelled',
            'description' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return response()->json([
            'success' => true,
            'data' => $appointment->load(['patient', 'doctor', 'clinic']),
        ]);
    }



    //Returns all medications related to a specific appointment.//
    public function medication(Appointment $appointment){

        $medication = $appointment->medications()->get();

        return response()->json([
           'success' => true,
           'data' => $medication
        ]);
    }
    //Returns the billing information related to a specific appointment.//
    public function billing(Appointment $appointment){

         $billing = $appointment->billing;

         return response()->json([
           'success' => true,
           'data' => $billing
         ]);
    }
    













    //     public function cancel(Appointment $appointment, Request $request)
// {
//     $user = $request->user();
//     $appointment->load(['patient', 'doctor']);

//     $isPatient = optional($appointment->patient)->user_id === $user->id;
//     $isDoctor = optional($appointment->doctor)->user_id === $user->id;

//     if (!($isPatient || $isDoctor)) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Unauthorized to cancel this appointment.'
//         ], 403);
//     }

//     $appointment->status = 'canceled';
//     $appointment->save();

//     return response()->json([
//         'success' => true,
//         'message' => 'Appointment canceled successfully.',
//         'data' => $appointment,
//     ]);
// }

}
