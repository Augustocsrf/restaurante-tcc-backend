<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verifyUserCode(Request $request){
        $userExists = User::where([
            ['confirmation_token', $request->code],
            ['id', $request->user->id]
        ])->exists();

        if($userExists){
            $user = $userExists = User::where([
                ['confirmation_token', $request->code],
                ['id', $request->user->id]
            ])->first();

            $user->emaiL_verified_at = new Date();

            $user->save();

            return response()->json([
                "message" => "Email confirmado"
            ], 200);
        } else{
            return response()->json([
                "message" => "Código inválido"
            ], 404);
        }
    }
}
