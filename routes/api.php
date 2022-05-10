<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AssisCollectionController;
use App\Http\Controllers\AssisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\QuestionController;
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

Route::get('/dashboard', [DashboardController::class, 'dashboard']);

Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'index']);
    Route::get('{id}', [ReportController::class, 'show']);
    Route::post('/', [ReportController::class, 'store']);
    Route::delete('{id}', [ReportController::class, 'delete']);
    Route::put('{id}', [ReportController::class, 'update']);
});

Route::prefix('people')->group(function () {
    Route::get('/', [PersonController::class, 'index']);
    Route::get('/search', [PersonController::class, 'search']);
    Route::get('{id}', [PersonController::class, 'show']);
    Route::post('/', [PersonController::class, 'store']);
    Route::delete('{id}', [PersonController::class, 'delete']);
    Route::post('{id}', [PersonController::class, 'update']);
});

Route::prefix('questions')->group(function () {
    Route::get('/', [QuestionController::class, 'index']);
    Route::post('/', [QuestionController::class, 'store']);
    Route::post('/{id}/question', [QuestionController::class, 'changeQuestion']);
    Route::post('/{id}/type', [QuestionController::class, 'changeYes']);
    Route::delete('{id}/disable', [QuestionController::class, 'delete']);

    Route::prefix('/answer')->group(function () {
        Route::post('/reply', [AnswerController::class, 'reply']);
        Route::post('{id}', [AnswerController::class, 'change']);
        Route::post('/{id}/comment', [AnswerController::class, 'comment']);
    });
});

Route::prefix('assis')->group(function () {
    Route::get('/', [AssisController::class, 'index']);
    Route::get('/{id}', [AssisController::class, 'show']);
    Route::post('/change-status/{id}', [AssisController::class, 'changeStatus']);

    Route::prefix('/collection')->group(function () {
        Route::post('/', [AssisCollectionController::class, 'newCollection']);
        Route::get('/{id}', [AssisCollectionController::class, 'show']);
        Route::get('/{id}/collection', [AssisCollectionController::class, 'showCollection']);
        Route::post('/{id_collection}/add', [AssisCollectionController::class, 'addToCollection']);
    });

    Route::prefix('/episode')->group(function () {
        Route::post('/{id_assis}/add', [EpisodeController::class, 'addToEpisode']);
        Route::get('/date/{date}', [EpisodeController::class, 'getByDate']);
    });
});
