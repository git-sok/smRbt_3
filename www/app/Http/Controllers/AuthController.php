<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Неверные учетные данные.'],
            ]);
        }

        // Генерируем обычную случайную строку вместо createToken()
        $token = Str::random(60);

        // Сохраняем этот токен в системную сессию, чтобы Middleware его увидело
        session(['auth_token' => $token, 'auth_user_id' => $user->id]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->role
        ]);
    }
}
