<?php

namespace App\Http\Controllers\Auth;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Clinic::all()
        ]);
    }



//     public function store(Request $request)
// {
//     $validated = $request->validate([
//         'name' => 'required|string',
//         'address' => 'required|string',
//         'contact_number' => 'nullable|string'
//     ]);

//     // Create clinic record
//     $clinic = Clinic::create($validated);

//     // Get the logged-in doctor's ID
//     $doctorId = auth('doctor')->id(); // or auth('doctor')->id();

//     // Link the clinic to the logged-in doctor via the pivot table
//     $clinic->doctors()->attach($doctorId);

//     return response()->json([
//         'success' => true,
//         'data' => $clinic
//     ]);
// }




     public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'contact_number' => 'nullable|string',
            
        ]);

        $clinic = Clinic::create($validated);
        // Get the logged-in user ID
        $userId = auth('web')->id();

    // Find the doctor profile using user_id
        $doctor = Doctor::where('user_id', $userId)->first();

    // Attach doctor to clinic via pivot table
        if ($doctor) {
            $clinic->doctors()->attach($doctor->id);
        }
        return response()->json([
            'success' => true,
            'data' => $clinic
        ]);
    }

    public function show(Clinic $clinic)
    {
        return response()->json([
            'success' => true,
            'data' => $clinic
        ]);
    }

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
    public function destroy(Clinic $clinic)
    {
        $clinic->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Clinic deleted successfully'
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
