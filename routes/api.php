<?php

use App\Http\Controllers\SubmittedWorksController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Controller;
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

Route::get('/registerJahadiGroup', [LoginController::class, 'registerJahadiGroup']);
Route::get('/registerIndividual', [LoginController::class, 'registerIndividual']);
Route::get('/registerGroup', [LoginController::class, 'registerGroup']);

Route::get('/atlasCode', [Controller::class, 'getAtlasCode']);
Route::get('/submittedWorks', [Controller::class, 'getSubmittedWorks']);
Route::get('/download/{filename}', [Controller::class, 'download']);

Route::post(
  '/jahadiGroupSubmittedWork',
  [SubmittedWorksController::class, 'jahadiGroupSubmittedWork'],
);
Route::post(
  '/individualSubmittedWork',
  [SubmittedWorksController::class, 'individualSubmittedWork'],
);
Route::post(
  '/groupSubmittedWork',
  [SubmittedWorksController::class, 'groupSubmittedWork'],
);

Route::get('/requests-count', [Controller::class, 'countRequests']);
