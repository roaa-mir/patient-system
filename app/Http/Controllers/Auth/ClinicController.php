<?php

namespace App\Http\Controllers\Auth;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    //show all clinics //
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Clinic::all()
        ]);
    }


// add clinic for a doctor //
  public function store(Request $request, $doctor_id)
{
    // Validate the input
    $validated = $request->validate([
        'name' => 'required|string',
        'address' => 'required|string',
        'contact_number' => 'required|string',
    ]);

     // Check if the doctor exists
    $doctor = Doctor::findOrFail($doctor_id);

    // Try to find an existing clinic with same name + address
    $clinic = Clinic::where('name', $validated['name'])
                    ->where('address', $validated['address'])
                    ->first();
    // Create the clinic
    if (!$clinic) {
    $clinic = Clinic::create([
        'name' => $validated['name'],
        'address' => $validated['address'],
        'contact_number' => $validated['contact_number']
    ]);
    }

    $clinic->doctors()->syncWithoutDetaching([$doctor_id]);
    //$clinic->doctors()->attach($doctor->id);
    return response()->json([
        'message' => 'Clinic created and linked to doctor successfully.',
        'clinic' => $clinic
    ]);
}


//show details for specific clinic //
    public function show(Clinic $clinic)
    {
        return response()->json([
            'success' => true,
            'data' => $clinic
        ]);
    }

    //update a clinic //
    public function update(Request $request, Clinic $clinic)
    {
        $validated = $request->validate([
        'name' => 'sometimes|string|max:255',
        'address' => 'sometimes|string|max:255',
        'contact_number' => 'nullable|string|max:20',
        'email' => 'sometimes|email|max:255',
        'facilities' => 'nullable|string',
        'working_hours' => 'nullable|string',
        'is_active' => 'sometimes|boolean',
        'doctor_id' => 'sometimes|exists:doctors,id'
        ]);

        $clinic->update($validated);

        return response()->json([
            'success' => true,
            'data' => $clinic
        ]);
    }

    //delete clinic //
    public function destroy(Clinic $clinic)
    {
        $clinic->doctors()->detach();
        $clinic->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Clinic deleted successfully'
        ]);
    }

   public function detachClinicFromDoctor($clinicId, $doctorId)
{
    // Find the clinic by ID or fail if not found
    $clinic = Clinic::findOrFail($clinicId);

    // Detach the doctor from the clinic in the pivot table only
    $clinic->doctors()->detach($doctorId);

    return response()->json([
        'success' => true,
        'message' => 'Clinic detached from doctor successfully.'
    ]);
}


    //Fetches all doctors belonging to the given clinic.
    public function doctors(Clinic $clinic)
    {
        return response()->json([
            'success' => true,
            'data' => $clinic->doctors()->with('specialitie')->get()
        ]);
    }

    //Fetches all appointments linked to the given clinic.
    public function appointments(Clinic $clinic)
    {
        return response()->json([
            'success' => true,
            'data' => $clinic->appointments()->with(['patient', 'doctor'])->get()
        ]);
    }

    // 5. Check Clinic Availability (today)
    public function checkClinicAvailability(Clinic $clinic)
    {
        $isActive = $clinic->is_active;
        $openToday = false;

        if ($clinic->working_hours) {
            $today = now()->format('l'); // Monday, Tuesday...
            $hours = json_decode($clinic->working_hours, true);

            $openToday = isset($hours[$today]) && $hours[$today] !== null;
        }

        return response()->json([
            'success' => true,
            'is_active' => $isActive,
            'open_today' => $openToday,
        ]);
    }

}
