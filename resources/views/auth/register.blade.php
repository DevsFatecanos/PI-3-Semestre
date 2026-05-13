<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta | Distribuidora Foccus</title>
    <link rel="preload" href="{{ asset('LOGO_FOCCUS.webp') }}" as="image" type="image/webp">
    <link rel="preload" href="{{ asset('LOGO_FOCCUS.png') }}" as="image">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-4xl w-full bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row">
        
        <div class="md:w-1/3 bg-slate-600 p-8 text-white flex flex-col justify-center">
            <picture>
                <source srcset="/LOGO_FOCCUS.webp" type="image/webp">
                <img src="/LOGO_FOCCUS.png" class="w-40 mb-8 brightness-0 invert" alt="Logo Foccus" decoding="async">
            </picture>
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

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700 text-sm rounded">
            Verifique os campos destacados abaixo.
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST" class="space-y-4">
        @csrf
        
        <div>
            <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Nome Completo</label>
            <input type="text" name="name" value="{{ old('name') }}" required 
                   class="w-full border-gray-200 border rounded-lg px-4 py-2.5 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 outline-none transition @error('name') border-red-500 @enderror">
            @error('name')
                <span class="text-red-500 text-[10px] font-bold uppercase mt-1">O campo nome é obrigatório.</span>
            @enderror
        </div>
        
        <div>
            <label class="block text-xs font-bold uppercase text-gray-400 mb-1">E-Mail</label>
            <input type="email" name="email" value="{{ old('email') }}" required 
                   class="w-full border-gray-200 border rounded-lg px-4 py-2.5 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 outline-none transition @error('email') border-red-500 @enderror" 
                   placeholder="email@dominio.com">
            @error('email')
                <span class="text-red-500 text-[10px] font-bold uppercase mt-1">Este e-mail já está cadastrado ou é inválido.</span>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Senha</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                           class="w-full border-gray-200 border rounded-lg px-4 py-2.5 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 outline-none transition @error('password') border-red-500 @enderror">
                    <button type="button" onclick="togglePassword('password', 'eyeIcon1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye" id="eyeIcon1"></i>
                    </button>
                </div>
                @error('password')
                    <span class="text-red-500 text-[10px] font-bold uppercase mt-1">A senha deve ter pelo menos 8 caracteres e ser confirmada.</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Confirmar Senha</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full border-gray-200 border rounded-lg px-4 py-2.5 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 outline-none transition">
                    <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye" id="eyeIcon2"></i>
                    </button>
                </div>
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

<script>
    function togglePassword(fieldId, iconId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

</body>
</html>