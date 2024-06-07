<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 2|YnMbvLgccqFG97yU49O65JUTqbACFBKgj4YCKcvY
class AuthController extends Controller
{
    use HttpResponses;
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return $this->success('Authorized', 200, [
                'token' => $request->user()->createToken('accessToken')->plainTextToken
            ]);
        }

        return $this->success('Not Authorized', 403);
    }
}
