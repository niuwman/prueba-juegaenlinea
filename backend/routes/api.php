<?php

use App\Http\Controllers\Api\AppointmentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

//Prueba
Route::post('/test-post', function (Request $request) {
    return response()->json([
        'message' => 'POST funciona correctamente',
        'data' => $request->all(),
        'method' => $request->method(),
    ]);
});

// Prueba
Route::post('/test-validation', function (Request $request) {
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
    ]);
    
    return response()->json([
        'message' => 'Validación pasó correctamente',
        'data' => $request->all(),
    ]);
});

Route::prefix('appointments')->group(function () {
    Route::get('/availability', [AppointmentController::class, 'index']);
    Route::post('/', [AppointmentController::class, 'store']);
});