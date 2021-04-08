<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    //Método para obter os endereços de um usuário
    public function findByUser($id)
    {
        $addresses = Address::where('client_id', $id)->get();

        return response()->json($addresses, 200);
    }

    //Método para criar um novo endereço
    public function create(Request $request) {
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
    public function delete($id) {
        //Verificar se existe um pedido em aberto com esse endereço. Se sim, impedir o processo de deletar
        $isInUse = DB::table('orders')
        ->join('order_statuses', 'orders.order_status_id', '=', 'order_statuses.id')
        ->where([['address_id', '=', $id], ['order_statuses.final', 0]])
        ->exists();

        if ($isInUse) {
            return response()->json([
                "message" => "Endereço está em uso em um pedido em aberto e não pode ser deletado até sua finalização."
            ], 403);
        }

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

    //Atualizar informações do Endereço
    public function update(Request $request, $id){
        if (Address::where('id', $id)->exists()) {
            $address = Address::find($id);

            $address->zip = is_null($request->zip) ? $address->zip : $request->zip;
            $address->street = is_null($request->street) ? $address->street : $request->street;
            $address->district = is_null($request->district) ? $address->district : $request->district;
            $address->city = is_null($request->city) ? $address->city : $request->city;
            $address->number = is_null($request->number) ? $address->number : $request->number;
            $address->complement = is_null($request->complement) ? $address->complement : $request->complement;
            $address->reference = is_null($request->reference) ? $address->reference : $request->reference;
            $address->identification = is_null($request->identification) ? $address->identification : $request->identification;

            $address->save();

            return response()->json([
                "message" => "Atualizado com sucesso"
            ], 200);
        } else {
            return response()->json([
                "message" => "Endereço não encontrado"
            ], 404);
        }
    }
}
