<?php

use App\Http\Controllers\API\ComptesController;
use App\Http\Controllers\API\EcrituresController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Compte
Route::get('/comptes', [ComptesController::class, 'getComptes']);
Route::get('/comptes/{uuid}', [ComptesController::class, 'getCompte']);
Route::post('/comptes', [ComptesController::class, 'postCompte']);
Route::put('/comptes/{uuid}', [ComptesController::class, 'putCompte']);
Route::delete('/comptes/{uuid}', [ComptesController::class, 'deleteCompte']);

// Ecriture
Route::get('/comptes/{uuid}/ecritures', [EcrituresController::class, 'getEcritures']);
Route::post('/comptes/{uuid}/ecritures', [EcrituresController::class, 'postEcriture']);
Route::put('/comptes/{compte_uuid}/ecritures/{ecriture_uuid}', [EcrituresController::class, 'putEcriture']);
Route::delete('/comptes/{compte_uuid}/ecritures/{ecriture_uuid}', [EcrituresController::class, 'deleteEcriture']);
