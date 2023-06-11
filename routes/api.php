<?php

use App\Http\Controllers\SubmittedWorksController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CoreController;
use App\Http\Controllers\RefereeLoginController;
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

Route::get('/atlasCode', [CoreController::class, 'getAtlasCode']);
Route::get('/submittedWorks', [CoreController::class, 'getSubmittedWorks']);
Route::get('/download/{filename}', [CoreController::class, 'download']);

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

Route::group((['prefix' => 'referee']), function () {
  Route::post(
    '/otp',
    [RefereeLoginController::class, 'otp'],
  );
  Route::post(
    '/login',
    [RefereeLoginController::class, 'login'],
  );
});
