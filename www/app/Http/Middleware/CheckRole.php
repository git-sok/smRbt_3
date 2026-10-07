<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Проверяем токен из заголовка Bearer
        $token = $request->bearerToken();
        $savedToken = session('auth_token');
        $userId = session('auth_user_id');

        if (!$token || $token !== $savedToken || !$userId) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($user->role === 'admin') {
            return $next($request);
        }

        if (in_array($user->role, $roles)) {
            if ($user->role === 'warden' && !$request->isMethod('GET')) {
                return response()->json(['message' => 'Forbidden. Your role only allows viewing data.'], 403);
            }
            return $next($request);
        }

        return response()->json(['message' => 'Forbidden. Access denied.'], 403);
    }
}
