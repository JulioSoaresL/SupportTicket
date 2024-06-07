<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return $this->success('Authorized', 200, [
                'token' => $request->user()->createToken('accessToken')->plainTextToken
            ]);
        }

        return $this->error('Not Authorized', 401, []);
    }
}
