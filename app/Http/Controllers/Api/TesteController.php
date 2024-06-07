<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TesteController extends Controller
{
    use HttpResponses;

    public function index()
    {
        return User::all();
    }


    public function create(Request $request)
    {
        //
    }

    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:cliente,colaborador']
        ]);

        if ($validator->fails()) {
            return $this->error('Invalid data', 422, $validator->errors());
        }

        // Criação do novo usuário
        try {
            $user = User::create([
                'name' => $request->name,
                'cpf' => $request->cpf,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar usuário.'], 500);
        }

        return $this->success('User registered successfully!', 201, $user);
    }


    public function show($id)
    {
        return User::where('id', $id)->first();
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
