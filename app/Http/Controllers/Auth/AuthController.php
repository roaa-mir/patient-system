<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:doctor,patient',
            'specialitie_id' => 'required_if:role,doctor|exists:specialities,id',
        ]);


        // Create user
        $user = User::create([
            'name' => $request->firstname . ' ' . $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Create doctor or patient profile
        if ($request->role === 'doctor') {
            Doctor::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'user_id' => $user->id,
                'email' => $request->email,
                'specialitie_id' => $request->specialitie_id,
                
            ]);
        } elseif ($request->role === 'patient') {
            Patient::create([
                'firstName' => $request->firstname,
                'lastName' => $request->lastname,
                'user_id' => $user->id,
                'email' => $request->email,
            ]);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Registration successful',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    // Logout (requires authentication)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
