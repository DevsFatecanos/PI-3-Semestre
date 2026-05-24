<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Vendas</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;padding:20px}
        table{width:100%;border-collapse:collapse}
        th,td{border:1px solid #ddd;padding:8px}
        th{background:#f4f4f4}
    </style>
</head>
<body>
    <h2>Relatório de Vendas</h2>
    <p>Gerado em {{ now()->toDateTimeString() }}</p>
    <table>
        <thead>
            <tr>
                <th>Pedido</th>
                <th>Referência</th>
                <th>Data</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Itens</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->referencia }}</td>
                <td>{{ $p->data_pagamento?->toDateTimeString() ?? '' }}</td>
                <td>{{ $p->nome_cliente }}</td>
                <td>R$ {{ number_format($p->total,2,',','.') }}</td>
                <td>{{ $p->itens->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
