<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Distribuidora Foccus | Portal B2B</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/02669f3445.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    
    <style>
        .swiper { width: 100%; padding-bottom: 40px; }
        .swiper-button-next {  
             color: #1e293b;
             margin-right: -20px;

             }  
        .swiper-button-prev{
            color: #1e293b;
            margin-left: -20px;
            }  
.swiper-pagination {
    display: flex; 
    justify-content: center; 
    flex-wrap: nowrap; 
    overflow-x: auto; 
    padding: 10px 0; 
    
    /* Para esconder a barra de rolagem */
    -ms-overflow-style: none;  
    scrollbar-width: none;  
}


.swiper-pagination-bullet {
    flex-shrink: 0; 
}

.dropdown {
    position: relative;
    display: inline-block;
    background-color:#1e293b;
    padding:10px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
}  


.dropdown:hover{
 background-color: #334155;
 transition:1s;
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
.dropdown-content a,
.dropdown-content button:hover {
    background-color:#ccc;
}

.dropdown-content.show {
    display: block;
}
    
    .Banner1Test{
        margin-bottom:40px;
    }


    </style>
</head>
<body class="bg-gray-50 font-sans">
    @php
        $quantidadeCarrinho = array_sum(session('carrinho', []));
    @endphp

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
        <!--Codigo para Verificar Usuario Logado -->
            @auth
                <div class="dropdown">
                    <button onclick="toggleMenu()" class="dropbtn">
                      Olá, {{ auth()->user()->name }}
                    </button>
                    <div id="menu" class="dropdown-content">
                         <a target="_blank" href="/meusdados">Meus dados</a>
                         <form m  ethod="GET" action="/logout">
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
        <!------------------------------------------------------------>            
            <div class="hidden sm:flex items-center space-x-3 border-r border-slate-400 pr-4 text-xs">
                @guest
                <a href="/login" class="hover:text-slate-200 transition">Entrar</a>
                <a href="/register" class="bg-white text-slate-600 px-3 py-1.5 rounded-lg font-bold hover:bg-slate-100 transition shadow-sm">
                    Cadastrar
                </a>
                @endguest
            </div>

            <button type="button" class="relative bg-slate-800 px-4 py-2 rounded-full hover:bg-slate-700 transition flex items-center gap-2" id="btnCart" onclick="openCartModal()">
                <span class="text-sm">🛒</span>
                <span class="text-xs font-bold hidden md:inline">Carrinho</span>
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-black rounded-full w-5 h-5 flex items-center justify-center" id="carrinhoCountBadge" style="display: none;">
                    0
                </span>
            </button>
        </div>
    </div>
</nav>

    @if (session('success') || session('error'))
        <div class="max-w-7xl mx-auto px-6 pt-6">
            <div class="rounded-xl border px-4 py-3 text-sm {{ session('success') ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-red-200 bg-red-50 text-red-700' }}">
                {{ session('success') ?? session('error') }}
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto p-6 md:p-10">
        <img class="Banner1Test" src="{{ asset('Banner1.png') }}" alt="">
        <section class="mb-16">
            <div class="flex items-center gap-2 mb-6">
                <i class="fa-solid fa-fire" style="color: #f52727; font-size: 30px "></i>
                <h2 class="text-2xl font-bold text-gray-800">Ofertas da Semana</h2>
            </div>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach($destaques as $item)
                        <div class="swiper-slide bg-white rounded-xl shadow-md border border-red-50 border-opacity-50 overflow-hidden">
                            <div class="relative">
                                <img src="{{ $item->imagem ?? 'https://via.placeholder.com/300x200' }}" class="w-full h-48 object-cover">
                                @if($item->preco_antigo)
                                    <span class="absolute top-2 right-2  px-2 py-1 rounded">
                                        <i class="fa-solid fa-tag" style="color: #f52727;font-size: 20px"></i>
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
                                <form action="{{ route('carrinho.add', $item, false) }}" method="POST" class="mt-3 add-to-cart-form" data-product-id="{{ $item->id }}" onsubmit="return addToCart(event)">
                                    @csrf
                                    <input type="hidden" name="quantidade" value="1">
                                    <button type="submit" class="w-full bg-slate-800 text-white py-2 rounded-lg text-sm font-bold hover:bg-slate-900 transition disabled:bg-slate-400 disabled:cursor-not-allowed" {{ $item->quantidade <= 0 ? 'disabled' : '' }}>
                                        {{ $item->quantidade > 0 ? '🛒 Adicionar' : 'Esgotado' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
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
                 <img src="{{ $produto->url_imagem }}" class="group-hover:scale-110 transition-transform duration-300">
            </div>

            <h3 class="font-bold text-lg text-gray-800 truncate">{{ $produto->nome }}</h3>
            <p class="text-xs text-gray-400 mb-1">{{ $produto->marca }}</p>
            
            <p class="text-sm {{ $produto->quantidade > 0 ? 'text-green-600' : 'text-red-600' }}">
                Estoque: {{ $produto->quantidade }} un.
            </p>

            <p class="text-blue-700 font-bold text-xl mt-2 font-mono">
                R$ {{ number_format($produto->preco_atual, 2, ',', '.') }}
            </p>

            <form action="{{ route('carrinho.add', $produto, false) }}" method="POST" class="mt-4 add-to-cart-form" data-product-id="{{ $produto->id }}" onsubmit="return addToCart(event)">
                @csrf
                <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                
                <div class="flex items-center mb-3">
                    <label class="text-xs font-bold text-gray-400 mr-2 uppercase">Qtd:</label>
                    <input type="number" name="quantidade" value="1" min="1" max="{{ $produto->quantidade }}" 
                           class="w-full border border-gray-200 rounded px-2 py-1 text-sm focus:ring-2 focus:ring-blue-200 outline-none">
                </div>

                <button type="submit" 
                        {{ $produto->quantidade <= 0 ? 'disabled' : '' }}
                        class="w-full bg-blue-600 text-white py-2 rounded-md font-medium hover:bg-blue-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed">
                    {{ $produto->quantidade > 0 ? 'Adicionar ao Pedido' : 'Esgotado' }}
                </button>
            </form>
        </div>
    @endforeach
</div>
    </main>

    <footer class="mt-20 border-t border-gray-200 bg-white p-8 text-center text-gray-400 text-sm">
        &copy; {{ date('Y') }} Distribuidora Foccus - Todos os direitos reservados.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- Modal do Carrinho -->
    <div id="cartModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[80vh] flex flex-col">
            <!-- Header -->
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-bold">Meu Carrinho</h2>
                <button onclick="closeCartModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Conteúdo -->
            <div class="flex-1 overflow-y-auto p-6" id="cartContent">
                <p class="text-center text-gray-500">Carregando...</p>
            </div>
            
            <!-- Rodapé -->
            <div class="border-t p-6 space-y-2">
                <div class="flex justify-between text-lg font-bold">
                    <span>Total:</span>
                    <span id="cartTotal">R$ 0,00</span>
                </div>
                <button onclick="finalizarPedido()" class="w-full bg-green-600 text-white py-2 rounded-lg font-bold hover:bg-green-700 transition">
                    Finalizar Pedido
                </button>
                <button onclick="closeCartModal()" class="w-full bg-gray-300 text-gray-800 py-2 rounded-lg font-bold hover:bg-gray-400 transition">
                    Continuar Comprando
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            document.querySelector('input[name="_token"]')?.value;

        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        if (csrfToken) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        }

        // Adicionar event listeners aos formulários de carrinho
        document.addEventListener('DOMContentLoaded', function() {
            // Atualizar badge do carrinho ao carregar
            updateCartBadge();
        });

        function openCartModal() {
            loadCart();
            document.getElementById('cartModal').classList.remove('hidden');
        }

        function closeCartModal() {
            document.getElementById('cartModal').classList.add('hidden');
        }

        function addToCart(event) {
            event.preventDefault();
            
            const form = event.currentTarget || event.target;
            const quantidadeInput = form.querySelector('input[name="quantidade"]');
            const quantidade = quantidadeInput ? quantidadeInput.value : 1;
            const url = form.getAttribute('action');

            if (!url) {
                showNotification('Erro ao adicionar ao carrinho', 'error');
                return false;
            }
            
            axios.post(url, {
                quantidade: quantidade
            }, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.data.success) {
                    showNotification(response.data.message, 'success');
                    updateCartBadge();
                    // Abrir o modal
                    if (!document.getElementById('cartModal').classList.contains('hidden')) {
                        loadCart();
                    }
                }
            })
            .catch(error => {
                const message = error.response?.data?.message
                    || error.response?.data?.errors?.quantidade?.[0]
                    || 'Erro ao adicionar ao carrinho';
                showNotification(message, 'error');
            });

            return false;
        }

        function loadCart() {
            axios.get('/api/carrinho')
                .then(response => {
                    renderCart(response.data);
                })
                .catch(error => {
                    document.getElementById('cartContent').innerHTML = 
                        '<p class="text-center text-red-500">Erro ao carregar carrinho</p>';
                });
        }

        function renderCart(data) {
            const { carrinho, total, quantidadeTotal } = data;
            const cartContent = document.getElementById('cartContent');
            
            if (carrinho.length === 0) {
                cartContent.innerHTML = '<p class="text-center text-gray-500 py-8">Carrinho vazio</p>';
                document.getElementById('cartTotal').textContent = 'R$ 0,00';
                return;
            }
            
            let html = '<div class="space-y-4">';
            
            carrinho.forEach(item => {
                const produto = item.produto;
                html += `
                    <div class="border rounded-lg p-3 flex items-start justify-between hover:bg-gray-50">
                        <div class="flex-1">
                            <p class="font-bold text-sm">${produto.nome}</p>
                            <p class="text-xs text-gray-500">${produto.marca}</p>
                            <p class="text-sm font-bold mt-1">R$ ${formatPrice(produto.preco_atual)}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <button onclick="updateQuantity(${produto.id}, ${item.quantidade - 1})" class="px-2 py-1 bg-gray-200 rounded text-xs hover:bg-gray-300">-</button>
                                <span class="text-sm font-bold px-2">${item.quantidade}</span>
                                <button onclick="updateQuantity(${produto.id}, ${item.quantidade + 1})" class="px-2 py-1 bg-gray-200 rounded text-xs hover:bg-gray-300">+</button>
                            </div>
                        </div>
                        <div class="text-right ml-2">
                            <p class="font-bold text-sm">R$ ${formatPrice(item.subtotal)}</p>
                            <button onclick="removeFromCart(${produto.id})" class="text-red-500 text-xs hover:text-red-700 mt-2">Remover</button>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            cartContent.innerHTML = html;
            document.getElementById('cartTotal').textContent = `R$ ${formatPrice(total)}`;
        }

        function updateQuantity(productId, newQuantidade) {
            if (newQuantidade < 1) {
                removeFromCart(productId);
                return;
            }
            
            const url = `/carrinho/${productId}`;
            
            axios.put(url, {
                quantidade: newQuantidade
            }, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                loadCart();
                updateCartBadge();
            })
            .catch(error => {
                showNotification('Erro ao atualizar quantidade', 'error');
            });
        }

        function removeFromCart(productId) {
            const url = `/carrinho/${productId}`;
            
            axios.delete(url, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.data && response.data.carrinho) {
                    renderCart(response.data);
                    updateCartBadgeValue(response.data.quantidadeTotal || 0);
                } else {
                    loadCart();
                    updateCartBadge();
                }
                showNotification(response.data?.message || 'Produto removido do carrinho', 'success');
            })
            .catch(error => {
                showNotification('Erro ao remover produto', 'error');
            });
        }

        function updateCartBadgeValue(quantidade) {
            const badge = document.getElementById('carrinhoCountBadge');

            if (!badge) {
                return;
            }

            if (quantidade > 0) {
                badge.textContent = quantidade;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        function updateCartBadge() {
            axios.get('/api/carrinho')
                .then(response => {
                    updateCartBadgeValue(response.data.quantidadeTotal || 0);
                });
        }

        function formatPrice(price) {
            return parseFloat(price).toLocaleString('pt-BR', { 
                minimumFractionDigits: 2, 
                maximumFractionDigits: 2 
            });
        }

        function showNotification(message, type) {
            // Criar elemento de notificação
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-4 py-3 rounded-lg text-sm font-bold z-50 ${
                type === 'success' 
                    ? 'bg-green-100 text-green-700 border border-green-200' 
                    : 'bg-red-100 text-red-700 border border-red-200'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            // Remover após 3 segundos
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        function finalizarPedido() {
            alert('Finalizar pedido - Funcionalidade a implementar');
            // Implementar lógica de finalizar pedido
        }

        // Fechar modal ao clicar fora dele
        document.getElementById('cartModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCartModal();
            }
        });
    </script>
    
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
</html>