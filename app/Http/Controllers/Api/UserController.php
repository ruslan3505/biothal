<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAuthUser()
    {
        $user = Auth::user();

        return response()->json($user === null ? null: $user);
    }
}
