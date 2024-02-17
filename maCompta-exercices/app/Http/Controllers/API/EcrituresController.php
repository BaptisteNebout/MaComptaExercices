<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EcrituresController extends Controller
{
    public function getEcritures($uuid)
    {
        $ecritures = DB::table('ecritures')->where('compte_uuid', $uuid)->get();

        if ($ecritures->isEmpty()) {
            return response()->json(['message' => 'Aucune écriture trouvée pour ce compte.'], 404);
        }

        return response()->json(['items' => $ecritures], 200);
    }

    public function postEcriture(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:255',
            'date' => ['required', 'date_format:d/m/Y', 'after_or_equal:'.now()->format('d/m/Y')],
            'type' => ['required', 'in:C,D'],
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $compte = DB::table('comptes')->where('uuid', $uuid)->first();
        if (!$compte) {
            return response()->json(['message' => 'Compte non trouvé.'], 404);
        }

        $uuid = Str::uuid();

        DB::table('ecritures')->insert([
            'uuid' => $uuid,
            'compte_uuid' => $compte->uuid,
            'label' => $request->input('label'),
            'date' => Carbon::createFromFormat('d/m/Y', $request->input('date'))->toDateString(),
            'type' => $request->input('type'),
            'amount' => $request->input('amount'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return response()->json(['uuid' => $uuid], 201);
    }

    public function putEcriture(Request $request, $compte_uuid, $ecriture_uuid)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:255',
            'date' => ['required', 'date_format:d/m/Y', 'after_or_equal:'.now()->format('d/m/Y')],
            'type' => ['required', 'in:C,D'],
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $compte = DB::table('comptes')->where('uuid', $compte_uuid)->first();
        if (!$compte) {
            return response()->json(['message' => 'Compte non trouvé.'], 404);
        }

        $ecriture = DB::table('ecritures')->where('uuid', $ecriture_uuid)->where('compte_uuid', $compte_uuid)->first();
        if (!$ecriture) {
            return response()->json(['message' => 'Écriture non trouvée dans ce compte.'], 404);
        }

        $ecriture = DB::table('ecritures')->where('uuid', $ecriture_uuid)->first();
        if (!$ecriture) {
            return response()->json(['message' => 'Écriture non trouvée.'], 404);
        }

        DB::table('ecritures')
            ->where('uuid', $ecriture_uuid)
            ->update([
                'label' => $request->input('label'),
                'date' => Carbon::createFromFormat('d/m/Y', $request->input('date'))->toDateString(),
                'type' => $request->input('type'),
                'amount' => $request->input('amount'),
                'updated_at' => Carbon::now(),
            ]);

        return response()->json(null, 204);
    }

    public function deleteEcriture($compte_uuid, $ecriture_uuid)
    {
        $ecriture = DB::table('ecritures')->where('compte_uuid', $compte_uuid)->where('uuid', $ecriture_uuid)->first();

        if (!$ecriture) {
            return response()->json(['message' => 'Écriture non trouvée.'], 404);
        }

        DB::table('ecritures')->where('compte_uuid', $compte_uuid)->where('uuid', $ecriture_uuid)->delete();

        return response()->json(null, 204);
    }
}
