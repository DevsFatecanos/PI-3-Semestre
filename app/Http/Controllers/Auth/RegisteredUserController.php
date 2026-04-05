<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    // 1. Exibe a tela de cadastro
    public function create()
    {
        return view('auth.register');
    }

    // 2. Processa o formulário
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Criação do usuário no banco
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Se você adicionou 'documento' na migration de users, salve aqui também:
            // 'documento' => $request->documento, 
        ]);

        // Loga o usuário automaticamente após cadastrar
        Auth::login($user);

        // Redireciona para a página inicial ou catálogo
        return redirect('/')->with('success', 'Conta criada com sucesso!');
    }
}