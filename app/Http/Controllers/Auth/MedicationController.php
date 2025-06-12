<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class MedicationController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('viewAny', Medication::class);
        return response()->json([
            'success' => true,
            'data' => Medication::with(['patient', 'appointment'])->get()
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Medication::class);
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
        $this->authorize('view', $medication);
        return response()->json([
            'success' => true,
            'data' => $medication->load(['patient', 'appointment'])
        ]);
    }

    public function update(Request $request, Medication $medication)
    {
        $this->authorize('update', $medication);
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
        $this->authorize('delete', $medication);
        $medication->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Medication deleted successfully'
        ]);
    }
}

