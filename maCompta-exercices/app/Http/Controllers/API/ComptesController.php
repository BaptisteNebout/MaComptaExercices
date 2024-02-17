<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ComptesController extends Controller
{

    public function getComptes()
    {
        $comptes = DB::table('comptes')->get();

        return response()->json($comptes);
    }

    public function getCompte($uuid)
    {
        $compte = DB::table('comptes')->where('uuid', $uuid)->first();

        return response()->json($compte, 200);
    }

    public function postCompte(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|max:255',
            'password' => 'required|string',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $uuid = Str::uuid();

        DB::table('comptes')->insert([
            'uuid' => $uuid,
            'login' => $request->input('login'),
            'password' => $request->input('password'),
            'name' => $request->input('name'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return response()->json(['uuid' => $uuid], 201);
    }

    public function putCompte(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $compte = DB::table('comptes')->where('uuid', $uuid)->first();
        if (!$compte) {
            return response()->json(['message' => 'Compte non trouvé.'], 404);
        }

        DB::table('comptes')
            ->where('uuid', $uuid)
            ->update([
                'password' => $request->input('password'),
                'name' => $request->input('name'),
                'updated_at' => Carbon::now(),
            ]);

        return response()->json($compte, 204);
    }

    public function deleteCompte($uuid)
    {
        $count = DB::table('ecritures')->where('compte_uuid', $uuid)->count();

        if ($count > 0) {
            return response()->json(['message' => 'Ce compte a des écritures associées et ne peut pas être supprimé.'], 400);
        }

        $compte = DB::table('comptes')->where('uuid', $uuid)->first();
        if (!$compte) {
            return response()->json(['message' => 'Compte non trouvé.'], 404);
        }

        DB::table('comptes')->where('uuid', $uuid)->delete();

        return response()->json(null, 204);
    }

}