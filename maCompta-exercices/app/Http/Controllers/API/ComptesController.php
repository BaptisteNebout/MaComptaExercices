<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use Illuminate\Http\Request;

class ComptesController extends Controller
{
  /*
  |-------------------------------------------------------------------------------
  | Get All Compte
  |-------------------------------------------------------------------------------
  | URL:            /compte
  | Method:         GET
  | Description:    Récupère tous les comptes
  */
    public function getComptes(){
        $comptes = Compte::all();

        return response()->json( $comptes );
  }

  /*
  |-------------------------------------------------------------------------------
  | Get An Individual Compte
  |-------------------------------------------------------------------------------
  | URL:            /compte/{uuid}
  | Method:         GET
  | Description:    récupère un compte individuel
  | Parameters:
  |   $uuid   -> UUID du compte
  */
  public function getCompte( $uuid ){
    $compte = Compte::where('uuid', '=', $uuid)->first();

    return response()->json( $compte );
  }
}