<?php

use App\Http\Controllers\Api\CalendlyIntegrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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


Route::get('get-user',[CalendlyIntegrationController::class,'getUser']);
Route::get('get-event-types',[CalendlyIntegrationController::class,'getEventTypes']);
Route::get('get-scheduled-events',[CalendlyIntegrationController::class,'getScheduledEvents']);
//Route::post('scheduled-events',[CalendlyIntegrationController::class,'scheduleEvent']);
