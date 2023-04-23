<?php

use App\Http\Controllers\GroupDataController;
use App\Http\Controllers\GroupSubmittedWorkController;
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

Route::get('/group-data', [GroupDataController::class, 'index']);

Route::get('/atlas-code', [GroupDataController::class, 'getAtlasCode']);

Route::post('/submitted-work', [GroupSubmittedWorkController::class, 'store']);
