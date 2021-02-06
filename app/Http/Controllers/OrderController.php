<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\Address;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //Método para criar um novo pedido
    public function create(Request $request){
        //Criar pedido
        $order = new Order();

        $order->price = $request->totalCost;
        $order->payment_method = $request->paymentMethod;
        $order->cash = $request->cash;
        $order->delivery_method = $request->deliveryMethod;
        $order->order_status_id = 1;
        $order->address_id = $request->deliveryAddressId;
        $order->client_id = $request->id;
        
        $order->save();

        
        //Criar lista de produtos pedidos
        foreach ($request->items as $item) {
            $orderItem = new OrderItem();

            $orderItem->comment = $item["comment"];
            $orderItem->name = $item["name"];
            $orderItem->price = $item["price"];
            $orderItem->quantity = $item["quantity"];
            
            $orderItem->item_id = $item["id"];
            $orderItem->order_id = $order->id;
            
            $orderItem->save();
        }
        
        

        return response()->json([
            "order" => $order,
            "message" => "Pedido realizado com sucesso"
        ], 201);
    }

    public function update(Request $request, $id){
        if (Order::where('id', $id)->exists()) {
            $order = Order::find($id);
            
            $order->order_status_id = is_null($request->status) ? $order->order_status_id : $request->status;
            
            $order->save();

            return response()->json([
                "message" => "Atualizado com sucesso"
            ], 200);
        } else {
            return response()->json([
                "message" => "Pedido não encontrado"
            ], 404);
        }
    }
    
    //Método para obter os pedidos em aberto de um cliente
    public function getClientOpenOrders($id)
    {
        $orders = Order::where([['order_status_id', "!=", 5], ['client_id', $id]])
        ->join('order_statuses', 'orders.order_status_id', '=', 'order_statuses.id')
        ->select('orders.*', 'order_statuses.name as status_name')
        ->get();
        
        foreach ($orders as $order) {
            $items = OrderItem::where('order_id', $order->id)->get();

            $order->items = $items;
            
            if($order->address_id != null){
                $address = Address::find($order->address_id);
                $order->address = $address;
            }
        }
        
        
        return response()->json($orders, 200);
    }

    //Método para obter todos os pedidos em aberto 
    public function getOpenOrders($id)
    {
        $orders = Order::where(['order_status_id', "!=", 5])
        ->join('order_statuses', 'orders.order_status_id', '=', 'order_statuses.id')
        ->select('orders.*', 'order_statuses.name as status_name')
        ->get();
        
        foreach ($orders as $order) {
            $items = OrderItem::where('order_id', $order->id)->get();

            $order->items = $items;
            
            if($order->address_id != null){
                $address = Address::find($order->address_id);
                $order->address = $address;
            }
        }
        
        
        return response()->json($orders, 200);
    }
}
