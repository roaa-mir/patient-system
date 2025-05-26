<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    // List all doctors
    public function index()
    {
        $doctors = Doctor::with('user', 'speciality')->get();
        return response()->json($doctors, 200);
    }

    // Show a specific doctor by ID
    public function show($id)
    {
        $doctor = Doctor::with('user', 'speciality')->find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        return response()->json($doctor, 200);
    }

    // Store a new doctor profile
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phoneNumber' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'speciality_id' => 'required|exists:specialities,id',
        ]);

        $doctor = Doctor::create($validatedData);

        return response()->json($doctor, 201);
    }

    // Update an existing doctor profile
    public function update(Request $request, $id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        $validatedData = $request->validate([
            'firstname' => 'sometimes|required|string|max:255',
            'lastname' => 'sometimes|required|string|max:255',
            'phoneNumber' => 'sometimes|nullable|string|max:20',
            'address' => 'sometimes|nullable|string|max:255',
            'gender' => 'sometimes|nullable|in:male,female,other',
            'speciality_id' => 'sometimes|required|exists:specialities,id',
        ]);

        $doctor->update($validatedData);

        return response()->json($doctor, 200);
    }

    // Delete a doctor profile
    public function destroy($id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        $doctor->delete();

        return response()->json(['message' => 'Doctor deleted successfully'], 200);
    }
}

