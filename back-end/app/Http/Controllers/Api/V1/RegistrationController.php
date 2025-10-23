<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Hackathon;
use App\Models\Registration;

class RegistrationController extends Controller
{
    // POST /api/v1/hackathons/{id}/register
    public function store($id)
    {
        $user = Auth::user();
        $hackathon = Hackathon::findOrFail($id);

        // 1. check duplicate
        if (Registration::where('user_id', $user->id)
                        ->where('hackathon_id', $hackathon->id)
                        ->exists()) {
            return response()->json(['message' => 'Already registered'], 409);
        }

        // 2. check if hackathon open
        if ($hackathon->status === 'ended') {
            return response()->json(['message' => 'Hackathon already ended'], 400);
        }

        // 3. capacity check
        if ($hackathon->capacity &&
            $hackathon->registrations()->count() >= $hackathon->capacity) {
            return response()->json(['message' => 'Hackathon is full'], 400);
        }

        // 4. payment check placeholder
        if ($hackathon->is_paid) {
            return response()->json(['message' => 'Payment required'], 402);
        }

        // 5. create registration
        $registration = Registration::create([
            'user_id' => $user->id,
            'hackathon_id' => $hackathon->id,
            'status' => 'registered',
        ]);

        return response()->json([
            'message' => 'Registration successful',
            'registration' => $registration
        ], 201);
    }

     
    // GET /api/v1/hackathons/{id}/registrations
    // Organizer c participants

    public function index($id)
    {
        $user = Auth::user();
        $hackathon = Hackathon::findOrFail($id);
       
        if($user->id !== $hackathon->organizer_id && $user->role !== 'admin') {
            return response()->json(['mesage' =>'not autorizedx'] , 403);
        }
        $registrations = $hackathon->registrations()->with('user')->get();

        return response()->json($registrations);
    }
        
    
}