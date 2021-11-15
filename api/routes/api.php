<?php

use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'getAll']);
    Route::get('{id}', [ReportController::class, 'getById']);
    Route::post('/', [ReportController::class, 'create']);
    Route::delete('{id}', [ReportController::class, 'delete']);
    Route::patch('{id}', [ReportController::class, 'updateReport']);

    Route::get('participant/{id}', [ReportController::class, 'getByParticipantId']);
});

Route::prefix('participants')->group(function () {
    Route::get('/', [ParticipantController::class, 'getAll']);
    Route::get('{id}', [ParticipantController::class, 'getById']);
    Route::delete('{id}', [ParticipantController::class, 'delete']);
    Route::post('{id}', [ParticipantController::class, 'createByIdReport']);
    
    Route::get('report/{id}', [ParticipantController::class, 'getByIdReport']);
});

Route::prefix('people')->group(function () {
    Route::get('/', [PersonController::class, 'getAll']);
    Route::get('{id}', [PersonController::class, 'getById']);
    Route::post('/', [PersonController::class, 'create']);
    Route::delete('{id}', [PersonController::class, 'delete']);
    Route::put('{id}', [PersonController::class, 'update']);
});