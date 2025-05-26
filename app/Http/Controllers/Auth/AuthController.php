<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:doctor,patient',
            
            // Doctor specific fields
            'firstname' => 'required_if:role,doctor|string|max:255',
            'lastname' => 'required_if:role,doctor|string|max:255',
            'specialitie_id' => 'required_if:role,doctor|exists:specialities,id',
            
            // Patient specific fields
            'firstName' => 'required_if:role,patient|string|max:255',
            'lastName' => 'required_if:role,patient|string|max:255',
            'dateOfBirth' => 'required_if:role,patient|date',
        ]);
        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);
        
        // Create role-specific profile
        if ($validated['role'] === 'doctor') {
            Doctor::create([
                'user_id' => $user->id,
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'specialitie_id' => $validated['specialitie_id'],
                // Add other doctor fields with default values or from request
            ]);
            } else {
            Patient::create([
                'user_id' => $user->id,
                'firstName' => $validated['firstName'],
                'lastName' => $validated['lastName'],
                'dateOfBirth' => $validated['dateOfBirth'],
                // Add other patient fields with default values or from request
            ]);
        }
        
        // Create token for immediate login
        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }



//     public function register(Request $request)
// {
//     $request->validate([
//         'firstName' => 'required|string|max:255',
//         'lastName' => 'required|string|max:255',
//         'email' => 'required|email|unique:users',
//         'password' => 'required|min:6|confirmed',
//         'role' => 'required|in:doctor,patient',
//     ]);

//     // Create User
//     $user = User::create([
//         'name' => $request->firstName . ' ' . $request->lastName,
//         'email' => $request->email,
//         'role' => $request->role,
//         'password' => Hash::make($request->password),
//     ]);

//     // Add to doctor or patient table (only with available fields)
//     if ($user->role === 'doctor') {
//         Doctor::create([
//             'firstname' => $request->firstName,
//             'lastname' => $request->lastName,
//             'user_id' => $user->id,
//         ]);
//     } elseif ($user->role === 'patient') {
//         Patient::create([
//             'firstName' => $request->firstName,
//             'lastName' => $request->lastName,
//             'user_id' => $user->id,
//             'email' => $request->email,
//         ]);
//     }

//     // Create token
//     $token = $user->createToken('authToken')->plainTextToken;

//     return response()->json([
//         'message' => 'Registration successful',
//         'token' => $token,
//         'user' => $user,
//     ], 201);
// }


    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         throw ValidationException::withMessages(['email' => ['Invalid credentials']]);
    //     }

    //     // Load role-specific data based on user role
    //     if ($user->role === 'doctor') {
    //         $user->load('doctor');
    //     } elseif ($user->role === 'patient') {
    //         $user->load('patient');
    //     }

    //     $token = $user->createToken('authToken')->plainTextToken;

    //     return response()->json(['token' => $token, 'user' => $user], 200);
    // }

    // public function logout(Request $request)
    // {
    //     $request->user()->tokens()->delete();
    //     return response()->json(['message' => 'Logged out successfully']);
    // }
}