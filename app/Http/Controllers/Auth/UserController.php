<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class UserController extends Controller
{
    use AuthenticatesUsers;

    public function checkUser()
    {
        $user = auth()->user();
        $login = false;

        if(!empty($user)){
            $login = true;
        }

        return response()->json([
            'login' => $login
        ]);
    }
}
