<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }
    
    public function login(Request $request){
        // Get only email and password from request
        $credentials = $request->only('email', 'password');
  
        // Get user by email
        $user = User::where('email', $credentials['email'])->first();
  
        // Validate Company
        if(!$user) {
          return response()->json([
            'error' => 'Invalid credentials'
          ], 401);
        }
        
        // Validate Password
        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json([
              'error' => 'Invalid credentials password'
            ], 401);
        }

        return response()->json($user);
    }

    public function clientLogin(Request $request){
        //Encontrar usuário com aquele email único na categoria cliente
        $userExists = User::where([
            ['email', $request->email], 
            ["permission", 1]
            ])
                    ->exists();
                
        if($userExists){
            $user = User::where([
                ['email', $request->email], 
                ["permission", 1]
                ])
                        ->first();

                
            if($user && Hash::check($request->password, $user->password)){
                return response()->json($user, 200);
            } else {
                return response()->json([
                    "message" => "Email ou senha incorretos"
                ], 404);
            }
            
        } else {
            return response()->json([
                "message" => "Email incorreto"
            ], 404);
        }
    }

    public function staffLogin(Request $request){
        //Fazer o hash da senha
        $password = Hash::make($request->password);
        $userExists = User::where([
            ['email', $request->email], 
            ["permission", '!=', 1]
            ])
                    ->exists();

        if($userExists){
            $user = User::where([
                ['email', $request->email], 
                ["permission", '!=', 1]
                ])
                        ->first();

                
            if($user && Hash::check($request->password, $user->password)){
                return response()->json($user, 200);
            } else {
                return response()->json([
                    "message" => "Email ou senha incorretos"
                ], 404);
            }
        } else {
            return response()->json([
                "message" => "Email incorreto ou não tem essa permissão"
            ], 404);
        }
    }

    //Método para realizar login com uma conta google
    public function googleLogin(Request $request){
        //Encontrar usuário com aquele email único
        $userExists = User::where('email', $request->email)->exists();
                
        //Caso o usuário exista, 
        if($userExists){
            $user = User::where('email', $request->email)->first();

                
            if($user && Hash::check($request->password, $user->password)){
                return response()->json($user, 200);
            } else {
                return response()->json([
                    "message" => "Email ou senha incorretos. Email pode já estar cadastrado sem login pela google."
                ], 404);
            }
            
        } else {
            //Caso o usuário não exista, criar um usuário com essas informações na categoria de cliente
            $user = User::create([
                'name' => $request->name,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'phone' => $request->phone,
                'permission' => 1,
                'password' => Hash::make($request->password),
                'api_token' => Str::random(60),
            ]);
    
            return response()->json($user, 201);
        }
    }
}