<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web', ['except' => ['login','index']]);
    }

    public function index()
    {
        if (auth('web')->check()){
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->except(['_token']);
        auth('web')->attempt($credentials, true);
        if(!empty(auth('web')->user())){
            if (auth('web')->user()->isAdmin()){
                return redirect()->route('admin.dashboard');
            } else {
                auth('web')->logout();
                return redirect()->back();
            }
        }
        auth('web')->logout();
        return Redirect::back()->with('error', 'Данные не верные');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
