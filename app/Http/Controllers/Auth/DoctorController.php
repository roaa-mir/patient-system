<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DoctorController extends Controller
{
    use AuthorizesRequests;
    // List all doctors///
    public function index()
    {
        $doctors = Doctor::with('user', 'specialitie','clinics')->get();
        return response()->json([
            'success' => true,
            'data' => $doctors
        ]);
    }
   
//get all clinics for specific dr ///
public function getClinics($doctorId) {
    $doctor = Doctor::with('clinics')->find($doctorId);

    if (!$doctor) {
        return response()->json(['success' => false, 'message' => 'Doctor not found'], 404);
    }

    return response()->json([
        'success' => true,
        'doctor_id' => $doctor->id,
        'clinics' => $doctor->clinics
    ]);
}



    // Show details for a specific doctor ///
    public function showdetails(Doctor $doctor)
    {
        $doctor->load(['user', 'specialitie', 'clinics']);

        return response()->json([
            'success' => true,
            'data' => $doctor
        ]);
    }


    // Update doctor profile //
    public function update(Request $request, Doctor $doctor)
    {
        $this->authorize('update', $doctor);
        $validated = $request->validate([
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

    // AppoforApecDR returns all appointments related to a specific doctor.///
    public function appointments(Doctor $doctor)
    {
        $this->authorize('view', $doctor);
        return response()->json([
            'success' => true,
            'data' => $doctor->appointments()->with(['patient', 'clinic'])->get()
        ]);
    }

    // Doctors by Speciality ///
    public function doctorsBySpecialitie($specialitie_id)
    {
        $doctors = Doctor::where('specialitie_id', $specialitie_id)
            ->with('clinics')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $doctors
        ]);
    }



    
//     // Search Doctors by Name
//     public function searchDoctors(Request $request)
// {
//     $request->validate(['query' => 'required|string']);

//     $query = $request->query('query'); // from ?query=a

//     $doctors = Doctor::where('firstname', 'like', "%$query%")
//         ->orWhere('lastname', 'like', "%$query%")
//         ->get(['id', 'firstname', 'lastname']); // return only needed fields

//     return response()->json([
//         'success' => true,
//         'data' => $doctors
//     ]);
// }

}

