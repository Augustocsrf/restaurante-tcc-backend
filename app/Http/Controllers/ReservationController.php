<?php

namespace App\Http\Controllers;

use App\Reservation;
use App\ReservationStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(){
        $reservations = Reservation::get();
        return response($reservations, 200);
    }

    public function create(Request $request){
        //Verificar se uma reserva com o mesmo tempo, dia, e usuário já existe
        // Se sim, retornar um erro por conflito
        if(Reservation::where([['day', $request->day], ['time', $request->time], ['client_id', $request->clientId]])->exists()){
            return response()->json([
                "message" => "Reserva para este usuário, nessa hora e dia já existe"
            ], 409);
        }


        $reservation = new Reservation;

        $reservation->day = $request->day;
        $reservation->time = $request->time;
        $reservation->name = $request->name;
        $reservation->lastName = $request->lastName;
        $reservation->guests = $request->guests;
        $reservation->client_id = $request->clientId;

        $reservation->save();

        return response()->json([
            "message" => "Reserva criada"
        ], 201);
    }

    public function update(Request $request, $id){
        if (Reservation::where('id', $id)->exists()) {
            $reservation = Reservation::find($id);

            $reservation->reservation_status = is_null($request->status) ? $reservation->reservation_status : $request->status;

            $reservation->save();

            return response()->json([
                "message" => "Atualizado com sucesso"
            ], 200);
        } else {
            return response()->json([
                "message" => "Reserva não encontrada"
            ], 404);
        }
    }

    public function getBusyDays(){
        $reservations = Reservation::where('reservation_status', 1)
            ->select('day', 'time')
            ->groupBy('day')
            ->groupBy('time')
            ->orderBy('day')
            ->orderBy('time')
            ->havingRaw('count(*) >= 4')
            ->get();

        return response($reservations, 200);
    }

    public function getClientOpenReservations($id){
        $reservations = Reservation::where([['reservation_status', 1], ['client_id', $id]])
        ->join('reservation_statuses', 'reservations.reservation_status', '=', 'reservation_statuses.id')
        ->select('reservations.*', 'reservation_statuses.name as status_name')
        ->get();

        return response()->json($reservations, 200);
    }

    public function getOpenReservations(){
        $reservations = Reservation::where([
            ['reservation_status', 1]
        ])
        ->join('reservation_statuses', 'reservations.reservation_status', '=', 'reservation_statuses.id')
        ->select('reservations.*', 'reservation_statuses.name as status_name')
        ->orderBy('day', 'asc')
        ->orderBy('time', 'asc')
        ->get();

        return response()->json($reservations, 200);
    }

    public function getReservationStatuses(){
        $reservationStatuses = ReservationStatus::get();

        return response()->json($reservationStatuses, 200);
    }
}
