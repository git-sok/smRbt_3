<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        // Список студентов со вложенным объектом комнаты (with('room'))
        return response()->json(Student::with('room')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        // room_id по умолчанию null
        return response()->json(Student::create($validated), 201);
    }

    public function settle(Request $request, $id)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $student = Student::findOrFail($id);
        $room = Room::withCount('students')->findOrFail($request->room_id);

        // Если студент заселяется в эту комнату впервые, проверяем лимит мест
        if ($student->room_id !== $room->id) {
            if ($room->students_count >= $room->capacity) {
                return response()->json([
                    'message' => 'В этой комнате больше нет свободных мест'
                ], 422); // Возвращаем 422 Unprocessable Entity по заданию
            }
        }

        $student->update(['room_id' => $room->id]);

        return response()->json([
            'message' => 'Студент успешно заселен/переселен.',
            'student' => $student->load('room')
        ]);
    }
}
