<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\Appointment;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BillingController extends Controller
{
    use AuthorizesRequests;
    //list all billings for an appointment:
    public function index(Appointment $appointment)
{
    
    $this->authorize('viewAny', Billing::class);

    
    $billings = $appointment->billings()->with('appointment.patient.user', 'appointment.doctor.user')->get();

    return response()->json([
        'success' => true,
        'data' => $billings
    ]);
}

    // show a single billing
    public function show(Billing $billing)
{
    // Load relations needed for policy check
    $billing->load('appointment.doctor.user', 'appointment.patient.user');

    $this->authorize('view', $billing);

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
        'titlee' => [
        'required',
        'string',
        'max:255',
        // Enforce unique per appointment:
        Rule::unique('billings')->where(fn ($q) => 
            $q->where('appointment_id', $appointment->id)
        ),
    ],
        'date' => 'required|date',
        'time' => 'nullable',
        'amount' => 'required|numeric',
        'status' => 'required|in:paid,unpaid,pending',
    ]);

    $billing = $appointment->billings()->create($validated);

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
            'titlee' => 'nullable|string|max:255',
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

    public function destroy(Billing $billing)
{
    $this->authorize('delete', $billing);

    $billing->delete();

    return response()->json([
        'success' => true,
        'message' => 'Billing deleted successfully'
    ]);
}

}