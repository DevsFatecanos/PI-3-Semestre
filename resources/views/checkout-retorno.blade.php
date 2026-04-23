<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retorno do Checkout | Distribuidora Foccus</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
</head>
<body class="min-h-screen bg-slate-950 px-4 py-14 text-white">
    <main class="mx-auto max-w-2xl rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl md:p-8">
        @php
            $cores = [
                'approved' => 'text-emerald-400',
                'pending' => 'text-amber-400',
                'failure' => 'text-red-400',
            ];
        @endphp

        <p class="text-xs font-bold uppercase tracking-[0.35em] text-slate-400">Checkout</p>
        <h1 class="mt-3 text-3xl font-black md:text-4xl">Status do pagamento</h1>

        <p class="mt-6 text-sm uppercase tracking-[0.2em] text-slate-400">Situacao</p>
        <p class="mt-1 text-2xl font-black {{ $cores[$status] ?? 'text-sky-400' }}">{{ strtoupper($status) }}</p>

        <p class="mt-5 text-slate-200">{{ session('success') ?? $mensagemStatus }}</p>

        <div class="mt-8 space-y-3 rounded-2xl border border-white/10 bg-black/20 p-4 text-sm text-slate-300">
            <p><span class="font-bold text-white">Referencia:</span> {{ $referencia }}</p>
            <p><span class="font-bold text-white">Gateway:</span> {{ $provedor }}</p>
        </div>

        <div class="mt-8 grid gap-3 sm:grid-cols-2">
            <a href="/checkout" class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-3 text-sm font-bold text-slate-900 transition hover:bg-slate-200">Tentar novamente</a>
            <a href="/" class="inline-flex items-center justify-center rounded-xl border border-white/20 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/10">Voltar para loja</a>
        </div>
    </main>
</body>
</html>
