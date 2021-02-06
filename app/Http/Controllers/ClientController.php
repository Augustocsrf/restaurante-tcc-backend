<?php

namespace App\Http\Controllers;

use App\Client;
use App\Category;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    //Método para realizar o login de um cliente
    public function login(Request $request)
    {
        $client = Client::where([
            ['email', $request->email],
            ['password', md5($request->password)]
        ])
            ->get();

        if (count($client) < 1) {
            return response(["message" => "Email ou senha incorretos"], 401);
        }

        return response($client[0]->toJson(JSON_PRETTY_PRINT), 200);
    }

     //Método para realizar cadastro de novos clientes
     public function registerClient(Request $request){
        $verifyClient = Client::where([ ['email', $request->email] ])->count();

        if($verifyClient > 0){
            return response()->json(["message" => "Email cadastrado já existe"], 409);
        }
        
        $client = new Client;
        
        $client->email = $request->email;
        $client->password = md5($request->passwordRegister);
        $client->name = $request->name;
        $client->lastName = $request->lastName;
        $client->phone = $request->phone;
        
        $client->save();
        
        return response()->json([
            "client" => $client,
            "message" => "Conta criada com sucesso"
        ], 201);
    }
}
