<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trocar Senha</title>
    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/02669f3445.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.min.js" defer></script>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
</head>
<body class="bg-[#f4f4f4] flex items-center justify-center min-h-screen font-sans">

    <section class="main">
        <!-- Container Principal -->
        <div class="flex w-[950px] h-auto bg-white rounded-[20px] shadow-2xl overflow-hidden">
            
            <!-- Lado Esquerdo (Azul) -->
            <div class="relative w-[35%] bg-[#465367] p-[50px] text-white flex flex-col justify-center">
                <!-- Botão Home -->
                <a class="absolute top-10 left-10 text-white no-underline text-sm flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity"  href="/" >
                    <i class="fa-solid fa-angle-left"></i> Home
                </a>

                <div class="logo">
                    <img src="{{ asset('LOGO_FOCCUS.png') }}" class="w-40 brightness-0 invert" alt="Logo Foccus">
                </div>
                <h1 class="text-[28px] font-bold mt-[30px] mb-[15px] leading-tight">Segurança da Conta</h1>
                <p class="text-[14px] leading-[1.6] opacity-80">Altere sua senha para manter sua conta segura. Escolha uma senha forte e única.</p>
            </div>

            <!-- Lado Direito (Branco/Formulário) -->
            <div class="w-[65%] p-[60px] flex flex-col justify-center">
                <h2 class="text-[24px] font-bold text-[#1a1a1a]">Trocar Senha</h2>
                <p class="text-[13px] text-[#888] mb-[30px]">Insira sua senha atual e a nova senha desejada.</p>

                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <strong class="font-bold">Erro!</strong>
                    <ul class="mt-2 ml-4 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <strong class="font-bold">Sucesso!</strong>
                    <p>{{ session('success') }}</p>
                </div>
                @endif

                <form method="POST" action="{{ route('change-password.update') }}">
                    @csrf

                    <!-- Senha Atual -->
                    <div class="mb-[20px]">
                        <label class="block text-[11px] font-bold text-[#aaa] uppercase tracking-[1px] mb-[5px]">Senha Atual</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="current_password" 
                                id="current_password"
                                class="w-full p-[12px] pr-10 border border-[#e0e0e0] rounded-[8px] text-[14px] outline-none focus:border-[#465367] transition-colors"
                                placeholder="Digite sua senha atual"
                                required
                            >
                            <button type="button" onclick="togglePassword('current_password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 cursor-pointer">
                                <i class="fas fa-eye" id="current_password-icon"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nova Senha -->
                    <div class="mb-[20px]">
                        <label class="block text-[11px] font-bold text-[#aaa] uppercase tracking-[1px] mb-[5px]">Nova Senha</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                class="w-full p-[12px] pr-10 border border-[#e0e0e0] rounded-[8px] text-[14px] outline-none focus:border-[#465367] transition-colors"
                                placeholder="Digite uma nova senha (mínimo 8 caracteres)"
                                required
                                minlength="8"
                            >
                            <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 cursor-pointer">
                                <i class="fas fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar Nova Senha -->
                    <div class="mb-[30px]">
                        <label class="block text-[11px] font-bold text-[#aaa] uppercase tracking-[1px] mb-[5px]">Confirmar Nova Senha</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation"
                                class="w-full p-[12px] pr-10 border border-[#e0e0e0] rounded-[8px] text-[14px] outline-none focus:border-[#465367] transition-colors"
                                placeholder="Confirme a nova senha"
                                required
                                minlength="8"
                            >
                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 cursor-pointer">
                                <i class="fas fa-eye" id="password_confirmation-icon"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botões -->
                    <div class="flex gap-[15px]">
                        <button type="submit" class="flex-1 bg-[#465367] text-white font-bold py-[12px] px-[20px] rounded-[8px] cursor-pointer hover:bg-[#3a4452] transition-colors">
                            <i class="fas fa-lock"></i> Alterar Senha
                        </button>
                        <a href="/" class="flex-1 bg-[#e0e0e0] text-[#465367] font-bold py-[12px] px-[20px] rounded-[8px] cursor-pointer hover:bg-[#d0d0d0] transition-colors text-center no-underline">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>

                <!-- Link para Voltar -->
                <div class="mt-[20px] text-center">
                    <a href="/meusdados" class="text-[#465367] text-[13px] no-underline hover:underline">
                        <i class="fas fa-arrow-left"></i> Voltar para Meus Dados
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');
            
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
