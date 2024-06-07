<?php

namespace App\Service;

use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserService
{
    use HttpResponses;
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById(int $id)
    {
        return User::findOrFail($id);
    }

    public function registerUser(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:cliente,colaborador']
        ]);

        if ($validator->fails()) {
            return $this->error('Invalid data', 400, $validator->errors());
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'cpf' => $request->cpf,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error to create User.'], 500);
        }

        return $this->success('User registered successfully!', 201, $user);
    }
}
