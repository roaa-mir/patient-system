<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Medication::with(['patient', 'appointment'])->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'required|exists:appointments,id',
            'name' => 'required|string',
            'doses' => 'required|string',
            'startDate' => 'required|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
        ]);

        $medication = Medication::create($validated);

        return response()->json([
            'success' => true,
            'data' => $medication
        ]);
    }

    public function show(Medication $medication)
    {
        return response()->json([
            'success' => true,
            'data' => $medication->load(['patient', 'appointment'])
        ]);
    }

    public function update(Request $request, Medication $medication)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'doses' => 'sometimes|string',
            'startDate' => 'sometimes|date',
            'endDate' => 'sometimes|date|after_or_equal:startDate',
        ]);

        $medication->update($validated);

        return response()->json([
            'success' => true,
            'data' => $medication
        ]);
    }

    public function destroy(Medication $medication)
    {
        $medication->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Medication deleted successfully'
        ]);
    }
}

