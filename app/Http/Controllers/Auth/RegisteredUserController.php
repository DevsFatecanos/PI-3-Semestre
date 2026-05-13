<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validação com mensagens personalizadas
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ], [
            'name.required'      => 'O campo nome é obrigatório.',
            'name.string'        => 'O nome deve ser um texto válido.',
            'name.max'           => 'O nome não pode ter mais de 255 caracteres.',
            'email.required'     => 'O campo e-mail é obrigatório.',
            'email.string'       => 'O e-mail deve ser um texto válido.',
            'email.email'        => 'Digite um e-mail válido.',
            'email.max'          => 'O e-mail não pode ter mais de 255 caracteres.',
            'email.unique'       => 'Este e-mail já está cadastrado.',
            'password.required'  => 'O campo senha é obrigatório.',
            'password.string'    => 'A senha deve ser um texto válido.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'password.min'       => 'A senha deve ter pelo menos 8 caracteres.',
        ]);

        // 2. Criar usuário
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Logar automaticamente
        Auth::login($user);

        // 4. Redirecionar
        return redirect('/')->with('success', 'Cadastro realizado com sucesso!');
    }
}