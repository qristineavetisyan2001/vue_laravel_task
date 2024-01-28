<?php

use App\Http\Controllers\TaskController;
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

Route::group(['prefix' => 'tasks'], function () {
    Route::get("/", [TaskController::class, 'getTasks']);
    Route::post("/", [TaskController::class, 'addTask']);
    Route::put("/{id}", [TaskController::class, 'editTask']);
    Route::put("/edit-task-status/{id}", [TaskController::class, 'editTaskStatus']);
    Route::delete("/{id}", [TaskController::class, 'deleteTask']);
});
