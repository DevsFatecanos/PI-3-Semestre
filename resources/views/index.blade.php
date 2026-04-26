<!DOCTYPE html>
<html class="scroll-smooth" lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Foccus comercial | Distribuidora</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/02669f3445.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">

    <style>
        :root {
            --brand-900: #0f172a;
            --brand-700: #1d4ed8;
            --brand-500: #0ea5e9;
            --accent-500: #f97316;
            --surface: #f8fafc;
            --promo: #dc2626;
        }

        .animated-bg {
            position: relative;
            overflow: hidden;
        }

        .animated-bg::before {
            content: "";
            position: absolute;
            inset: -50% -20% auto;
            height: 280px;
            background: conic-gradient(from 90deg, rgba(14, 165, 233, 0.2), rgba(59, 130, 246, 0.08), rgba(249, 115, 22, 0.15), rgba(14, 165, 233, 0.2));
            filter: blur(24px);
            animation: spin 24s linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .glass {
            backdrop-filter: blur(12px);
            background: rgba(15, 23, 42, 0.82);
        }

        .reveal {
            opacity: 1;
            transform: translateY(0);
        }

        .js .reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity 0.55s ease, transform 0.55s ease;
        }

        .js .reveal.show {
            opacity: 1;
            transform: translateY(0);
        }

        .swiper {
            width: 100%;
            padding: 8px 4px 38px;
        }

        .swiper-pagination {
            display: none !important;
        }

        .carousel-shell {
            position: relative;
            border-radius: 26px;
            border: 1px solid rgba(29, 78, 216, 0.15);
            background: linear-gradient(180deg, rgba(255,255,255,0.9) 0%, rgba(248,250,252,0.9) 100%);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
            padding: 14px;
        }

        .hero-banner-wrap {
            position: relative;
            min-height: 100%;
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.96), rgba(30, 64, 175, 0.92), rgba(14, 165, 233, 0.88));
            padding: 18px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.26);
        }

        .hero-banner-wrap::after {
            content: "";
            position: absolute;
            inset: auto 12px 12px 12px;
            height: 24px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.14);
            filter: blur(18px);
            pointer-events: none;
        }

        .carousel-counter {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 9999px;
            background: #0f172a;
            color: #fff;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.16em;
            padding: 8px 12px;
        }

        .carousel-counter-current {
            color: #38bdf8;
        }

        .carousel-counter-total {
            color: #cbd5e1;
        }

        .promo-card.has-discount {
            border-width: 2px;
            box-shadow: 0 16px 34px rgba(220, 38, 38, 0.17);
        }

        .promo-card {
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }

        .promo-card.has-discount:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 18px 36px rgba(220, 38, 38, 0.22);
        }

        .category-chip {
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }

        .category-chip:hover {
            transform: translateY(-2px);
        }

        .category-chip.active {
            box-shadow: 0 10px 22px rgba(30, 64, 175, 0.24);
            opacity: 1;
        }

        .product-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.14);
        }

        .category-grid {
            transition: opacity 0.28s ease, transform 0.28s ease;
        }

        .category-grid.is-switching {
            opacity: 0.28;
            transform: translateY(10px) scale(0.99);
        }

        .batch-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 9999px;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.96), rgba(29, 78, 216, 0.92));
            color: #fff;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 0.16em;
            padding: 6px 10px;
            text-transform: uppercase;
        }

        .batch-pill span {
            color: #38bdf8;
        }

.dropdown {
    position: relative;
    display: inline-block;
    background-color:white;
    padding:10px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
    transition: background-color 0.3s ease, transform 0.2s ease;
}  


.dropdown:hover{
 background-color: #a9abae;
transform: translateY(-2px);
}

.dropdown-content {
    display: none;
    position: absolute;
    background: #fff;
    border: 1px solid #ccc;
    min-width: 150px;
}

.dropdown-content a,
.dropdown-content button {
    display: block;
    padding: 10px;
    text-decoration: none;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    color:#424242;
}

.dropdown-content a:hover {
    background-color:#ccc;
}

.dropdown-content button:hover {
    background-color:#ccc;
}

