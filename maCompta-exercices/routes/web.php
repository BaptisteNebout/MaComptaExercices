<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ComptesController;
use App\Http\Controllers\API\EcrituresController;

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
//Compte
Route::get('/comptes', [ComptesController::class, 'getComptes']);

//Ecriture
Route::get('/comptes/{uuid}/ecritures', [EcrituresController::class, 'getCompteEcritures']);
Route::post('/comptes/{uuid}/ecritures', [EcrituresController::class, 'ajouterEcriture']);
Route::put('/comptes/{compte_uuid}/ecritures/{ecriture_uuid}', [EcrituresController::class, 'updateEcriture']);
Route::delete('/comptes/{compte_uuid}/ecritures/{ecriture_uuid}', [EcrituresController::class, 'deleteEcriture']);
