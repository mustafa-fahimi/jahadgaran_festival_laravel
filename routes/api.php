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

Route::get('/register', [RegisterController::class, 'register']);

Route::get('/atlas-code', [RegisterController::class, 'getAtlasCode']);

Route::post('/submitted-work', [GroupSubmittedWorkController::class, 'store']);
