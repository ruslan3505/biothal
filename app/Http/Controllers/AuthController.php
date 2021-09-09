<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\Login as LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PhoneVerify;
use App\Http\Requests\User\Verify as VerifyRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'verifyUser']]);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where([
            'phone_number' => $request->phone_number,
            'isVerified' => true
        ])->first();

        if(!empty($user)){
            if(!$token = auth()->attempt([
                'email' => $user->email,
                'password' => $request->password
            ])){
                return response()->json([
                    'message' => 'Проверьте ваши учетные данные',
                ], 413);
            }

        } else {
            return response()->json([
                'message' => 'Проверьте ваши учетные данные',
            ], 413);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function checkUser()
    {
        $user = auth()->user();
        $exist = false;
        if(!empty($user)){
            $exist = true;
        }

        return response()->json([
            'exist' => $exist
        ]);
    }

    public function verifyUser(VerifyRequest $request)
    {
        $verify = PhoneVerify::where([
            'phone_number' => $request->phone_number,
            'code' => $request->code,
        ])->orderBy('created_at', 'DESC')->first();

        if(!empty($verify)){
            $user = User::where('phone_number' , $request->phone_number)->first();
            $user->isVerified = true;
            $user->save();

            $verify->delete();
            Mail::to($user->email)->send(new UserCreated($user));
            return response()->json([
                'message' => 'Аккаунт успешно подтвержден',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Проверьте ваши учетные данные',
            ], 413);
        }
    }
}
