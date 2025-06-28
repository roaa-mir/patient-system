<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\Appointment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BillingController extends Controller
{
    use AuthorizesRequests;
    // public function index()
    // {
    //     $user = auth()->user();

    //     // Load billings with appointment + patient + doctor relations
    //     if ($user->role === 'doctor') {
    //         $billings = Billing::with('appointment.patient.user', 'appointment.doctor.user')->get();
    //     } elseif ($user->role === 'patient') {
    //         $billings = Billing::whereHas('appointment', function ($query) use ($user) {
    //             $query->where('patient_id', $user->patient->id);
    //         })->with('appointment.patient.user', 'appointment.doctor.user')->get();
    //     } else {
    //         $billings = collect(); // empty collection for unauthorized roles
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'data' => $billings
    //     ]);
    // }

    public function show(Billing $billing)
    {
        $this->authorize('view', $billing);

        $billing->load('appointment.patient.user', 'appointment.doctor.user');

        return response()->json([
            'success' => true,
            'data' => $billing
        ]);
    }

// Create billing for a specific appointment
    public function store(Request $request, Appointment $appointment)
{
    $this->authorize('create',Billing::class);

    if ($appointment->billing) {
        return response()->json([
            'success' => false,
            'message' => 'Billing already exists for this appointment.'
        ], 400);
    }

    $validated = $request->validate([
        'date' => 'required|date',
        'time' => 'nullable',
        'amount' => 'required|numeric',
        'status' => 'required|in:paid,unpaid,pending',
    ]);

    $billing = $appointment->billing()->create($validated);

    return response()->json([
        'success' => true,
        'data' => $billing
    ]);   
}
// Update billing of a specific appointment
    public function update(Request $request, Appointment $appointment)
    {
        $billing = $appointment->billing;

        $this->authorize('update', $billing);

        $validated = $request->validate([
            'date' => 'sometimes|date',
            'time' => 'nullable',
            'amount' => 'sometimes|numeric',
            'status' => 'sometimes|in:paid,unpaid,pending',
        ]);

        $billing->update($validated);

        return response()->json([
            'success' => true,
            'data' => $billing
        ]);
    }

    // Delete billing of a specific appointment
    public function destroy(Appointment $appointment)
    {
        $billing = $appointment->billing;

        $this->authorize('delete', $billing);

        $billing->delete();

        return response()->json([
            'success' => true,
            'message' => 'Billing deleted successfully'
        ]);
    }
}