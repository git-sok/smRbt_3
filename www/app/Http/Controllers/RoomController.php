<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        // Выводим все комнаты с количеством фактически заселенных студентов через withCount
        return response()->json(Room::withCount('students')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number',
            'floor' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
        ]);

        return response()->json(Room::create($validated), 201);
    }

    public function destroy($id)
    {
        Room::findOrFail($id)->delete();
        return response()->json(['message' => 'Комната успешно удалена.']);
    }
}
