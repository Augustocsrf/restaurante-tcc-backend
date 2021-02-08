<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class StaffController extends Controller
{
    //Método para encontrar todos os funcionários
    public function index(){
        $staff = User::where('permission', 2)
        ->orderBy('name')
        ->orderBy('lastName')
        ->get();

        return response()->json($staff, 200);
    }

    //Método para deletar funcionário
    public function delete($id){
        if (User::where('id', $id)->exists()) {
            $staffer = User::find($id);
            $staffer->delete();

            return response()->json([
                "message" => "Funcionário deletado"
            ], 202);
        } else {
            return response()->json([
                "message" => "Funcionário não encontrado"
            ], 404);
        }
    }

    //
    public function update(Request $request, $id){
        if (User::where('id', $id)->exists()) {
            $staff = User::find($id);

            $staff->name = is_null($request->name) ? $staff->name : $request->name;
            $staff->lastName = is_null($request->lastName) ? $staff->lastName : $request->lastName;
            $staff->email = is_null($request->email) ? $staff->email : $request->email;
            $staff->phone = is_null($request->phone) ? $staff->phone : $request->phone;
            $staff->permission = is_null($request->permission) ? $staff->permission : $request->permission;

            $staff->save();

            return response()->json([
                "message" => "Atualizado com sucesso"
            ], 200);
        } else {
            return response()->json([
                "message" => "Reserva não encontrada"
            ], 404);
        }
    }
}
