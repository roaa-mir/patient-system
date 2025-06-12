<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Billing;

class BillingController extends Controller
{
    // public function index()
    // {
    //     return response()->json([
    //         'success' => true,
    //         'data' => Billing::with('appointment')->get()
    //     ]);
    // }
    // public function show(Billing $billing)
    // {
    //     return response()->json([
    //         'success' => true,
    //         'data' => $billing->load('appointment')
    //     ]);
    // }
    public function destroy(Billing $billing)
    {
        $billing->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Billing deleted successfully'
        ]);
    }
    public function update(Request $request, Billing $billing)
    {
        $validated = $request->validate([
            'amount' => 'sometimes|numeric',
            'status' => 'sometimes|in:paid,unpaid,pending',
            'billing_date' => 'sometimes|date'
        ]);

        $billing->update($validated);

        return response()->json([
            'success' => true,
            'data' => $billing
        ]);
    }
   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'amount' => 'required|numeric',
            'status' => 'required|in:paid,unpaid,pending',
            'billing_date' => 'required|date'
        ]);

        $billing = Billing::create($validated);

        return response()->json([
            'success' => true,
            'data' => $billing
        ]);
    }
}
