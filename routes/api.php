<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WidgetApiController;

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

Route::post('/widget/init', [WidgetApiController::class, 'init']);
Route::post('/widget/start-chat', [WidgetApiController::class, 'startChat']);
Route::post('/widget/send-message', [WidgetApiController::class, 'sendMessage']);
Route::post('/widget/send-file', [WidgetApiController::class, 'sendFile']);
Route::post('/widget/poll', [WidgetApiController::class, 'poll']);
