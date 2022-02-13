<?php

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
    Route::get('/', [ReportController::class, 'get']);
    Route::get('{id}', [ReportController::class, 'getById']);
    Route::post('/', [ReportController::class, 'create']);
    Route::delete('{id}', [ReportController::class, 'delete']);
    Route::patch('{id}', [ReportController::class, 'updateReport']);

    Route::get('person/{id}', [ReportController::class, 'getByPersonId']);
});

Route::prefix('people')->group(function () {
    Route::get('/', [PersonController::class, 'getAll']);
    Route::get('/search', [PersonController::class, 'search']);
    Route::get('{id}', [PersonController::class, 'getById']);
    Route::post('/', [PersonController::class, 'create']);
    Route::delete('{id}', [PersonController::class, 'delete']);
    Route::put('{id}', [PersonController::class, 'update']);
});