.dropdown-content.show {
    display: block;
}

.conteiner-img{
    position:absolute;
    color:white;
    top:75%;
    left: 12%;
    width: 26%;
    display:flex;
    justify-content:space-between;
}

.conteiner-img a{
padding: 10px;
font-size: small;
border-radius:20px;
background-color:#5f6f86;
font-weight: 500 ;
transition: background-color 0.3s ease, transform 0.2s ease;
text-align: center;
}


.conteiner-img #compre {
   padding: 10px 100px 10px 100px;
   justify-self:center;
   
}


.conteiner-img a:hover{
transform: translateY(-2px);
background-color:#a9abae;
}
    </style>
</head>
<body class="min-h-screen text-slate-900">
    @php
        $quantidadeCarrinho = array_sum(session('carrinho', []));

        $produtosPorCategoria = $produtosGerais
            ->groupBy(fn ($produto) => $produto->categoria ?: 'Sem categoria');

        $categoriaLista = $produtosPorCategoria->keys()->values();

        $categoriaMeta = [];
        foreach ($categoriaLista as $nomeCategoria) {
            $slug = Illuminate\Support\Str::slug($nomeCategoria);
            $normalizado = Illuminate\Support\Str::lower($nomeCategoria);

            $meta = [
                'slug' => $slug,
                'icone' => 'fa-box-open',
                'cores' => 'from-slate-600 to-slate-500',
                'bg' => 'bg-slate-100 text-slate-700',
            ];

            if (str_contains($normalizado, 'beb') || str_contains($normalizado, 'suco') || str_contains($normalizado, 'refri')) {
                $meta = ['slug' => $slug, 'icone' => 'fa-glass-water', 'cores' => 'from-cyan-600 to-blue-600', 'bg' => 'bg-cyan-100 text-cyan-700'];
            } elseif (str_contains($normalizado, 'limpeza')) {
                $meta = ['slug' => $slug, 'icone' => 'fa-soap', 'cores' => 'from-indigo-600 to-blue-700', 'bg' => 'bg-indigo-100 text-indigo-700'];
            } elseif (str_contains($normalizado, 'higiene')) {
                $meta = ['slug' => $slug, 'icone' => 'fa-pump-soap', 'cores' => 'from-emerald-600 to-teal-600', 'bg' => 'bg-emerald-100 text-emerald-700'];
            } elseif (str_contains($normalizado, 'alimento') || str_contains($normalizado, 'mercearia')) {
                $meta = ['slug' => $slug, 'icone' => 'fa-wheat-awn', 'cores' => 'from-amber-600 to-orange-600', 'bg' => 'bg-amber-100 text-amber-700'];
            } elseif (str_contains($normalizado, 'pet')) {
                $meta = ['slug' => $slug, 'icone' => 'fa-paw', 'cores' => 'from-fuchsia-600 to-pink-600', 'bg' => 'bg-fuchsia-100 text-fuchsia-700'];
            }

            $categoriaMeta[$nomeCategoria] = $meta;
        }
    @endphp
<!---ANIMAÇÃO DE CARREGAMENTO --->

<div class="carregando fixed inset-0 z-[1002] flex flex-col items-center justify-center overflow-hidden bg-slate-600/90">
<div class="mb-8 flex justify-center w-full">
    <img class="w-1/4 h-auto object-contain brightness-0 invert" src="{{asset('LOGO_FOCCUS.png')}}" alt="Logo">
</div>
    <!-- Barra de Progresso (progresso) -->
    <div class="w-1/2 overflow-hidden rounded-full bg-white/20">
        <div class="barra h-[5px] w-0 bg-white"></div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(window).on("load",function(){
        $(".barra").animate({width:"100%"},1000,function(){
            $(".carregando").fadeOut(500);
        });
    });
    </script>

