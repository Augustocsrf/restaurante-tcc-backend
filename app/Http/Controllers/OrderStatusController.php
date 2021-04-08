<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderStatus;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    //Método para pegar os status possíveis de pedidos
    public function index(){
        $orderStatuses = OrderStatus::get();

        return response()->json($orderStatuses, 200);
    }

    //Método para deletar um status
    public function delete($id){
        if($id < 7) {
            return response()->json([
                "message" => "Os estados básicos do sistema não podem ser deletados"
            ], 409);
        }

        if (OrderStatus::where('id', $id)->exists()) {
            Order::where('order_status_id', $id)->update(['order_status_id' => 6]);

            $orderStatus = OrderStatus::find($id);
            $orderStatus->delete();

            return response()->json([
                "message" => "Registros deletados"
            ], 202);
        } else {
            return response()->json([
                "message" => "Estado não encontrado"
            ], 404);
        }
    }

    //Método para criar um status
    public function create(Request $request){
        if(OrderStatus::where('name', $request->name)->exists()){
            return response()->json([
                "message" => "Estado com esse nome já existe"
            ], 409);
        }

        $orderStatus = new OrderStatus;
        $orderStatus->name = $request->name;
        $orderStatus->final = 0;
        $orderStatus->save();

        return response()->json($orderStatus, 201);
    }

    //Atualizar informações do status
    public function update(Request $request, $id){
        if(OrderStatus::where('name', $request->name)->exists()){
            return response()->json([
                "message" => "Estado com esse nome já existe"
            ], 409);
        }

        if (OrderStatus::where('id', $id)->exists()) {
            $orderStatus = OrderStatus::find($id);
            $orderStatus->name = is_null($request->name) ? $orderStatus->name : $request->name;
            $orderStatus->final = is_null($request->final) ? $orderStatus->final : $request->final;
            $orderStatus->save();

            return response()->json([
                "message" => "Atualizado com sucesso"
            ], 200);
        } else {
            return response()->json([
                "message" => "Estado não encontrado"
            ], 404);
        }
    }
}
