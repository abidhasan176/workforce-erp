<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Health check route matching shared api-client expectations
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'workforce-erp-api',
    ]);
});

// Version 1 of the Workforce API
Route::prefix('v1')->group(function () {
    // Dummy CRUD operations for items using UsersController
    Route::get('/items', [UsersController::class, 'index']);
    Route::get('/items/{id}', [UsersController::class, 'show']);
    Route::post('/items', [UsersController::class, 'store']);
    Route::put('/items/{id}', [UsersController::class, 'update']);
    Route::patch('/items/{id}', [UsersController::class, 'patch']);
    Route::delete('/items/{id}', [UsersController::class, 'destroy']);
});

if (app()->environment('testing', 'local')) {
    Route::prefix('v1')->group(function () {
        Route::get('/test-errors/{type}', function ($type) {
            switch ($type) {
                case '401':
                    throw new \Illuminate\Auth\AuthenticationException;
                case '403':
                    throw new \Illuminate\Auth\Access\AuthorizationException;
                case '409':
                    throw new \Symfony\Component\HttpKernel\Exception\ConflictHttpException('Conflict occurred.');
                case '500':
                    throw new \Exception('Fatal database crash.');
            }

            return response()->json(['message' => 'Ok']);
        });
    });
}
