<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Foccus Comercial')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/02669f3445.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #ffffff;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 200px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            border-radius: 12px;
            z-index: 1001;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            padding: 0.5rem;
        }
        .dropdown-content.show {
            display: block;
        }
        .dropdown-content a {
            display: block;
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: #475569;
            font-size: 0.875rem;
            border-radius: 8px;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .dropdown-content a:hover {
            background-color: #f1f5f9;
            color: #1e293b;
        }
        .dropbtn {
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            border: none;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 0.875rem;
            font-weight: 600;
            transition: background 0.2s;
        }
        .dropbtn:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>

    @yield('extra-css')
</head>
<body class="min-h-screen text-slate-900">

    <!-- BARRA DE CATEGORIAS -->
    <div class="w-full fixed top-0 left-0 z-20 text-center text-white bg-slate-600 font-sans text-sm py-0.5">
        <section class="Nav-catalogo bg-slate-100 w-full flex fixed justify-center z-40">
            <div class="flex w-1/2 justify-between p-2">
                <p class="text-slate-600 font-bold">Compre por Categoria!</p>
                <a class="text-slate-600 hover:text-slate-600 hover:font-bold" href="/categoria/limpeza">Limpeza</a>
                <a class="text-slate-600 hover:text-slate-600 hover:font-bold" href="/categoria/higiene">Higiene</a>
                <a class="text-slate-600 hover:text-slate-600 hover:font-bold" href="/categoria/mercearia">Mercearia</a>
                <a class="text-slate-600 hover:text-slate-600 hover:font-bold" href="/categoria/bebidas">Bebidas</a>
                <a class="text-red-600 hover:text-slate-600 font-bold" href="/categoria/ofertas">Ofertas</a>
            </div>
        </section>
    </div>

    <!-- NAVBAR PRINCIPAL -->
    <nav style="padding: 10px;" class="sticky top-0 z-50 border-b border-slate-700 bg-slate-800">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 pb-4 pt-7 md:px-8">

            <!-- Logo -->
            <a href="/" class="shrink-0">
                <picture>
                    <source srcset="/LOGO_FOCCUS.webp" type="image/webp">
                    <img src="/LOGO_FOCCUS.png" class="w-36 brightness-0 invert md:w-40" alt="Logo Foccus" decoding="async">
                </picture>
            </a>

            <!-- Busca (desktop) -->
            <div class="flex-1 max-w-md hidden lg:block">
                <form action="/search" method="GET" class="relative">
                    <input type="text"
                        name="q"
                        placeholder="O que você procura hoje?"
                        class="w-full bg-slate-600 text-white text-sm rounded-full py-2 px-10 focus:outline-none focus:ring-2 focus:ring-slate-300 placeholder-slate-300 transition-all border border-transparent focus:bg-slate-700">
                    <div class="absolute left-3 top-2.5 text-slate-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>
            </div>

            <!-- Ações (login/registro ou usuário + carrinho) -->
            <div class="flex items-center gap-3">
                @auth
                    <div class="dropdown z-[1000]">
                        <button onclick="toggleMenu()" class="dropbtn">
                            Olá, {{ auth()->user()->name }}
                        </button>
                        <div id="menu" class="dropdown-content">
                            <a target="_blank" href="/meusdados">Meus dados</a>
                            <a target="_blank" href="/">Meus Pedidos</a>
                            @if(Auth::user()->is_admin)
                                <a target="_blank" href="/admin/dashboard">Dashboard</a>
                            @endif
                            <a target="_blank" href="/change-password">Trocar Senha</a>
                            @if(Auth::user()->is_admin)
                                <a target="_blank" href="/admin/produtos/create">Novo Produto</a>
                            @endif
                            <form action="/logout" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 border-0 bg-transparent cursor-pointer font-semibold">
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>

                    <script>
                        function toggleMenu() {
                            document.getElementById("menu").classList.toggle("show");
                        }
                        window.onclick = function(event) {
                            if (!event.target.matches('.dropbtn')) {
                                let menus = document.querySelectorAll('.dropdown-content');
                                menus.forEach(function(menu) {
                                    menu.classList.remove('show');
                                });
                            }
                        }
                    </script>
                @else
                    <a href="/login" class="hidden text-sm font-semibold text-slate-100 transition hover:text-white md:inline">Entrar</a>
                    <a href="/register" class="hidden rounded-full bg-white px-4 py-2 text-sm font-black text-slate-900 transition hover:bg-slate-100 md:inline">Cadastrar</a>
                @endguest
                <h1 class="text-white">|</h1>
                <button type="button" class="relative flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-black text-slate-900 transition hover:bg-slate-100" id="btnCart" onclick="openCartModal()">
                    <span>🛒</span>
                    <span class="hidden md:inline">Carrinho</span>
                    <span class="absolute -right-2 -top-2 hidden h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-black text-white" id="carrinhoCountBadge">0</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session('success') || session('error'))
        <div class="mx-auto mt-6 max-w-7xl px-4 md:px-8">
            <div class="rounded-2xl border px-4 py-3 text-sm {{ session('success') ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-red-200 bg-red-50 text-red-700' }}">
                {{ session('success') ?? session('error') }}
            </div>
        </div>
    @endif

    @yield('flash')

    <!-- Main Content -->
    <main class="mx-auto max-w-7xl px-4 py-8 md:px-8 md:py-10">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-lg-3 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Foccus Comercial</h5>
                    <p>Desde 2024 sendo sua parceira em soluções de comercialização e distribuição na região de São Paulo.</p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="https://www.instagram.com/foccus.dist/" target="_blank" class="text-white hover-warning" style="font-size: 1.2rem;"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.facebook.com/foccus.dist/" target="_blank" class="text-white hover-warning" style="font-size: 1.2rem;"><i class="fab fa-facebook"></i></a>
                        <a href="https://wa.me/5511937530312" target="_blank" class="text-white hover-warning" style="font-size: 1.2rem;"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <div class="col-md-2 col-lg-2 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Links úteis</h5>
                    <p><a href="/" class="text-white" style="text-decoration: none;">Início</a></p>
                    <p><a href="/carrinho" class="text-white" style="text-decoration: none;">Meu Carrinho</a></p>
                </div>

                <div class="col-md-3 col-lg-3 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Contato</h5>
                    <p class="small"><i class="fas fa-home mr-3 text-warning"></i> R. Cembira, 922 - Vila Curuçá Velha, SP</p>
                    <p class="small"><i class="fas fa-envelope mr-3 text-warning"></i> contato@foccus.com.br</p>
                    <p class="small"><i class="fas fa-phone mr-3 text-warning"></i> (11) 93753-0312</p>
                </div>

                <div class="col-md-3 col-lg-4 mx-auto mt-3 text-center text-md-start">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Horário de Funcionamento</h5>
                    <p class="small">Segunda a Sexta: 08:00 - 18:00</p>
                    <p class="small">Sábado: 08:00 - 13:00</p>
                </div>
            </div>
            <hr class="border-secondary mt-4">
            <div class="text-center mt-3 pb-2">
                <small>Versão 1.0</small>
                <br>
                <small class="text-muted">© 2026 Foccus</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    @yield('extra-js')
</body>
</html>