<!-------------------------------->
    <div class="w-full text-center text-white bg-slate-600 font-sans text-sm"><p>Ofertas imperdíveis de até <strong> 20%OFF! </strong> Você não vai querer perder! 🔥</p></div>
    <nav class="glass sticky top-0 z-50 border-b border-white/10">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-4 md:px-8">
            <a href="/" class="shrink-0">
                <img src="/LOGO_FOCCUS.png" class="w-36 brightness-0 invert md:w-40" alt="Logo Foccus">
            </a>


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

            <div class="flex items-center gap-3">
            @auth
                <div class="dropdown">
                    <button onclick="toggleMenu()" class="dropbtn">
                      Olá, {{ auth()->user()->name }}
                    </button>
                    <div id="menu" class="dropdown-content">
                         <a target="_blank" href="/meusdados">Meus dados</a>
                         <a target="_blank" href="/">Meus Pedidos</a>
                         <a target="_blank" href="/admin/dashboard">Dashboard</a>
                         <form method="POST" action="/logout">
                        @csrf
                         <button type="submit">Sair</button>
                        </form>
                    </div>
                </div>
                <script>
                    function toggleMenu() {
                        document.getElementById("menu").classList.toggle("show");
                    }

                    // fechar ao clicar fora
                        window.onclick = function(event) {
                            if (!event.target.matches('.dropbtn')) {
                                let menu = document.getElementById("menu");
                                if (menu && menu.classList.contains('show')) {
                                    menu.classList.remove('show');
                                }
                            }
                        }
                </script>
            @endauth
                @guest
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

    @if (session('success') || session('error'))
        <div class="mx-auto mt-6 max-w-7xl px-4 md:px-8">
            <div class="rounded-2xl border px-4 py-3 text-sm {{ session('success') ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-red-200 bg-red-50 text-red-700' }}">
                {{ session('success') ?? session('error') }}
            </div>
        </div>
    @endif

    <main class="mx-auto max-w-7xl px-4 py-8 md:px-8 md:py-10">
        <section class=" reveal mb-14  shadow-lg">
            <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="5000">
                        <img width="100%" class="w-full w-screen" src="{{ asset('carousel/Banner1.png') }}" alt="carrosel 1 imagem" widtch:>
                            <div class="conteiner-img">
                                    <a href="#ofertas" class=" ">Ofertas da Semana 🔥</a>
                                    <a href="#catalogo" class="">Catalogo de produtos</a>
                            </div>
                    </div>
                    <div class="carousel-item" data-bs-interval="5000">
                        <img src="{{ asset('carousel/Banner2.png') }}" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item" data-bs-interval="5000">
                        <img src="{{ asset('carousel/Banner3.png') }}" class="d-block w-100" alt="...">
                            <div class="conteiner-img">
                                 <a id="compre" href="#catalogo" class=" ">Compre Já!</a>
                           </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>

        <section id="ofertas" class="reveal mb-16">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-red-100 text-red-600"><i class="fa-solid fa-fire text-4xl"></i></span>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900">Ofertas da semana</h2>
                        <p class="text-sm text-slate-500">Carrossel em rolagem continua, pausado no hover.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-black uppercase tracking-wider text-red-700">Descontos ativos</span>
                    <span class="carousel-counter" id="offerCounter" data-total="{{ max($destaques->count(), 1) }}">
                        <span class="carousel-counter-current" id="offerCounterCurrent">01</span>
                        <span>/</span>
                        <span class="carousel-counter-total" id="offerCounterTotal">{{ str_pad(max($destaques->count(), 1), 2, '0', STR_PAD_LEFT) }}</span>
                    </span>
                </div>
            </div>

            <div class="carousel-shell">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach($destaques as $item)
                        @php
                            $precoAntigo = (float) ($item->preco_antigo ?? 0);
                            $precoAtual = (float) ($item->preco_atual ?? 0);
                            $temDesconto = $precoAntigo > 0 && $precoAtual > 0 && $precoAtual < $precoAntigo;
                            $percentualDesconto = $temDesconto ? max(1, (int) round((1 - ($precoAtual / $precoAntigo)) * 100)) : 0;
                            $img = $item->url_imagem ?? $item->imagem ?? 'https://via.placeholder.com/500x320';
                        @endphp
                        <article class="swiper-slide promo-card {{ $temDesconto ? 'has-discount' : '' }} overflow-hidden rounded-3xl border bg-white shadow-md {{ $temDesconto ? 'border-red-200' : 'border-slate-100' }}">
                            <div class="relative aspect-square">
                                <img src="{{ $img }}" class="h-full w-full object-contain p-2">
                                @if($temDesconto)
                                    <span class="absolute left-3 top-3 inline-flex items-center gap-2 rounded-full bg-red-600 px-3 py-1 text-xs font-black text-white shadow-lg">
                                        <i class="fa-solid fa-bolt"></i>
                                        -{{ $percentualDesconto }}%
                                    </span>
                                @endif
                            </div>

                            <div class="p-5">
                                <h3 class="truncate text-lg font-black text-slate-800">{{ $item->nome }}</h3>
                                <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-400">{{ $item->categoria ?? 'Oferta' }}</p>

                                <div class="mt-3">
                                    @if($temDesconto)
                                        <span class="text-sm text-slate-400 line-through">R$ {{ number_format($precoAntigo, 2, ',', '.') }}</span>
                                    @endif
                                    <p class="text-2xl font-black {{ $temDesconto ? 'text-red-600' : 'text-blue-700' }}">R$ {{ number_format($precoAtual, 2, ',', '.') }}</p>
                                </div>

                                <form action="{{ route('carrinho.add', $item, false) }}" method="POST" class="mt-4 add-to-cart-form" onsubmit="return addToCart(event)">
                                    @csrf
                                    <div class="mb-3 flex items-center justify-center gap-2">
                                        <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Qtd:</label>
                                        <div class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-50">
                                            <button type="button" class="px-3 py-1.5 text-slate-600 hover:bg-slate-200 transition" onclick="this.nextElementSibling.stepDown()">−</button>
                                            <input type="number" name="quantidade" value="1" min="1" max="{{ $item->quantidade }}" class="w-14 border-0 bg-transparent text-center text-sm font-bold text-slate-800 outline-none [-moz-appearance:_textfield] [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none">
                                            <button type="button" class="px-3 py-1.5 text-slate-600 hover:bg-slate-200 transition" onclick="this.previousElementSibling.stepUp()">+</button>
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full rounded-xl px-4 py-2 text-sm font-black text-white transition {{ $temDesconto ? 'bg-red-600 hover:bg-red-500' : 'bg-slate-800 hover:bg-slate-700' }} disabled:cursor-not-allowed disabled:bg-slate-300" {{ $item->quantidade <= 0 ? 'disabled' : '' }}>
                                        {{ $item->quantidade > 0 ? 'Adicionar ao pedido' : 'Esgotado' }}
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
            </div>
        </section>

        <section id="catalogo">
            <div class="mb-7 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
                <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900">Catalogo por categoria</h2>
                        <p class="text-slate-500">Filtro em tempo real por nome, faixa de preco e categoria.</p>
                    </div>
                    <button type="button" id="clearFilters" class="rounded-full border border-slate-300 px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-slate-700 transition hover:bg-slate-100">Limpar filtros</button>
                </div>

                <div class="grid gap-3 md:grid-cols-5">
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-xs font-black uppercase tracking-[0.2em] text-slate-500">Pesquisa por nome</label>
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-3 text-slate-400"></i>
                            <input id="productSearch" type="text" placeholder="Ex: detergente, arroz, refrigerante" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-black uppercase tracking-[0.2em] text-slate-500">Categoria</label>
                        <select id="categoryFilter" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:bg-white">
                            <option value="">Todas</option>
                            @foreach($categoriaLista as $nomeCategoria)
                                <option value="{{ Illuminate\Support\Str::slug($nomeCategoria) }}">{{ $nomeCategoria }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-black uppercase tracking-[0.2em] text-slate-500">Preco minimo (R$)</label>
                        <input id="minPriceFilter" type="number" min="0" step="0.01" placeholder="Sem limite" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:bg-white">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-black uppercase tracking-[0.2em] text-slate-500">Preco maximo (R$)</label>
                        <input id="maxPriceFilter" type="number" min="0" step="0.01" placeholder="Sem limite" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:bg-white">
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2" id="categoryChips">
                    @foreach($categoriaLista as $nomeCategoria)
                        @php $meta = $categoriaMeta[$nomeCategoria] ?? ['slug' => Illuminate\Support\Str::slug($nomeCategoria), 'icone' => 'fa-box-open', 'bg' => 'bg-slate-100 text-slate-700']; @endphp
                        <button type="button" class="category-chip rounded-full px-3 py-1.5 text-xs font-black uppercase tracking-[0.12em] {{ $meta['bg'] }}" data-category-chip="{{ $meta['slug'] }}">
                            <i class="fa-solid {{ $meta['icone'] }} mr-1"></i>{{ $nomeCategoria }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div id="globalEmptyState" class="hidden rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">
                Nenhum produto encontrado com os filtros atuais.
            </div>

            <div class="space-y-10" id="catalogSections">
                @foreach($categoriaLista as $nomeCategoria)
                    @php
                        $slug = Illuminate\Support\Str::slug($nomeCategoria);
                        $meta = $categoriaMeta[$nomeCategoria] ?? ['slug' => $slug, 'icone' => 'fa-box-open', 'cores' => 'from-slate-600 to-slate-500', 'bg' => 'bg-slate-100 text-slate-700'];
                        $produtosCategoria = $produtosPorCategoria->get($nomeCategoria, collect())->values();
                    @endphp

                    <section class="categoria-bloco" data-category-section="{{ $slug }}" data-category-name="{{ Illuminate\Support\Str::lower($nomeCategoria) }}">
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br {{ $meta['cores'] }} text-white shadow">
                                    <i class="fa-solid {{ $meta['icone'] }}"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-900">{{ $nomeCategoria }}</h3>
                                    <div class="mt-1 flex flex-wrap items-center gap-2">
                                        <p class="text-sm text-slate-500"><span data-visible-count>0</span> itens visiveis</p>
                                        <span class="batch-pill">Lote <span data-batch-label>01/01</span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="prev-more-btn hidden rounded-full border border-slate-300 px-3 py-1.5 text-xs font-black uppercase tracking-[0.15em] text-slate-700 hover:bg-slate-100" data-prev-lot="{{ $slug }}">Lote Anterior</button>
                                <button type="button" class="show-more-btn hidden rounded-full border border-slate-300 px-3 py-1.5 text-xs font-black uppercase tracking-[0.15em] text-slate-700 hover:bg-slate-100" data-show-more="{{ $slug }}">Próximo lote</button>
                            </div>
                        </div>

                        <div class="category-grid grid gap-5 sm:grid-cols-2 xl:grid-cols-3" data-category-grid>
                            @foreach($produtosCategoria as $produto)
                                @php
                                    $preco = (float) ($produto->preco_atual ?? 0);
                                    $precoAntigo = (float) ($produto->preco_antigo ?? 0);
                                    $temDesconto = $precoAntigo > 0 && $preco > 0 && $preco < $precoAntigo;
                                    $percentual = $temDesconto ? max(1, (int) round((1 - ($preco / $precoAntigo)) * 100)) : 0;
                                @endphp
                                <article class="product-card overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-sm"
                                    data-product-card
                                    data-product-name="{{ Illuminate\Support\Str::lower($produto->nome) }}"
                                    data-product-price="{{ $preco }}"
                                    data-product-category="{{ $slug }}"
                                    data-product-card-index="{{ $loop->index }}">
                                    @php
                                        $eanCat = preg_replace('/\D/', '', $produto->codigo_barras ?? '');
                                        $eanPicturesCat = $eanCat ? "http://www.eanpictures.com.br:9000/api/gtin/{$eanCat}" : '';
                                    @endphp
                                    <div class="relative mb-4 overflow-hidden rounded-xl bg-slate-100 aspect-square">
                                        <img src="{{ $produto->url_imagem }}"
                                             @if($eanPicturesCat)
                                             onerror="if(this.src!=='{{ $eanPicturesCat }}'){this.src='{{ $eanPicturesCat }}';}else{this.onerror=null;this.src='{{ asset('/LOGO_FOCCUS.png') }}';}"
                                             @else
                                             onerror="this.onerror=null;this.src='{{ asset('/LOGO_FOCCUS.png') }}';"
                                             @endif
                                             class="h-full w-full object-contain p-2 transition duration-300 hover:scale-105" alt="{{ $produto->nome }}">
                                        @if($temDesconto)
                                            <span class="absolute left-2 top-2 rounded-full bg-red-600 px-2 py-1 text-[11px] font-black text-white">-{{ $percentual }}%</span>
                                        @endif
                                    </div>

                                    <div class="mb-3 flex items-start justify-between gap-2">
                                        <div>
                                            <h4 class="line-clamp-2 text-base font-black text-slate-900">{{ $produto->nome }}</h4>
                                            <p class="text-xs text-slate-500">{{ $produto->marca ?? 'Marca nao informada' }}</p>
                                        </div>
                                        <span class="rounded-full px-2 py-1 text-[10px] font-black uppercase tracking-wide {{ $meta['bg'] }}">{{ $nomeCategoria }}</span>
                                    </div>

                                    <div class="mb-3">
                                        @if($temDesconto)
                                            <p class="text-xs text-slate-400 line-through">R$ {{ number_format($precoAntigo, 2, ',', '.') }}</p>
                                        @endif
                                        <p class="text-2xl font-black text-blue-700">R$ {{ number_format($preco, 2, ',', '.') }}</p>
                                        <p class="text-xs {{ $produto->quantidade > 0 ? 'text-emerald-600' : 'text-red-600' }}">Estoque: {{ $produto->quantidade }} un.</p>
                                    </div>

                                    <form action="{{ route('carrinho.add', $produto, false) }}" method="POST" onsubmit="return addToCart(event)">
                                        @csrf
                                        <div class="mb-2 flex items-center gap-2">
                                            <label class="text-xs font-black uppercase text-slate-400">Qtd</label>
                                            <input type="number" name="quantidade" value="1" min="1" max="{{ $produto->quantidade }}" class="w-full rounded-lg border border-slate-200 px-2 py-1.5 text-sm outline-none focus:border-blue-500">
                                        </div>
                                        <button type="submit" class="w-full rounded-xl bg-blue-700 py-2 text-sm font-black text-white transition hover:bg-blue-600 disabled:cursor-not-allowed disabled:bg-slate-300" {{ $produto->quantidade <= 0 ? 'disabled' : '' }}>
                                            {{ $produto->quantidade > 0 ? 'Adicionar ao pedido' : 'Esgotado' }}
                                        </button>
                                    </form>
                                </article>
                            @endforeach
                        </div>

                        <div class="hidden rounded-xl border border-dashed border-slate-300 bg-white p-4 text-center text-sm text-slate-500" data-empty-category>
                            Nenhum produto desta categoria corresponde ao filtro atual.
                        </div>
                    </section>
                @endforeach
            </div>
        </section>
    </main>


<!--CARRINHO-->

    <div id="cartModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/55 p-4">
        <div class="flex max-h-[80vh] w-full max-w-md flex-col rounded-2xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b p-6">
                <h2 class="text-xl font-black">Meu Carrinho</h2>
                <button onclick="closeCartModal()" class="text-slate-500 transition hover:text-slate-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6" id="cartContent">
                <p class="text-center text-slate-500">Carregando...</p>
            </div>

            <div class="space-y-2 border-t p-6">
                <div class="flex justify-between text-lg font-black">
                    <span>Total:</span>
                    <span id="cartTotal">R$ 0,00</span>
                </div>
                <button onclick="finalizarPedido()" class="w-full rounded-lg bg-emerald-600 py-2 font-black text-white transition hover:bg-emerald-500">Finalizar pedido</button>
                <button onclick="closeCartModal()" class="w-full rounded-lg bg-slate-200 py-2 font-black text-slate-700 transition hover:bg-slate-300">Continuar comprando</button>
            </div>
        </div>
    </div>
<!----->

    <footer class="mt-20 border-t border-slate-200 bg-white/80 p-8 text-center text-sm text-slate-500">
        &copy; {{ date('Y') }} Distribuidora Foccus - Todos os direitos reservados.
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="{{ asset('js/carrinho.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
