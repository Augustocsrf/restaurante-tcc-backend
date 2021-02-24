<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\Reservation;
use App\User;
use Illuminate\Http\Request;

class DataReportController extends Controller
{
    //Obter quantidade de pedidos, organizado por mês/ano
    public function getOrderAmount(){
        $orderAmount = Order::selectRaw("
            DATE_FORMAT(created_at, '%Y/%m') as month,
            COUNT(*) AS data,
            COUNT(case when order_status_id = 5 then 1 else null end) AS cancelled
        ")
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->get();
        return response()->json($orderAmount, 200);
    }

    //Obter faturamento de pedidos, organizado por mês/ano
    public function getOrderRevenue(){
        $orderRevenue = Order::selectRaw("
            DATE_FORMAT(created_at, '%Y/%m') as month,
            count(*) data,
            SUM(price) as total,
            SUM(CASE WHEN order_status_id != 5 THEN price ELSE 0 END) totalPrice,
            SUM(CASE WHEN order_status_id = 5 THEN price ELSE 0 END) totalCancelled
        ")
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->get();

        return response()->json($orderRevenue, 200);
    }

    //Obter quantidade de reservas, organizada por mês/ano
    public function getReservationAmount(){
        $reservationAmount = Reservation::selectRaw("
            DATE_FORMAT(created_at, '%Y/%m') as month,
            COUNT(*) AS data,
            COUNT(case when reservation_status = 3 then 1 else null end) AS delays,
            COUNT(case when reservation_status = 5 then 1 else null end) AS cancelled
        ")
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->get();

        return response()->json($reservationAmount, 200);
    }

    //Obter items mais pedido de cada mês
    public function getItemsOrdered(){
        $itemAmount = OrderItem::selectRaw("
            DATE_FORMAT(created_at, '%Y/%m') as month,
            item_id,
            COUNT(*) AS data
        ")
                ->groupBy('item_id', 'month')
                ->orderBy('month', 'desc')
                ->get();

        return response()->json($itemAmount, 200);
    }

    //Obter quantidade de novos clientes
    public function getNewClients(){
        $newClients = User::selectRaw("
            DATE_FORMAT(created_at, '%Y/%m') as month,
            COUNT(case when permission = 1 then 1 else null end) AS clients
        ")
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->get();

        return response()->json($newClients, 200);
    }

    //Obter proporção de entregas e pick-ups
    public function getDeliveryProportions(){
        $delivery = Order::selectRaw("
            DATE_FORMAT(created_at, '%Y/%m') as month,
            ( COUNT(case when delivery_method = 2 then 1 else null end) * 100 / COUNT(*) ) AS pickUps,
            ( COUNT(case when delivery_method = 1 then 1 else null end) * 100 / COUNT(*) ) AS delivery
        ")
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->get();

        return response()->json($delivery, 200);
    }
}
