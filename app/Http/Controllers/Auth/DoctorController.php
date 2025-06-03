<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    // List all doctors//
    public function index()
    {
        $doctors = Doctor::with('user', 'specialitie','clinics')->get();
        return response()->json([
            'success' => true,
            'data' => $doctors
        ]);
    }

    // Show a specific doctor //
    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'specialitie', 'clinics']);

        return response()->json([
            'success' => true,
            'data' => $doctor
        ]);
    }

    // Store a new doctor profile//
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phoneNumber' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'speciality_id' => 'required|exists:specialities,id',
            'clinic_ids' => 'sometimes|array',
            'clinic_ids.*' => 'exists:clinics,id',
        ]);

        $doctor = Doctor::create($validatedData);

        return response()->json($doctor, 201);
    }

    // Update an existing doctor profile //
    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'speciality_id' => 'sometimes|exists:specialities,id',
            //'clinic_id' => 'sometimes|exists:clinics,id',
            'firstname' => 'sometimes|string|max:255',
            'lastname' => 'sometimes|string|max:255',
            'email' => 'sometimes|email',
            'phoneNumber' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
            'gender' => 'sometimes|in:male,female',
        ]);

        $doctor->update($validated);

        return response()->json([
            'success' => true,
            'data' => $doctor
        ]);
    }

    // Delete a doctor profile //
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Doctor deleted successfully'
        ]);
    }

    //returns all appointments related to a specific doctor.//
    public function appointments(Doctor $doctor)
    {
        return response()->json([
            'success' => true,
            'data' => $doctor->appointments()->with(['patient', 'clinic'])->get()
        ]);
    }

    // Doctors by Speciality
    public function doctorsBySpecialitie($specialitie_id)
    {
        $doctors = Doctor::where('specialitie_id', $specialitie_id)
            ->with('clinic')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $doctors
        ]);
    }
    // Search Doctors by Name
    public function searchDoctors(Request $request)
    {
        $request->validate(['query' => 'required|string']);

        $doctors = Doctor::where('firstname', 'like', '%' . $request->query . '%')
            ->orWhere('lastname', 'like', '%' . $request->query . '%')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $doctors
        ]);
    }
}

