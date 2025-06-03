<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialitie;

class SpecialitieController extends Controller
{
    // GET /api/specialities
    public function index()
    {
        return response()->json(Specialitie::all());
    }

    // GET /api/specialities/{id}
    public function show($id)
    {
        $speciality = Specialitie::find($id);

        if (!$speciality) {
            return response()->json(['message' => 'Speciality not found'], 404);
        }

        return response()->json($speciality);
    }

    // POST /api/specialities
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $speciality = Specialitie::create([
            'title' => $request->title,
        ]);

        return response()->json($speciality, 201);
    }

    // PUT/PATCH /api/specialities/{id}
    public function update(Request $request, $id)
    {
        $speciality = Specialitie::find($id);

        if (!$speciality) {
            return response()->json(['message' => 'Speciality not found'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $speciality->title = $request->title;
        $speciality->save();

        return response()->json($speciality);
    }

    // DELETE /api/specialities/{id}
    public function destroy($id)
    {
        $speciality = Specialitie::find($id);

        if (!$speciality) {
            return response()->json(['message' => 'Speciality not found'], 404);
        }

        $speciality->delete();

        return response()->json(['message' => 'Speciality deleted successfully']);
    }
}
