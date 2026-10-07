<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StudentController;

// Публичный роут для логина
Route::post('/login', [AuthController::class, 'login']);

// Защищенные роуты (используют стандартные сессии)
Route::middleware('web')->group(function () {

    // Группа «Комнаты»
    Route::prefix('rooms')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->middleware('role:admin,warden');
        Route::post('/', [RoomController::class, 'store'])->middleware('role:admin');
        Route::delete('/{id}', [RoomController::class, 'destroy'])->middleware('role:admin');
    });

    // Группа «Студенты»
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->middleware('role:admin,warden');
        Route::post('/', [StudentController::class, 'store'])->middleware('role:admin');
        Route::put('/{id}/settle', [StudentController::class, 'settle'])->middleware('role:admin');
    });

});
