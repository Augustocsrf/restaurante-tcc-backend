<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $data)
    {
        //Verificar se email informado já existe
        if(User::where('email', $data->email)->exists()){
            return response()->json([ "message" => "Email já existe" ], 409);
        }

        // Criar código único para o token de confirmação do email
        $u = uniqid();
        $code = substr(strtoupper($u),7);

        $user = User::create([
            'name' => $data->name,
            'lastName' => $data->lastName,
            'email' => $data->email,
            'phone' => $data->phone,
            'permission' => $data->permission,
            'confirmation_token' => $code,
            'password' => Hash::make($data->password),
            'api_token' => Str::random(60),
        ]);

        //Enviar e-mail para informar usuário do seu código de confirmação
        $headerFields = array(
            "From: noreply@restaurantetcc.com.br",
            "Content-Type: text/html;charset=utf-8"
        );

        mail(
            $user->email,
            "Recuperação de Senha",
            "Olá, " . $user->name . "<br>"
            . "Você se cadastrou no site RestauranteTCC.<br>"
            . "Para confirmar esse email, utilize o código <strong>" . $code . "</strong>.",
            implode("\r\n", $headerFields)
        );

        return response()->json($user, 201);
    }

    //Método para criar um novo funcionário
    protected function createEmployee(Request $data)
    {
        //Verificar se o usuário com esse email já existe,
        // caso ele já exista, e o administrador não tenha dado permissão pra atualizar esse usuário, retornar erro
        // Caso haja essa permissão atualizar o usuário existente para ser um funcionário
        // Caso o email não exista, criar um novo funcionário
        if(User::where('email', $data->email)->exists()){
            if($data->allowUpdate){
                $user = User::where('email', $data->email)->first();

                $user->permission = 2;
                $user->save();

                return response()->json($user, 200);
            } else {
                return response()->json([
                    "message" => "Email já existe. Você gostaria de atualizar o usuário com esse email?",
                    "requestConfirmation" => true
                ], 204);
            }
        } else {
            $user = User::create([
                'name' => $data->name,
                'lastName' => $data->lastName,
                'email' => $data->email,
                'phone' => $data->phone,
                'permission' => 2,
                'password' => Hash::make($data->password),
                'api_token' => Str::random(60),
            ]);
        }

        return response()->json($user, 201);
    }
}
