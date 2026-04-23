<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho | Distribuidora Foccus</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <style>
        body {
            background:
                radial-gradient(circle at top left, rgba(148, 163, 184, 0.18), transparent 30%),
                linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
        }
    </style>
</head>
<body class="min-h-screen text-slate-900">
    <nav class="bg-slate-500 text-white p-4 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center gap-8">
            <a href="/" class="flex-shrink-0">
                <img src="/LOGO_FOCCUS.png" class="w-40 brightness-0 invert" alt="Logo Foccus">
            </a>

            <div class="flex items-center gap-3">
                <a href="/carrinho" class="relative bg-slate-800 px-4 py-2 rounded-full hover:bg-slate-700 transition flex items-center gap-2">
                    <span class="text-sm">🛒</span>
                    <span class="text-xs font-bold hidden md:inline">Carrinho</span>
                    @if ($quantidadeTotal > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-black rounded-full w-5 h-5 flex items-center justify-center">
                            {{ $quantidadeTotal }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-10 md:py-14">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-500">Resumo do pedido</p>
            <h1 class="mt-2 text-4xl md:text-5xl font-black text-slate-900">Carrinho</h1>
            <p class="mt-3 max-w-2xl text-slate-600">Revise os itens, ajuste quantidades e siga para a finalização quando estiver pronto.</p>
        </div>

        @if (session('success') || session('error'))
            <div class="mb-6 rounded-xl border px-4 py-3 text-sm {{ session('success') ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-red-200 bg-red-50 text-red-700' }}">
                {{ session('success') ?? session('error') }}
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-[1.5fr_0.9fr]">
            <section class="space-y-4">
                @forelse ($itens as $item)
                    <article class="rounded-3xl border border-slate-100 bg-white p-4 shadow-sm md:p-5">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center">
                            <div class="h-24 w-24 overflow-hidden rounded-2xl bg-slate-100 shrink-0">
                                <img src="{{ $item['produto']->url_imagem }}" alt="{{ $item['produto']->nome }}" class="h-full w-full object-cover">
                            </div>

                            <div class="flex-1">
                                <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
                                    <div>
                                        <h2 class="text-lg font-bold text-slate-900">{{ $item['produto']->nome }}</h2>
                                        <p class="text-sm text-slate-500">{{ $item['produto']->marca ?? 'Marca não informada' }}</p>
                                    </div>
                                    <div class="text-left md:text-right">
                                        <p class="text-sm text-slate-500">Subtotal</p>
                                        <p class="text-xl font-black text-slate-900">R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                                    <form action="{{ route('carrinho.update', $item['produto']) }}" method="POST" class="flex flex-wrap items-end gap-3">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-slate-400">Quantidade</label>
                                            <input type="number" name="quantidade" value="{{ $item['quantidade'] }}" min="1" max="{{ $item['produto']->quantidade }}" class="w-28 rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                                        </div>
                                        <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white transition hover:bg-slate-800">Atualizar</button>
                                    </form>

                                    <form action="{{ route('carrinho.remove', $item['produto']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-xl border border-red-200 px-4 py-2 text-sm font-bold text-red-600 transition hover:bg-red-50">Remover</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-slate-200 bg-white p-10 text-center shadow-sm">
                        <h2 class="text-2xl font-black text-slate-900">Seu carrinho está vazio</h2>
                        <p class="mt-3 text-slate-600">Escolha produtos no catálogo para montar seu pedido.</p>
                        <a href="/" class="mt-6 inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-sm font-bold text-white transition hover:bg-slate-800">Voltar ao catálogo</a>
                    </div>
                @endforelse
            </section>

            <aside class="h-fit rounded-3xl bg-slate-900 p-6 text-white shadow-xl lg:sticky lg:top-24">
                <p class="text-xs font-bold uppercase tracking-[0.35em] text-slate-400">Resumo</p>
                <div class="mt-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-white/10 pb-4 text-sm text-slate-300">
                        <span>Itens no carrinho</span>
                        <span class="font-bold text-white">{{ $quantidadeTotal }}</span>
                    </div>
                    <div class="flex items-end justify-between">
                        <span class="text-sm text-slate-300">Total</span>
                        <span class="text-3xl font-black">R$ {{ number_format($total, 2, ',', '.') }}</span>
                    </div>
                </div>

                <p class="mt-5 text-sm leading-6 text-slate-400">Os valores são recalculados com base no estoque atual do catálogo.</p>

                <form action="{{ route('carrinho.clear') }}" method="POST" class="mt-6">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-xl border border-white/15 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/10">Limpar carrinho</button>
                </form>

                @if ($quantidadeTotal > 0)
                    <a href="/checkout" class="mt-3 inline-flex w-full items-center justify-center rounded-xl bg-emerald-500 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-400">Ir para checkout</a>
                @endif

                <a href="/" class="mt-3 inline-flex w-full items-center justify-center rounded-xl bg-white px-4 py-3 text-sm font-bold text-slate-900 transition hover:bg-slate-100">Continuar comprando</a>
            </aside>
        </div>
    </main>

    <footer class="mt-8 border-t border-slate-200 bg-white/70 px-8 py-6 text-center text-sm text-slate-400 backdrop-blur">
        &copy; {{ date('Y') }} Distribuidora Foccus - Todos os direitos reservados.
    </footer>
</body>
</html>