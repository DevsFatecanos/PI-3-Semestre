<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta | Distribuidora Foccus</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-4xl w-full bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row">
        
        <div class="md:w-1/3 bg-slate-500 p-8 text-white flex flex-col justify-center">
            <img src="{{ asset('LOGO_FOCCUS.png') }}" class="w-40 mb-8 brightness-0 invert" alt="Logo Foccus">
            <h2 class="text-2xl font-bold mb-4">Seja um cliente Foccus</h2>
            <ul class="space-y-4 text-sm text-slate-100">
                <li class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center bg-slate-400 w-5 h-5 rounded-full text-[10px] text-white">✓</span>
                    Preços exclusivos
                </li>
                <li class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center bg-slate-400 w-5 h-5 rounded-full text-[10px] text-white">✓</span>
                    Entrega rápida e segura
                </li>
                <li class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center bg-slate-400 w-5 h-5 rounded-full text-[10px] text-white">✓</span>
                    Suporte especializado 
                </li>
            </ul>
        </div>

        <div class="md:w-2/3 p-8 lg:p-12">
            <div class="mb-8">
                <h1 class="text-2xl font-black text-gray-800">Crie sua conta</h1>
                <p class="text-gray-500 text-sm">Preencha os dados abaixo para acessar o catálogo completo.</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Nome Completo / Nome Fantasia.</label>
                        <input type="text" name="name" required class="w-full border-gray-200 border rounded-lg px-4 py-2.5 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 outline-none transition">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 mb-1">E-mail</label>
                        <input type="email" name="email" required class="w-full border-gray-200 border rounded-lg px-4 py-2.5 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-1">CPF ou CNPJ</label>
                    <input type="text" name="documento" required class="w-full border-gray-200 border rounded-lg px-4 py-2.5 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="00.000.000/0001-00">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Senha</label>
                        <input type="password" name="password" required class="w-full border-gray-200 border rounded-lg px-4 py-2.5 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 outline-none transition">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Confirmar Senha</label>
                        <input type="password" name="password_confirmation" required class="w-full border-gray-200 border rounded-lg px-4 py-2.5 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 outline-none transition">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-slate-800 text-white py-3 rounded-lg font-bold hover:bg-slate-900 transition shadow-lg shadow-slate-200">
                        Finalizar Cadastro
                    </button>
                </div>

                <p class="text-center text-sm text-gray-500 mt-6">
                    Já possui uma conta? <a href="/login" class="text-slate-600 font-bold hover:underline">Faça login</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>