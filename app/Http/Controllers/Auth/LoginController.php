<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Mostra a tela de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Processa o envio do formulário
    public function login(Request $request)
    {
        // 1. Validação básica
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Tenta autenticar (o 'remember' é opcional)
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Gera uma nova sessão por segurança
            $request->session()->regenerate();

            // Redireciona para o dashboard se for admin, senão para home
            if (Auth::user()->is_admin) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended('/');
        }

        // 3. Se falhar, volta com erro
        return back()->withErrors([
            'email' => 'As credenciais informadas não coincidem com nossos registros.',
        ])->onlyInput('email'); // Mantém o e-mail preenchido
    }

    // Faz o logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}