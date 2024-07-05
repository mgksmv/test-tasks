<?php

use App\Http\Controllers\API\V1\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('api/v1')->group(function () {
    Route::prefix('tasks')->group(function () {
        Route::post('/get-tasks', [TaskController::class, 'getTasks']);
        Route::post('/create', [TaskController::class, 'create']);
        Route::post('/{task}/update', [TaskController::class, 'update']);
        Route::post('/{task}/delete', [TaskController::class, 'delete']);
    });
});
