<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    //Método para obter os endereços de um usuário
    public function findByUser($id)
    {
        $addresses = Address::where('client_id', $id)->get();

        return response()->json($addresses, 200);
    }

    //Método para criar um novo endereço
    public function create(Request $request)
    {
        $address = new Address();

        $address->zip = $request->zip;
        $address->street = $request->street;
        $address->district = $request->district;
        $address->city = $request->city;
        $address->number = $request->number;
        $address->complement = $request->complement;
        $address->reference = $request->reference;
        $address->identification = $request->identification;
        $address->client_id = $request->id;

        $address->save();

        return response()->json([
            "message" => "Endereço criado com sucesso",
            "address" => $address
        ], 201);
    }


    //Método para deletar um endereço
    public function delete($id)
    {
        if (Address::where('id', $id)->exists()) {
            $address = Address::find($id);
            $address->delete();

            return response()->json([
                "message" => "Endereço deletados"
            ], 202);
        } else {
            return response()->json([
                "message" => "Endereço não encontrado"
            ], 404);
        }
    }
}
