<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReplyController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function ()
{
    Route::post('/register', [UserController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function ()
    {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);

        Route::get('/tickets', [TicketController::class, 'index']);
        Route::get('/tickets/{ticketId}', [TicketController::class, 'show']);
        Route::post('/tickets', [TicketController::class, 'store']);
        Route::put('/tickets/{ticketId}', [TicketController::class, 'update']);

        Route::post('tickets/{ticketId}/replies', [ReplyController::class, 'store']);
    });
});
