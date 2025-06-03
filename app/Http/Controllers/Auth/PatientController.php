<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;

class PatientController extends Controller
{
    //Display a listing //
    public function index()
    {
         $patients = Patient::all();

        return response()->json([
            'success' => true,
            'patients' => $patients
        ]);
    }

    //for specific patient //
    public function show(Patient $patient)
    {
        return response()->json([
            'success' => true,
            'data' => $patient
        ]);
    }


    //Put //
    public function update(Request $request, Patient $patient){

        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        $validated = $request->validate([
        'firstName' => 'sometimes|string|max:255',
        'lastName' => 'sometimes|string|max:255',
        'dateOfBirth' => 'sometimes|date',
        'gender' => 'sometimes|in:male,female',
        'age' => 'sometimes|integer',
        'phoneNumber' => 'sometimes|string|max:20',
        'address' => 'nullable|string|max:255',
        'email' => 'sometimes|email|max:255',
        'weight' => 'sometimes|numeric',
        'height' => 'sometimes|numeric',
        'bloodType' => 'sometimes|in:' . implode(',', $bloodTypes),
         ]);

        $patient->update($validated);
   
        return response()->json([
          'success' => true,
          'data' => $patient
        ]);

    }

    //delete //
    public function destroy(Patient $patient)
    {
        $patient->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Patient deleted successfully'
        ]);
        
    }

    //retrieve all medications assigned to a specific patient
    public function medications(Patient $patient){

         $medications = $patient->medications()->get();

         return response()->json([
           'success' => true,
           'data' => $medications
        ]);
    }
    
    // retrieves all appointments for a specific patient
    public function appointments(Patient $patient)
    {
         return response()->json([
             'success' => true,
             'data' => $patient->appointments
        ]);
    }
    //retrieve all appointments for specific patient with clinic and doctor
    public function appointments_clinic_doctor(Patient $patient)
   {
    $appointments = $patient->appointments()
        ->with(['doctor', 'clinic']) // eager load relationships
        ->get();

    return response()->json([
        'success' => true,
        'data' => $appointments
    ]);
    }

}
