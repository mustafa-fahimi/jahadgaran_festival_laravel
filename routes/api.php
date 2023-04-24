<?php

use App\Http\Controllers\GroupSubmittedWorkController;
use App\Http\Controllers\RegisterController;
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

Route::get('/registerJahadiGroup', [RegisterController::class, 'registerJahadiGroup']);
Route::get('/registerIndividual', [RegisterController::class, 'registerIndividual']);
Route::get('/registerGroup', [RegisterController::class, 'registerGroup']);

Route::get('/atlasCode', [RegisterController::class, 'getAtlasCode']);

Route::post('/submittedWork', [GroupSubmittedWorkController::class, 'store']);
