<?php

use App\Http\Controllers\Api\V1\AuthorTicketController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\AuthorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('authors', AuthorController::class);

    Route::apiResource('tickets', TicketController::class)->except(['update']);

    Route::put('tickets/{ticket}', [TicketController::class, 'replace']);
    Route::patch('tickets/{ticket}', [TicketController::class, 'update']);

    Route::apiResource('authors.tickets', AuthorTicketController::class)->except(['update']);

    Route::put('authors/{author}/tickets/{ticket}', [AuthorTicketController::class, 'replace']);
    Route::patch('authors/{author}/tickets/{ticket}', [AuthorTicketController::class, 'update']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

