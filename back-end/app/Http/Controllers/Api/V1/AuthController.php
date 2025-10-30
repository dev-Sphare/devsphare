<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    // Register 
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'in:participant,organizer,admin'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'participant'
        ]);

        Auth::login($user); // auto login after registration

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user
        ], 201);
    }

    // Login existing usr
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // if (!Auth::attempt($credentials)) {
        //     throw ValidationException::withMessages([
        //         'email' => ['Invalid credentials provided.'],
        //     ]);
        // }


    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

        // $request->session()->regenerate();

        // return response()->json([
        //     'message' => 'Login successful',
        //     'user' => Auth::user(),
        // ], 200);


        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ], 200);
 //sessionproblem
    }

    // Getin authenticated user
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    //  Logout 
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
