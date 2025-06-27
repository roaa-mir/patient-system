<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Billing;
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

    public function store(Request $request)
    {
        $this->authorize('create', Billing::class);

        // Since billing is linked from appointment, 
        // you might need appointment_id to create a billing and link it later,
        // but with your setup, appointment has billing_id, so you can't set that here directly.

        // For now, just validate billing fields:
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'nullable',
            'amount' => 'required|numeric',
            'status' => 'required|in:paid,unpaid,pending',
        ]);

        $billing = Billing::create($validated);

        return response()->json([
            'success' => true,
            'data' => $billing
        ]);
    }

    public function update(Request $request, Billing $billing)
    {
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

    public function destroy(Billing $billing)
    {
        $this->authorize('delete', $billing);

        $billing->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Billing deleted successfully'
        ]);
    }
}
