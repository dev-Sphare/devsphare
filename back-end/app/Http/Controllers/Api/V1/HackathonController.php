<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Hackathon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HackathonController extends Controller
{
    // GET /api/v1/hackathons
    public function index()
    {
        $hackathons = Hackathon::with('organizer:id,name,email')
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($hackathons);
    }

    // GET /api/v1/hackathons/{slug}
    //A slug is a human-readable, URL-friendly identifier for a record.

    public function show($slug)
    {
        $hackathon = Hackathon::where('slug', $slug)
            ->with('organizer:id,name,email')
            ->firstOrFail();

        return response()->json($hackathon);
    }

    // POST /api/v1/hackathons
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'organizer' && $user->role !== 'admin') {
            return response()->json(['message' => 'Only organizers can create hackathons'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'is_paid' => 'boolean',
            'capacity' => 'nullable|integer|min:1'
        ]);

        $validated['organizer_id'] = $user->id;

        $hackathon = Hackathon::create($validated);

        return response()->json([
            'message' => 'Hackathon created successfully',
            'hackathon' => $hackathon
        ], 201);
    }

    // PATCH /api/v1/hackathons/{id}
    public function update(Request $request, Hackathon $hackathon)
    {
        $user = Auth::user();

        if ($user->id !== $hackathon->organizer_id && $user->role !== 'admin') {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'short_description' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'location' => 'sometimes|string|max:255',
            'start_at' => 'sometimes|date',
            'end_at' => 'sometimes|date|after:start_at',
            'status' => 'sometimes|in:draft,published,ongoing,ended',
            'is_paid' => 'sometimes|boolean',
            'capacity' => 'sometimes|integer|min:1'
        ]);

        $hackathon->update($validated);

        return response()->json([
            'message' => 'Hackathon updated',
            'hackathon' => $hackathon
        ]);
    }

    // DELETE /api/v1/hackathons/{id}
    public function destroy(Hackathon $hackathon)
    {
        $user = Auth::user();

        if ($user->id !== $hackathon->organizer_id && $user->role !== 'admin') {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        $hackathon->delete();

        return response()->json(['message' => 'Hackathon deleted']);
    }
}
