<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use Illuminate\Http\Request;
use App\Models\Ecriture;
use Illuminate\Support\Carbon;
use App\DataObject\CompteDTO;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ComptesController extends Controller
{

  public function getComptes()
  {
      $comptes = Compte::all();

      return response()->json( $comptes );
  }

  public function getCompte( $uuid )
  {
    $compte = Compte::where('uuid', '=', $uuid)->first();

    return response()->json( $compte );
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

      $compte = new Compte();
      $compte->uuid = Str::uuid();
      $compte->login = $request->input('login');
      $compte->password = $request->input('password');
      $compte->name = $request->input('name');
      $compte->save();

      $compteDTO = new CompteDTO(
          $compte->uuid,
          $compte->login,
          $compte->password,
          $compte->name,
          $compte->created_at,
          $compte->updated_at
      );

      return response()->json($compteDTO, 201);
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

      $compte = Compte::where('uuid', $uuid)->first();
      if (!$compte) {
          return response()->json(['message' => 'Compte non trouvé.'], 404);
      }

      $compte->password = $request->input('password');
      $compte->name = $request->input('name');
      $compte->updated_at = Carbon::now();
      $compte->save();

      $compteDTO = new CompteDTO(
          $compte->uuid,
          $compte->login,
          $compte->password,
          $compte->name,
          $compte->created_at,
          $compte->updated_at
      );

      return response()->json($compteDTO, 200);
  }

}