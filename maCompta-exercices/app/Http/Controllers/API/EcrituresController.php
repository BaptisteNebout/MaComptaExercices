<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ecriture;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\DataObject\EcritureDTO;

class EcrituresController extends Controller
{
    /*
    |-------------------------------------------------------------------------------
    | Get An Individual Compte
    |-------------------------------------------------------------------------------
    | URL:            /compte/{uuid}/ecritures
    | Method:         GET
    | Description:    récupère un compte individuel
    | Parameters:
    |   $uuid   -> UUID du compte
    */
    public function getCompteEcritures( $uuid ){
        $ecritures = Ecriture::where('compte_uuid', $uuid)->get();

        if ($ecritures->isEmpty()) {
        return response()->json(['message' => 'Aucune écriture trouvée pour ce compte.'], 404);
        }

        $ecritureformatted = [];
        foreach ($ecritures as $ecriture) {
            $dto = new EcritureDTO(
                $ecriture->uuid,
                $ecriture->compte_uuid,
                $ecriture->label,
                $ecriture->date,
                $ecriture->type,
                $ecriture->amount,
                $ecriture->created_at,
                $ecriture->updated_at
            );
            $ecritureformatted[] = $dto;
        }

        return response()->json(['items' => $ecritureformatted], 200);
    }
}