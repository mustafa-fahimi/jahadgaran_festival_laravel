<?php

use App\Http\Controllers\SubmittedWorksController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CoreController;
use App\Http\Controllers\RefereeLoginController;
use App\Http\Controllers\RefereeController;
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

Route::get('/atlasCode', [CoreController::class, 'getAtlasCode']);
Route::get('/submittedWorks', [CoreController::class, 'getSubmittedWorks']);
Route::get('/download/{filename}', [CoreController::class, 'download']);

Route::group(([]), function () {
  Route::get('/registerJahadiGroup', [LoginController::class, 'registerJahadiGroup']);
  Route::get('/registerIndividual', [LoginController::class, 'registerIndividual']);
  Route::get('/registerGroup', [LoginController::class, 'registerGroup']);
});

Route::group(([]), function () {
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
  Route::delete(
    '/submittedWork/{id}',
    [SubmittedWorksController::class, 'deleteSubmittedWork'],
  );
});

Route::group((['prefix' => 'referee']), function () {
  Route::post(
    '/otp',
    [RefereeLoginController::class, 'otp'],
  );
  Route::post(
    '/login',
    [RefereeLoginController::class, 'login'],
  );
  Route::get(
    '/submittedWorks',
    [RefereeController::class, 'submittedWorks'],
  );
  Route::post(
    '/submitScore',
    [RefereeController::class, 'submitScore'],
  );
});
