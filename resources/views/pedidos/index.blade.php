<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historico de compras | Distribuidora Foccus</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <main class="mx-auto max-w-7xl px-4 py-10 md:py-14">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-500">Pedidos</p>
                <h1 class="mt-2 text-4xl font-black">Historico de compras</h1>
                <p class="mt-2 text-slate-600">Visualize seus pedidos, total pago, data, meio de pagamento e itens.</p>
            </div>
            <a href="/carrinho" class="rounded-full bg-slate-900 px-5 py-3 text-sm font-bold text-white">Voltar ao carrinho</a>
        </div>

        <section class="space-y-5">
            @forelse ($pedidos as $pedido)
                <article class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Referencia</p>
                            <p class="text-lg font-black">{{ $pedido->referencia }}</p>
                        </div>
                        <div class="text-sm text-slate-600">
                            <p><strong>Status:</strong> {{ strtoupper($pedido->status) }}</p>
                            <p><strong>Pagamento:</strong> {{ strtoupper($pedido->metodo_pagamento) }}</p>
                            <p><strong>Data:</strong> {{ optional($pedido->data_pagamento ?? $pedido->created_at)->format('d/m/Y H:i') }}</p>
                            <p><strong>Total:</strong> R$ {{ number_format((float) $pedido->total, 2, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-slate-500">
                                    <th class="py-2">Produto</th>
                                    <th class="py-2">Categoria</th>
                                    <th class="py-2">Qtd</th>
                                    <th class="py-2">Preco</th>
                                    <th class="py-2">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pedido->itens as $item)
                                    <tr class="border-t border-slate-100">
                                        <td class="py-2 font-semibold">{{ $item->nome_produto }}</td>
                                        <td class="py-2">{{ $item->categoria_produto ?? 'Sem categoria' }}</td>
                                        <td class="py-2">{{ $item->quantidade }}</td>
                                        <td class="py-2">R$ {{ number_format((float) $item->preco_unitario, 2, ',', '.') }}</td>
                                        <td class="py-2 font-semibold">R$ {{ number_format((float) $item->subtotal, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center">
                    <h2 class="text-2xl font-black">Voce ainda nao possui compras</h2>
                    <p class="mt-2 text-slate-600">Assim que concluir seu primeiro pedido, ele aparecera aqui.</p>
                </div>
            @endforelse
        </section>

        @if ($sugestoes->count() > 0)
            <section class="mt-12">
                <h2 class="text-3xl font-black">Sugestoes baseadas nas suas compras</h2>
                <div class="mt-5 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($sugestoes as $produto)
                        <article class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                            <div class="relative overflow-hidden rounded-xl bg-slate-100 aspect-square mb-0">
                                <a href="{{ route('produtos.show', $produto) }}" class="block h-full w-full">
                                    <x-product-image :url="$produto->url_imagem" :ean="$produto->codigo_barras" class="h-full w-full object-contain p-2" />
                                </a>

                                @auth
                                    @php $isFavorito = in_array($produto->id, $favoritosIds ?? [], true); @endphp
                                    <form action="{{ $isFavorito ? route('favoritos.destroy', $produto) : route('favoritos.store', $produto) }}" method="POST" class="absolute right-3 top-3 z-20">
                                        @csrf
                                        @if ($isFavorito)
                                            @method('DELETE')
                                        @endif
                                        <button type="submit" aria-label="{{ $isFavorito ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}" class="h-10 w-10 rounded-full shadow flex items-center justify-center transition focus:outline-none {{ $isFavorito ? 'bg-red-600 text-white' : 'bg-white text-amber-500 border border-slate-200' }}">
                                            <i class="fa-solid fa-heart"></i>
                                        </button>
                                    </form>
                                @endauth
                            </div>
                            <h3 class="mt-3 line-clamp-2 text-base font-black">{{ $produto->nome }}</h3>
                            <p class="text-xs text-slate-500">{{ $produto->categoria ?? 'Sem categoria' }}</p>
                            <p class="mt-2 text-2xl font-black text-blue-700">R$ {{ number_format((float) $produto->preco_atual, 2, ',', '.') }}</p>

                            <form action="{{ route('carrinho.add', $produto) }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="quantidade" value="1">
                                <button class="w-full rounded-xl bg-slate-900 py-2 text-sm font-bold text-white hover:bg-slate-800">Comprar novamente</button>
                            </form>

                            @auth
                                @php $isFavorito = in_array($produto->id, $favoritosIds ?? [], true); @endphp
                                <form action="{{ $isFavorito ? route('favoritos.destroy', $produto) : route('favoritos.store', $produto) }}" method="POST" class="mt-3">
                                    @csrf
                                    @if ($isFavorito)
                                        @method('DELETE')
                                    @endif
                                    <button class="w-full rounded-xl border border-amber-200 py-2 text-sm font-bold text-amber-700 hover:bg-amber-50">
                                        {{ $isFavorito ? 'Remover favorito' : 'Favoritar' }}
                                    </button>
                                </form>
                            @endauth
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </main>
</body>
</html>
