<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout | Distribuidora Foccus</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <style>
        body {
            background:
                radial-gradient(circle at 0% 0%, rgba(16, 185, 129, 0.16), transparent 32%),
                radial-gradient(circle at 100% 100%, rgba(59, 130, 246, 0.14), transparent 36%),
                linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
        }
    </style>
</head>
<body class="min-h-screen text-slate-900">
    <main class="mx-auto max-w-7xl px-4 py-10 md:py-14">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-500">Finalizacao</p>
                <h1 class="mt-2 text-4xl font-black text-slate-900 md:text-5xl">Checkout</h1>
                <p class="mt-3 max-w-2xl text-slate-600">Revise os dados e escolha seu meio de pagamento. Com token do Mercado Pago configurado, o redirecionamento aceita PIX, cartao e boleto.</p>
            </div>
            <a href="/carrinho" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Voltar para o carrinho</a>
        </div>

        @if (session('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm md:p-8">
                <h2 class="text-xl font-black text-slate-900">Dados para pagamento</h2>
                <p class="mt-2 text-sm text-slate-500">Essas informacoes sao usadas para gerar o link de pagamento seguro.</p>

                <form id="checkoutForm" action="/checkout/processar" method="POST" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label for="nome" class="mb-1 block text-sm font-bold text-slate-600">Nome completo</label>
                        <input id="nome" name="nome" value="{{ old('nome') }}" required class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                        @error('nome')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="email" class="mb-1 block text-sm font-bold text-slate-600">E-mail</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                            @error('email')
                                <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="telefone" class="mb-1 block text-sm font-bold text-slate-600">Telefone</label>
                            <input id="telefone" name="telefone" value="{{ old('telefone') }}" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                            @error('telefone')
                                <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <p class="mb-2 text-sm font-bold text-slate-600">Metodo preferido</p>
                        <div class="grid gap-3 sm:grid-cols-3">
                            <label class="rounded-2xl border border-slate-200 p-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300">
                                <input type="radio" name="metodo_preferido" value="pix" class="mr-2" {{ old('metodo_preferido', 'pix') === 'pix' ? 'checked' : '' }}>
                                PIX
                            </label>
                            <label class="rounded-2xl border border-slate-200 p-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300">
                                <input type="radio" name="metodo_preferido" value="cartao" class="mr-2" {{ old('metodo_preferido') === 'cartao' ? 'checked' : '' }}>
                                Cartao
                            </label>
                            <label class="rounded-2xl border border-slate-200 p-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300">
                                <input type="radio" name="metodo_preferido" value="boleto" class="mr-2" {{ old('metodo_preferido') === 'boleto' ? 'checked' : '' }}>
                                Boleto
                            </label>
                        </div>
                        @error('metodo_preferido')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="observacoes" class="mb-1 block text-sm font-bold text-slate-600">Observacoes</label>
                        <textarea id="observacoes" name="observacoes" rows="4" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200">{{ old('observacoes') }}</textarea>
                        @error('observacoes')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-black text-white transition hover:bg-emerald-400">Finalizar e ir para pagamento</button>
                </form>
            </section>

            <aside class="h-fit rounded-3xl bg-slate-900 p-6 text-white shadow-xl lg:sticky lg:top-8">
                <p class="text-xs font-bold uppercase tracking-[0.35em] text-slate-400">Resumo do pedido</p>
                <div class="mt-5 space-y-4">
                    @foreach ($itens as $item)
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                            <p class="text-sm font-bold">{{ $item['produto']->nome }}</p>
                            <p class="mt-1 text-xs text-slate-300">Qtd: {{ $item['quantidade'] }}</p>
                            <p class="mt-1 text-sm font-black">R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 border-t border-white/10 pt-4">
                    <div class="mb-2 flex justify-between text-sm text-slate-300">
                        <span>Itens</span>
                        <span>{{ $quantidadeTotal }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-black">
                        <span>Total</span>
                        <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <script>
        const checkoutForm = document.getElementById('checkoutForm');

        if (checkoutForm) {
            checkoutForm.addEventListener('submit', async function (event) {
                event.preventDefault();

                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const submitButton = checkoutForm.querySelector('button[type="submit"]');
                const originalText = submitButton?.textContent;

                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Processando...';
                }

                try {
                    const response = await fetch(checkoutForm.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token || '',
                        },
                        body: new FormData(checkoutForm),
                    });

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Nao foi possivel finalizar o pedido.');
                    }

                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                        return;
                    }

                    window.location.href = '/checkout/retorno';
                } catch (error) {
                    alert(error.message || 'Nao foi possivel finalizar o pedido.');
                } finally {
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.textContent = originalText || 'Finalizar e ir para pagamento';
                    }
                }
            });
        }
    </script>
</body>
</html>
