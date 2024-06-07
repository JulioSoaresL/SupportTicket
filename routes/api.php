<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Api\TesteController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [TesteController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users', [TesteController::class, 'index']);
        Route::get('/users/{id}', [TesteController::class, 'show']);

        Route::get('/tickets', [TicketController::class, 'index']);
        Route::get('/tickets/{ticketId}', [TicketController::class, 'index']);
        Route::post('/tickets', [TicketController::class, 'store']);
        Route::put('/tickets/{ticketId}', [TicketController::class, 'update']);
    });

});

// Route::post('/register', [RegisterController::class, 'register']);
