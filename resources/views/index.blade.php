<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuidora Foccus | Portal B2B</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <style>
        .swiper { width: 100%; padding-bottom: 40px; }
        .swiper-button-next, .swiper-button-prev { color: #1e293b; }
    </style>
</head>
<body class="bg-gray-50 font-sans">

    <nav class="bg-slate-500 text-white p-4 shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center gap-8">
        
        <div class="flex-shrink-0">
            <a href="/">
                <img src="{{ asset('LOGO_FOCCUS.png') }}" class="w-40 brightness-0 invert" alt="Logo Foccus">
            </a>
        </div>

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

        <div class="space-x-6 hidden xl:flex text-sm font-medium items-center">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="flex items-center hover:text-slate-200 transition outline-none">
                    Catálogo
                    <svg class="w-4 h-4 ml-1 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-xl py-2 z-50 border border-gray-100">
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-slate-100">Todos os Produtos</a>
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-slate-100">Lançamentos</a>
                    <hr class="my-1">
                    @foreach($categorias as $nomeCategoria)
    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-slate-100">
        {{ $nomeCategoria }}
    </a>
@endforeach
                </div>
            </div>

            <a href="#" class="hover:text-slate-200 transition">Pedidos</a>
            <a href="#" class="hover:text-slate-200 transition">Contato</a>
        </div>

        <div class="flex items-center space-x-4">
            <div class="hidden sm:flex items-center space-x-3 border-r border-slate-400 pr-4 text-xs">
                <a href="/login" class="hover:text-slate-200 transition">Entrar</a>
                <a href="/register" class="bg-white text-slate-600 px-3 py-1.5 rounded-lg font-bold hover:bg-slate-100 transition shadow-sm">
                    Cadastrar
                </a>
            </div>

            <div class="bg-slate-800 px-4 py-2 rounded-full cursor-pointer hover:bg-slate-700 transition flex items-center gap-2">
                <span class="text-sm">🛒</span>
                <span class="text-xs font-bold hidden md:inline">Carrinho</span>
            </div>
        </div>
    </div>
</nav>

    <main class="max-w-7xl mx-auto p-6 md:p-10">
        
        <section class="mb-16">
            <div class="flex items-center gap-2 mb-6">
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">HOT</span>
                <h2 class="text-2xl font-bold text-gray-800">Ofertas da Semana</h2>
            </div>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach($destaques as $item)
                        <div class="swiper-slide bg-white rounded-xl shadow-md border border-red-50 border-opacity-50 overflow-hidden">
                            <div class="relative">
                                <img src="{{ $item->imagem ?? 'https://via.placeholder.com/300x200' }}" class="w-full h-48 object-cover">
                                @if($item->preco_antigo)
                                    <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">
                                        OFF
                                    </span>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-gray-700 truncate">{{ $item->nome }}</h3>
                                <div class="mt-2">
                                    @if($item->preco_antigo)
                                        <span class="text-xs text-gray-400 line-through">R$ {{ number_format($item->preco_antigo, 2, ',', '.') }}</span>
                                    @endif
                                    <p class="text-xl font-black text-red-600">R$ {{ number_format($item->preco_atual, 2, ',', '.') }}</p>
                                </div>
                                <button class="w-full mt-3 bg-slate-800 text-white py-2 rounded-lg text-sm font-bold hover:bg-slate-900 transition">
                                    🛒 Adicionar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </section>

        <hr class="mb-16 border-gray-200">

        <header class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Catálogo Geral</h2>
                <p class="text-gray-500">Explore todos os nossos produtos em estoque.</p>
            </div>
           
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($produtosGerais as $produto)
                <div class="border p-4 rounded-lg shadow-sm bg-white hover:shadow-md transition-all group">
                    <div class="h-40 bg-gray-100 mb-4 rounded flex items-center justify-center overflow-hidden">
                         <img src="{{ $produto->imagem ?? 'https://via.placeholder.com/150' }}" class="group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <h3 class="font-bold text-lg text-gray-800">{{ $produto->nome }}</h3>
                    <p class="text-sm text-gray-500">Estoque: {{ $produto->estoque }} un.</p>
                    <p class="text-blue-700 font-bold text-xl mt-2 font-mono">
                        R$ {{ number_format($produto->preco_atual, 2, ',', '.') }}
                    </p>
                    <button class="w-full mt-4 bg-blue-600 text-white py-2 rounded-md font-medium hover:bg-blue-700 transition-colors">
                        Adicionar ao Pedido
                    </button>
                </div>
            @endforeach
        </div>
    </main>

    <footer class="mt-20 border-t border-gray-200 bg-white p-8 text-center text-gray-400 text-sm">
        &copy; {{ date('Y') }} Distribuidora Foccus - Todos os direitos reservados.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 25,
            loop: true,
            autoplay: { delay: 4000 },
            pagination: { el: ".swiper-pagination", clickable: true },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 4 },
            },
        });
    </script>
</body>
</html>