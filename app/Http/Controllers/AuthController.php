<?php

/*
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
//use Tymon\JWTAuth\Facades\JWTAuth;
//use JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
//use JWTAuth;
//use Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function authenticate(Request $request) {
        // Get only email and password from request
        $credentials = $request->only('email', 'password');
  
        // Get user by email
        $company = User::where('email', $credentials['email'])->first();
  
        // Validate Company
        if(!$company) {
          return response()->json([
            'error' => 'Invalid credentials'
          ], 401);
        }
        
        // Validate Password
        if (!Hash::check($credentials['password'], $company->password)) {
            return response()->json([
              'error' => 'Invalid credentials'
            ], 401);
        }

        /*return response()->json([ 
            "company" => $company,
            "check" => Hash::check($credentials['password'], $company->password)
        ]);
  
        // Generate Token
        $token = JWTAuth::fromUser($company);
        
        // Get expiration time
        $objectToken = JWTAuth::setToken($token);
        $expiration = JWTAuth::decode($objectToken->getToken())->get('exp');
  
        return response()->json([
          'company' => $company,
          'access_token' => $token,
          'token_type' => 'bearer',
          'expires_in' => JWTAuth::decode($objectToken->getToken())->get('exp')
        ]);
      }
}
*/
