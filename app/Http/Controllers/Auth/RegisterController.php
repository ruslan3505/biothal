<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use App\Models\UserGroup;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use http\Env;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\User\Create as createUser;
use App\Models\EmailForEmailNewsletter;
use App\Models\PhoneVerify;
use App\Models\PhoneReceive;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
            'date' => ['date'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(createUser $request)
    {
        $user = User::create([
            'name' => $request->name,
            'sur_name' => $request->surname,
            'date_of_birth' => $request->date,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        $phone = $user->phone_number;
        $code = $this->generateRandomString(6);
        EmailForEmailNewsletter::create([
            'email' => $user->email,
            'is_receive' => $request->is_receive
        ]);

        PhoneReceive::create([
            'phone' => $user->phone_number,
            'is_receive' => true
        ]);


        PhoneVerify::create([
            'code' => $code,
            'phone_number' => $phone,
            'expired_in' => Carbon::now()->addMinute(30)
        ]);

        $this->sendCode($phone, $code);

        $token = $request->token;
        if(!empty($token)){
            $this->addUserToGroup($token, $user);
        }

        return response()->json([
            'message' => 'Вы успешно зарегистрировались',
        ], 201);
    }

    public  function generateRandomString($length = 20) {
        $characters = '0123456789abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public  function getNewVerifyCode(Request $request) {

        $code = $this->generateRandomString(6);

        PhoneVerify::where('phone_number', $request->phone_number)->update([
            'code' => $code,
            'expired_in' => Carbon::now()->addMinute(30)
        ]);

        $this->sendCode($request->phone_number, $code);

        return response()->json([
            'message' => 'Код отправлен повторно',
        ], 201);

    }

    public function sendCode($phone, $code)
    {

        $response = Http::get('https://api.turbosms.ua/message/send.json', [
            'recipients' => [
                $phone
            ],
            'sms' => [
                'sender' => 'Biothal',
                'text' => 'Ваш код подтверждения: '. $code,
            ],
            'token' => Env('TurboSmsToken')
        ]);
    }

    public function addUserToGroup($token, $user)
    {
        if(!empty($token)){
            if (!$invite = Invite::where('token', $token)->first()) {
                abort(404);
            }
            UserGroup::create([
                'user_id' => $invite->main_user,
                'attached_user_id' => $user->id,
                'active' => true
            ]);

            $invite->delete();
        }
    }
}
