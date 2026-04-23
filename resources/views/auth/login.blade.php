<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Distribuidora Foccus</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-4xl w-full bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:row-reverse md:flex-row">
        
        <div class="md:w-1/3 bg-slate-600 p-8 text-white flex flex-col justify-center text-center md:text-left">
            <img src="/LOGO_FOCCUS.png" class="w-32 mb-8 mx-auto md:mx-0 brightness-0 invert" alt="Logo Foccus">
            <h2 class="text-2xl font-bold mb-4">Bem-vindo de volta!</h2>
            <p class="text-slate-200 text-sm leading-relaxed">
                Acesse sua conta para conferir seus pedidos, preços exclusivos e o estoque atualizado em tempo real.
            </p>
        </div>

        <div class="md:w-2/3 p-8 lg:p-12">
            <div class="mb-8">
                <h1 class="text-2xl font-black text-gray-800">Acessar Portal</h1>
                <p class="text-gray-500 text-sm">Insira suas credenciais para continuar.</p>
            </div>

            @if ($errors->has('email'))
                <div class="mb-6 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs rounded flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <span>As credenciais informadas não coincidem com nossos registros.</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-1">E-Mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full border-gray-200 border rounded-lg px-4 py-3 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 outline-none transition @error('email') border-red-400 @enderror" 
                           placeholder="seu@email.com">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block text-xs font-bold uppercase text-gray-400">Senha</label>
                        <a href="#" class="text-[10px] text-slate-500 hover:underline">Esqueceu a senha?</a>
                    </div>
                    <input type="password" name="password" required 
                           class="w-full border-gray-200 border rounded-lg px-4 py-3 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 outline-none transition @error('email') border-red-400 @enderror">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-slate-600 focus:ring-slate-500">
                    <label for="remember" class="ml-2 text-sm text-gray-500 cursor-pointer">Lembrar de mim</label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-slate-800 text-white py-3 rounded-lg font-bold hover:bg-slate-900 transition shadow-lg shadow-slate-200">
                        Entrar no Portal
                    </button>
                </div>

                <p class="text-center text-sm text-gray-500 mt-8">
                    Ainda não é cliente? <a href="/register" class="text-slate-600 font-bold hover:underline">Crie sua conta aqui</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>