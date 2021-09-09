<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Requests\User\Login as LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

    }

    public function loginToProfile(LoginRequest $request)
    {
        $user = User::where('phone_number', $request->phone_number)->first();
        //dd(auth()->user());
        if(!empty($user)){
            if(!$login = auth()->attempt([
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

        return response()->json([
            'user_name' => $user->name,
            'success' => $login
        ]);
    }
}
