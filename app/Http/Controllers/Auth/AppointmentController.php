<?php

namespace App\Http\Controllers\Auth;
use App\Models\Appointment;
use \App\Models\Patient;

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


    public function storeForPatient(Request $request, $patient)
{
    $validated = $request->validate([
        'doctor_id' => 'required|exists:doctors,id',
        'clinic_id' => 'required|exists:clinics,id',
        'date' => 'required|date',
        'time' => 'required',
        'status' => 'required|in:scheduled,completed,cancelled',
        'description' => 'nullable|string',
        'billing_id' => 'nullable|exists:billings,id',
    ]);

    $appointment = $patient->appointments()->create($validated);

    return response()->json([
        'success' => true,
        'data' => $appointment->load(['doctor', 'clinic'])
    ]);
}




    //show specific //
    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'clinic', 'medications', 'billing']);

        return response()->json([
            'success' => true,
            'data' => $appointment
        ]);
    }

    //delete //
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Appointment deleted successfully'
        ]);
        
    }
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
    //Returns the billing information related to a specific appointment.
    public function billing(Appointment $appointment){

         $billing = $appointment->billing;

         return response()->json([
           'success' => true,
           'data' => $billing
         ]);
    }

}
