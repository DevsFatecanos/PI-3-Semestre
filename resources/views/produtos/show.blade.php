@extends('layouts.store')

@section('title', 'Produto - ' . $produto->nome)

@section('content')
    @php
        $temDesconto = $produto->preco_antigo && $produto->preco_antigo > 0
                       && $produto->preco_atual && $produto->preco_atual < $produto->preco_antigo;
        $percentualDesconto = $temDesconto
                              ? max(1, (int) round((1 - ($produto->preco_atual / $produto->preco_antigo)) * 100))
                              : 0;
        $stockStatus = $produto->stock_status ?? 'ok';
    @endphp

    <!-- Breadcrumb -->
    <nav class="mb-6 flex items-center gap-2 text-sm text-slate-600">
        <a href="/" class="text-slate-500 hover:text-slate-700">Home</a>
        <span>/</span>
        <a href="/#catalogo" class="text-slate-500 hover:text-slate-700">Catálogo</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">{{ $produto->nome }}</span>
    </nav>

        <!-- Produto Container -->
        <div class="grid gap-8 md:grid-cols-2 lg:gap-12">
            <!-- Imagem do Produto -->
            <div class="flex flex-col gap-4">
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm" style="aspect-ratio: 1;">
                    <img
                        src="{{ $produto->url_imagem }}"
                        alt="{{ $produto->nome }}"
                        class="h-full w-full object-contain"
                    >
                </div>

                <!-- Thumbnails Placeholder -->
                <div class="hidden md:flex gap-3">
                    <div class="h-20 w-20 rounded-lg border border-slate-200 bg-slate-50 p-2 cursor-pointer hover:border-blue-500 transition">
                        <img src="{{ $produto->url_imagem }}" alt="thumbnail" class="h-full w-full object-contain">
                    </div>
                </div>
            </div>

            <!-- Detalhes do Produto -->
            <div class="flex flex-col gap-6">
                <!-- Categoria e Título -->
                <div>
                    <span class="inline-block rounded-full bg-blue-100 px-3 py-1 text-xs font-black uppercase tracking-wider text-blue-700 mb-3">
                        {{ $produto->categoria ?? 'Produto' }}
                    </span>
                    <h1 class="text-4xl font-black text-slate-900">{{ $produto->nome }}</h1>

                    @if($produto->marca)
                        <p class="mt-2 text-sm text-slate-500">
                            <strong>Marca:</strong> {{ $produto->marca }}
                        </p>
                    @endif
                </div>

                <!-- Preço -->
                <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-slate-100 p-6">
                    <div class="flex items-baseline gap-3">
                        <span class="text-5xl font-black text-blue-700">
                            R$ {{ number_format($produto->preco_atual ?? 0, 2, ',', '.') }}
                        </span>
                        @if($temDesconto)
                            <div class="flex flex-col gap-1">
                                <span class="text-lg text-slate-500 line-through">
                                    R$ {{ number_format($produto->preco_antigo, 2, ',', '.') }}
                                </span>
                                <span class="inline-block rounded-full bg-red-100 px-2 py-1 text-xs font-black text-red-700">
                                    <i class="fas fa-bolt"></i> -{{ $percentualDesconto }}%
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Estoque -->
                <div class="rounded-2xl border p-4 transition @if($stockStatus === 'ok') border-emerald-200 bg-emerald-50 @elseif($stockStatus === 'low') border-amber-200 bg-amber-50 @elseif($stockStatus === 'critical') border-red-200 bg-red-50 @else border-slate-200 bg-slate-50 @endif">
                    <div class="flex items-center gap-3">
                        @if($stockStatus === 'out')
                            <i class="fas fa-times-circle text-2xl text-red-600"></i>
                            <div>
                                <p class="font-black text-red-900">Fora de estoque</p>
                                <p class="text-sm text-red-700">Este produto não está disponível no momento</p>
                            </div>
                        @elseif($stockStatus === 'critical')
                            <i class="fas fa-exclamation-circle text-2xl text-red-600"></i>
                            <div>
                                <p class="font-black text-red-900">Estoque crítico</p>
                                <p class="text-sm text-red-700"><strong>{{ $produto->quantidade }}</strong> unidades disponíveis</p>
                            </div>
                        @elseif($stockStatus === 'low')
                            <i class="fas fa-exclamation-triangle text-2xl text-amber-600"></i>
                            <div>
                                <p class="font-black text-amber-900">Baixo estoque</p>
                                <p class="text-sm text-amber-700">Apenas <strong>{{ $produto->quantidade }}</strong> unidades</p>
                            </div>
                        @else
                            <i class="fas fa-check-circle text-2xl text-emerald-600"></i>
                            <div>
                                <p class="font-black text-emerald-900">Em estoque</p>
                                <p class="text-sm text-emerald-700"><strong>{{ $produto->quantidade }}</strong> unidades disponíveis</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Form Adicionar ao Carrinho -->
                <form action="{{ route('carrinho.add', $produto) }}" method="POST" class="flex flex-col gap-3" onsubmit="return addToCart(event)">
                    @csrf
                    <div class="flex items-end gap-3">
                        <div class="flex-1">
                            <label class="mb-2 block text-xs font-black uppercase tracking-wider text-slate-500">
                                <i class="fas fa-cube mr-1"></i> Quantidade
                            </label>
                            <div class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-50 w-full">
                                <button type="button" class="flex-1 px-4 py-3 text-slate-600 hover:bg-slate-200 transition font-bold" onclick="this.nextElementSibling.stepDown()">
                                    −
                                </button>
                                <input
                                    type="number"
                                    name="quantidade"
                                    value="1"
                                    min="1"
                                    max="{{ max(1, $produto->quantidade) }}"
                                    class="flex-1 border-0 bg-transparent text-center text-lg font-black text-slate-900 outline-none [-moz-appearance:_textfield] [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none"
                                >
                                <button type="button" class="flex-1 px-4 py-3 text-slate-600 hover:bg-slate-200 transition font-bold" onclick="this.previousElementSibling.stepUp()">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl px-6 py-4 text-lg font-black text-white transition {{ $produto->quantidade > 0 ? 'bg-blue-700 hover:bg-blue-600 shadow-lg hover:shadow-xl' : 'bg-slate-300 cursor-not-allowed' }}"
                        {{ $produto->quantidade <= 0 ? 'disabled' : '' }}
                    >
                        <i class="fas fa-shopping-cart mr-2"></i>
                        {{ $produto->quantidade > 0 ? 'Adicionar ao Carrinho' : 'Produto Esgotado' }}
                    </button>
                </form>

                <!-- Info Rápida -->
                <div class="grid grid-cols-2 gap-3 rounded-2xl border border-slate-200 bg-white p-4">
                    <div class="text-center">
                        <p class="text-2xl font-black text-slate-900">✓</p>
                        <p class="text-xs font-bold text-slate-600">Produto Original</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-black text-slate-900">📦</p>
                        <p class="text-xs font-bold text-slate-600">Entrega Rápida</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção Completa de Specs -->
        @if($produto->descricao || $produto->codigo_barras)
            <section class="mt-16 border-t pt-12">
                <h2 class="mb-6 text-3xl font-black text-slate-900">Especificações</h2>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="mb-4 text-lg font-black text-slate-900">Informações do Produto</h3>
                        <dl class="space-y-4">
                            @if($produto->codigo_barras)
                                <div class="flex justify-between border-b border-slate-200 pb-3">
                                    <dt class="text-sm font-bold text-slate-600">Código (EAN/Barras)</dt>
                                    <dd class="font-mono text-sm font-bold text-slate-900">{{ $produto->codigo_barras }}</dd>
                                </div>
                            @endif

                            <div class="flex justify-between border-b border-slate-200 pb-3">
                                <dt class="text-sm font-bold text-slate-600">Categoria</dt>
                                <dd class="text-sm font-bold text-blue-700">{{ $produto->categoria ?? 'N/A' }}</dd>
                            </div>

                            <div class="flex justify-between border-b border-slate-200 pb-3">
                                <dt class="text-sm font-bold text-slate-600">Marca</dt>
                                <dd class="text-sm font-bold text-slate-900">{{ $produto->marca ?? 'N/A' }}</dd>
                            </div>

                            <div class="flex justify-between">
                                <dt class="text-sm font-bold text-slate-600">ID Interno</dt>
                                <dd class="font-mono text-sm font-bold text-slate-600">#{{ $produto->id }}</dd>
                            </div>
                        </dl>
                    </div>

                    @if($produto->descricao)
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="mb-4 text-lg font-black text-slate-900">Descrição</h3>
                            <p class="text-slate-700 leading-relaxed">{{ $produto->descricao }}</p>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        <!-- Produtos Relacionados -->
        @php
            $produtosRelacionados = \App\Models\Produto::where('categoria', $produto->categoria)
                                                       ->where('id', '!=', $produto->id)
                                                       ->where('ativo', true)
                                                       ->limit(4)
                                                       ->get();
        @endphp

        @if($produtosRelacionados->count() > 0)
            <section class="mt-16 border-t pt-12">
                <h2 class="mb-8 text-3xl font-black text-slate-900">Produtos Relacionados</h2>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($produtosRelacionados as $related)
                        @php
                            $preco = (float) ($related->preco_atual ?? 0);
                            $precoAntigo = (float) ($related->preco_antigo ?? 0);
                            $temDesc = $precoAntigo > 0 && $preco > 0 && $preco < $precoAntigo;
                            $percentual = $temDesc ? max(1, (int) round((1 - ($preco / $precoAntigo)) * 100)) : 0;
                        @endphp

                        <article class="overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:shadow-lg hover:border-blue-200">
                            <div class="relative mb-4 overflow-hidden rounded-xl bg-slate-100 aspect-square">
                                <a href="{{ route('produtos.show', $related->id) }}">
                                    <img
                                        src="{{ $related->url_imagem }}"
                                        alt="{{ $related->nome }}"
                                        class="h-full w-full object-contain p-2 transition hover:scale-105"
                                    >
                                </a>

                                @if($temDesc)
                                    <span class="absolute left-2 top-2 rounded-full bg-red-600 px-2 py-1 text-[11px] font-black text-white">
                                        -{{ $percentual }}%
                                    </span>
                                @endif
                            </div>

                            <h4 class="line-clamp-2 text-base font-black text-slate-900 mb-2">
                                <a href="{{ route('produtos.show', $related->id) }}" class="text-inherit hover:text-blue-700 transition">
                                    {{ $related->nome }}
                                </a>
                            </h4>

                            <p class="text-xs text-slate-500 mb-3">{{ $related->marca ?? 'Marca não informada' }}</p>

                            <div class="mb-3">
                                @if($temDesc)
                                    <p class="text-xs text-slate-400 line-through">
                                        R$ {{ number_format($precoAntigo, 2, ',', '.') }}
                                    </p>
                                @endif
                                <p class="text-2xl font-black text-blue-700">
                                    R$ {{ number_format($preco, 2, ',', '.') }}
                                </p>
                            </div>

                            <a
                                href="{{ route('produtos.show', $related->id) }}"
                                class="block w-full rounded-lg bg-blue-700 py-2 text-center text-sm font-black text-white transition hover:bg-blue-600"
                            >
                                Ver Detalhes
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

    <script>
        function addToCart(event) {
            event.preventDefault();
            const form = event.target;
            const quantidade = form.querySelector('input[name="quantidade"]').value;

            // Usa o script global do carrinho.js
            if (typeof addToCartGlobal === 'function') {
                addToCartGlobal(event);
            } else {
                form.submit();
            }
            return false;
        }
    </script>
@endsection
