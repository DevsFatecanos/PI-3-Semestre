<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Dados</title>
    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/02669f3445.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.min.js" defer></script>
    <script id="#userwayAccessibilityIcon" src = " https://cdn.userway.org/widget.js "  data-account = " wHedvuvp49 " ></script>
    
    <style>
    #userwayAccessibilityIcon {
        margin-top: 500px;
    }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <link rel="preload" href="{{ asset('LOGO_FOCCUS.webp') }}" as="image" type="image/webp">
    <link rel="preload" href="{{ asset('LOGO_FOCCUS.png') }}" as="image">
</head>
<body class="bg-[#f4f4f4] flex items-center justify-center min-h-screen font-sans">
    <section class="main">
        <!-- Container Principal -->
        <div class="flex w-[950px] h-[600px] bg-white rounded-[20px] shadow-2xl overflow-hidden">
            
            <!-- Lado Esquerdo (Azul) -->
            <div class="relative w-[35%] bg-[#465367] p-[50px] text-white flex flex-col justify-center">
                <!-- Botão Home (Substituindo o absolute manual por Tailwind) -->
                <a class="absolute top-10 left-10 text-white no-underline text-sm flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity"  href="/" >
                    <i class="fa-solid fa-angle-left"></i> Home
                </a>

                <div class="logo">
                    <picture>
                    <source srcset="{{ asset('LOGO_FOCCUS.webp') }}" type="image/webp">
                    <img src="{{ asset('LOGO_FOCCUS.png') }}" class="w-40 brightness-0 invert" alt="Logo Foccus" decoding="async">
                </picture>
                </div>
                <h1 class="text-[28px] font-bold mt-[30px] mb-[15px] leading-tight">Seus dados</h1>
                <p class="text-[14px] leading-[1.6] opacity-80">Acesse sua conta para ver ou alterar seus dados cadastrados.</p>
            </div>

            <!-- Lado Direito (Branco/Formulário) -->
            <div class="w-[65%] p-[60px] flex flex-col justify-center">
                <h2 class="text-[24px] font-bold text-[#1a1a1a]">Seus Dados</h2>
                <p class="text-[13px] text-[#888] mb-[30px]">Atualize ou Adicione seus dados aqui.</p>

                <form method="POST" action="/meusdados/update">
                      @csrf
                    <div class="mb-[20px]">
                        <label class="block text-[11px] font-bold text-[#aaa] uppercase tracking-[1px] mb-[5px]">Nome Completo</label>
                        <input name="name" type="text" value="{{ auth()->user()->name }}" class="w-full p-[12px] border border-[#e0e0e0] rounded-[8px] text-[14px] outline-none focus:border-[#465367] transition-colors">
                    </div>

                    <div class="flex gap-[20px] mb-[20px]">
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-[#aaa] uppercase tracking-[1px] mb-[5px]">CPF</label>
                            <input name="cpf" id="cpf" type="text" value="{{ auth()->user()->cpf }}" placeholder="123.456.789-10" data-format="999.999.999-99" class="w-full p-[12px] border border-[#e0e0e0] rounded-[8px] text-[14px] outline-none focus:border-[#465367] transition-colors">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-[#aaa] uppercase tracking-[1px] mb-[5px]">Telefone</label>
                            <input name="telefone" id="tel" type="text" value="{{ auth()->user()->telefone }}" placeholder="(11) 1234-5678" data-format="(99) 9999-9999" data-alt-format="(99) 99999-9999"  class="w-full p-[12px] border border-[#e0e0e0] rounded-[8px] text-[14px] outline-none focus:border-[#465367] transition-colors">
                        </div>
                    </div>

                    <div class="mb-[20px]">
                        <label class="block text-[11px] font-bold text-[#aaa] uppercase tracking-[1px] mb-[5px]">E-mail</label>
                        <input name="email" type="email" value="{{ auth()->user()->email }}" class="w-full p-[12px] border border-[#e0e0e0] rounded-[8px] text-[14px] outline-none focus:border-[#465367] transition-colors">
                    </div>
                    @if(session('success'))
                        <p style="color: green;">{{ session('success') }}</p>
                    @endif

                    <!-- Seção de Botões -->
                    <div class="mt-[20px] flex gap-[10px] items-start">
                        <a href="{{ route('change-password.form') }}" class="w-1/2 bg-[#465367] text-white border border-[#ddd] p-[15px] rounded-[8px] text-[14px] font-semibold cursor-pointer transition-all duration-500 hover:bg-[#888888] text-center no-underline">
                            <i class="fas fa-lock"></i> Trocar Senha
                        </a>
                        <button id="Submit" type="submit" class="w-1/2 bg-[#1e293b] text-white p-[15px] rounded-[8px] text-[14px] font-bold cursor-pointer hover:bg-slate-700 transition-colors">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>   
     <script>

            //  MASCARA DE DADOS DO FORMULARIO
                var cpf = document.querySelector("#cpf");
                    cpf.addEventListener("blur", function(){
                if(cpf.value) cpf.value = cpf.value.match(/.{1,3}/g).join(".").replace(/\.(?=[^.]*$)/,"-");});

                var tel = document.querySelector("#tel");

                tel.addEventListener("blur", function() {
                    if (tel.value) {

                        let v = tel.value.replace(/\D/g, "");
                        
                        if (v.length === 11) {
                            // Celular: (XX) XXXXX-XXXX
                            tel.value = v.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
                        } else if (v.length === 10) {
                            // Fixo: (XX) XXXX-XXXX
                            tel.value = v.replace(/^(\d{2})(\d{4})(\d{4})$/, "($1) $2-$3");
                        }
                    }
                });
            //  MASCARA DE DADOS DO FORMULARIO    
        </script>  
</body>
</html>