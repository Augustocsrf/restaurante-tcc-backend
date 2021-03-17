<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\RecoverPasswordCode;
use App\User;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function requestCode(Request $request){
        // Verificar se email informado está cadastrado
        $userExists = User::where([
                ['email', $request->email],
                ['google_id', null],
            ])
            ->exists();

        if($userExists){
            $user = User::where([
                ['email', $request->email],
                ['google_id', null],
            ])
            ->first();

            $u = uniqid();
            $code = substr(strtoupper($u),7);

            $headerFields = array(
                "From: noreply@restaurantetcc.com.br",
                "Content-Type: text/html;charset=utf-8"
            );

            mail(
                $user->email,
                "Recuperação de Senha",
                "Olá, " . $user->name . "<br>"
                . "Você requisitou uma recuperação de senha para o site RestauranteTCC."
                . "Para prosseguir com a alteração, utilize o código <strong>" . $code . "</strong>.",
                implode("\r\n", $headerFields)
            );

            $recoverPassword = RecoverPasswordCode::create([
                'code' => $code,
                'users_id' => $user->id,
            ]);

            return response()->json([
                "message" => "Requisição realizada com sucesso. Verifique o código recebida no seu email."
            ], 201);

        } else {
            return response()->json([
                "message" => "Email não existe no sistema"
            ], 404);
        }
    }

    public function verifyCode(Request $request) {
        $recoverCode = RecoverPasswordCode::where('code', $request->code)->exists();

        if($recoverCode){
            $recoverCode = RecoverPasswordCode::select('users_id', 'created_at')->where('code', $request->code)->first();

            return response()->json([
                "recoverCode" => $recoverCode,
                "message" => "Código válido"
            ], 200);
        } else {
            return response()->json([
                "message" => "Código inválido"
            ], 404);
        }
    }
}
