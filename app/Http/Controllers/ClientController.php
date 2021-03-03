<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    //Método para realizar a atualização de um cliente
    public function update(Request $request, $id){
        if (User::where('id', $id)->exists()) {
            $client = User::find($id);

            // Verificar se o email informado já existe no banco, se sim, retornar erro
            if(!is_null($request->email)){
                if(User::where([['email', $request->email], ['id', '!=', $id]])->exists()){
                    return response()->json([
                        "message" => "Erro ao realizar atualização. Email já existe."
                    ], 409);
                } else {
                    $client->email = is_null($request->email) ? $client->email : $request->email;
                }
            }
            
            $client->name = is_null($request->name) ? $client->name : $request->name;
            $client->lastName = is_null($request->lastName) ? $client->lastName : $request->lastName;
            $client->phone = is_null($request->phone) ? $client->phone : $request->phone;

            $client->save();

            return response()->json([
                "message" => "Atualizado com sucesso",
                "client" => $client,
            ], 200);
        } else {
            return response()->json([
                "message" => "Erro ocorrido durante atualização"
            ], 404);
        }
    }

    //Método para atualizar a senha do cliente
    public function updatePassword(Request $request, $id){
        if (User::where('id', $id)->exists()) {
            $client = User::find($id);

            $client->password = Hash::make($request->password);

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
