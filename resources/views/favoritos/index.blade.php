<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favoritos | Distribuidora Foccus</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<script id="#userwayAccessibilityIcon" src = " https://cdn.userway.org/widget.js "  data-account = " wHedvuvp49 " ></script>
    <style>
    #userwayAccessibilityIcon {
        margin-top: 500px;
    }
    </style>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <main class="mx-auto max-w-7xl px-4 py-10 md:py-14">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-500">Minha lista</p>
                <h1 class="mt-2 text-4xl font-black">Produtos favoritos</h1>
                <p class="mt-2 text-slate-600">Acesse rapidamente seus itens preferidos e sugestões relacionadas.</p>
            </div>
            <a href="/" class="rounded-full bg-slate-900 px-5 py-3 text-sm font-bold text-white">Voltar para loja</a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            @forelse ($favoritos as $produto)
                <article class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <div class="relative overflow-hidden rounded-xl bg-slate-100 aspect-square mb-0">
                        <a href="{{ route('produtos.show', $produto) }}" class="block h-full w-full">
                            <x-product-image :url="$produto->url_imagem" :ean="$produto->codigo_barras" class="h-full w-full object-contain p-2" />
                        </a>

                        @auth
                            @php $isFavorito = true; @endphp
                            <form action="{{ route('favoritos.destroy', $produto) }}" method="POST" class="absolute right-3 top-3 z-20">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Remover dos favoritos" class="h-10 w-10 rounded-full shadow flex items-center justify-center transition focus:outline-none bg-red-600 text-white">
                                    <i class="fa-solid fa-heart"></i>
                                </button>
                            </form>
                        @endauth
                    </div>

                    <h2 class="mt-3 line-clamp-2 text-base font-black">{{ $produto->nome }}</h2>
                    <p class="text-xs text-slate-500">{{ $produto->categoria ?? 'Sem categoria' }}</p>
                    <p class="mt-2 text-2xl font-black text-blue-700">R$ {{ number_format((float) $produto->preco_atual, 2, ',', '.') }}</p>

                    <form action="{{ route('favoritos.destroy', $produto) }}" method="POST" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button class="w-full rounded-xl border border-red-200 py-2 text-sm font-bold text-red-700 hover:bg-red-50">Remover dos favoritos</button>
                    </form>
                </article>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center">
                    <h2 class="text-2xl font-black">Nenhum favorito ainda</h2>
                    <p class="mt-2 text-slate-600">Favorite produtos no carrinho ou na pagina de produto para acompanhar aqui.</p>
                </div>
            @endforelse
        </section>

        @if ($sugestoes->count() > 0)
            <section class="mt-12">
                <h2 class="text-3xl font-black">Sugestoes relacionadas</h2>
                <div class="mt-5 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($sugestoes as $produto)
                        <article class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                            <a href="{{ route('produtos.show', $produto) }}" class="block overflow-hidden rounded-xl bg-slate-100 aspect-square">
                                <x-product-image :url="$produto->url_imagem" :ean="$produto->codigo_barras" class="h-full w-full object-contain p-2" />
                            </a>
                            <h3 class="mt-3 line-clamp-2 text-base font-black">{{ $produto->nome }}</h3>
                            <p class="text-xs text-slate-500">{{ $produto->categoria ?? 'Sem categoria' }}</p>
                            <p class="mt-2 text-2xl font-black text-blue-700">R$ {{ number_format((float) $produto->preco_atual, 2, ',', '.') }}</p>

                            <form action="{{ route('carrinho.add', $produto) }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="quantidade" value="1">
                                <button class="w-full rounded-xl bg-slate-900 py-2 text-sm font-bold text-white hover:bg-slate-800">Adicionar ao carrinho</button>
                            </form>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </main>
</body>
</html>
