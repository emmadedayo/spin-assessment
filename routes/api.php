<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('sign-up', [App\Http\Controllers\Api\AuthController::class, 'createUser']);

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'task'], function () {
    Route::post('create', [App\Http\Controllers\Api\TaskController::class, 'createTask']);
    Route::get('get', [App\Http\Controllers\Api\TaskController::class, 'getTasks']);
    Route::get('get/{id}', [App\Http\Controllers\Api\TaskController::class, 'getTask']);
    Route::put('update/{id}', [App\Http\Controllers\Api\TaskController::class, 'updateTask']);
    Route::delete('delete/{id}', [App\Http\Controllers\Api\TaskController::class, 'deleteTask']);
});
