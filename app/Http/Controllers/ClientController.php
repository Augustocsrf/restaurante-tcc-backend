<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    //Método para realizar a atualização de um cliente
    public function update(Request $request, $id){
        if (User::where('id', $id)->exists()) {
            $client = User::find($id);

            $client->name = is_null($request->name) ? $client->name : $request->name;
            $client->lastName = is_null($request->lastName) ? $client->lastName : $request->lastName;
            $client->email = is_null($request->email) ? $client->email : $request->email;
            $client->phone = is_null($request->phone) ? $client->phone : $request->phone;

            $client->save();

            return response()->json([
                "message" => "Atualizado com sucesso"
            ], 200);
        } else {
            return response()->json([
                "message" => "Erro ocorrido durante atualização"
            ], 404);
        }
    }
}
