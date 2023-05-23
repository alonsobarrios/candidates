<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CandidateController;
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

Route::post('/auth', [AuthController::class, 'login']);

Route::middleware('jwt_auth')->group(function () {
    Route::post('/lead', [CandidateController::class, 'store'])->middleware('manager_role');
    Route::get('/lead/{id}', [CandidateController::class, 'show']);
    Route::get('/leads', [CandidateController::class, 'index']);
});
