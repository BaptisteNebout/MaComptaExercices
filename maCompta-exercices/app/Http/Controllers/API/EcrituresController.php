<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ecriture;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\DataObject\EcritureDTO;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class EcrituresController extends Controller
{
    public function getEcritures( $uuid )
    {
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

    public function postEcriture(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:255',
            'date' => ['required', 'date_format:d/m/Y', 'after_or_equal:' . now()->format('d/m/Y')],
            'type' => ['required', Rule::in(['C', 'D'])],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $compte = Compte::where('uuid', $uuid)->first();
        if (!$compte) {
            return response()->json(['message' => 'Compte non trouvé.'], 404);
        }

        $ecriture = new Ecriture();
        $ecriture->uuid = Str::uuid();
        $ecriture->label = $request->input('label');
        $ecriture->date = Carbon::createFromFormat('d/m/Y', $request->input('date'))->toDateString();
        $ecriture->type = $request->input('type');
        $ecriture->amount = $request->input('amount');
        $ecriture->compte_uuid = $compte->uuid;

        $ecriture->save();

        $ecritureDTO = new EcritureDTO(
            $ecriture->uuid,
            $ecriture->compte_uuid,
            $ecriture->label,
            $ecriture->date,
            $ecriture->type,
            $ecriture->amount,
            $ecriture->created_at,
            $ecriture->updated_at
        );

        return response()->json($ecritureDTO, 201);
    }

    public function putEcriture(Request $request, $compte_uuid, $ecriture_uuid)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:255',
            'date' => ['required', 'date_format:d/m/Y', 'after_or_equal:' . now()->format('d/m/Y')],
            'type' => ['required', Rule::in(['C', 'D'])],
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $ecriture = Ecriture::where('uuid', $ecriture_uuid)->first();
        if (!$ecriture) {
            return response()->json(['message' => 'Écriture non trouvée.'], 404);
        }

        $ecriture->label = $request->input('label');
        $ecriture->date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('date'))->toDateString();
        $ecriture->type = $request->input('type');
        $ecriture->amount = $request->input('amount');

        $ecriture->save();

        $ecritureDTO = new EcritureDTO(
            $ecriture->uuid,
            $ecriture->compte_uuid,
            $ecriture->label,
            $ecriture->date,
            $ecriture->type,
            $ecriture->amount,
            $ecriture->created_at,
            $ecriture->updated_at
        );

        return response()->json($ecritureDTO, 204);
    }


    public function deleteEcriture($compte_uuid, $ecriture_uuid)
    {

        $ecriture = Ecriture::where('compte_uuid', $compte_uuid)->where('uuid', $ecriture_uuid)->first();

        $compte = Compte::where('uuid', $compte_uuid)->first();
        if (!$compte) {
            return response()->json(['message' => 'Compte non trouvé.'], 404);
        }

        if (!$ecriture) {
            return response()->json(['message' => 'Écriture non trouvée.'], 404);
        }

        $ecriture->delete();

        return response()->json(null, 204);
    }
}