<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Produtos</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;padding:20px}
        table{width:100%;border-collapse:collapse}
        th,td{border:1px solid #ddd;padding:8px}
        th{background:#f4f4f4}
    </style>
</head>
<body>
    <h2>Relatório de Produtos</h2>
    <p>Gerado em {{ now()->toDateTimeString() }}</p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Estoque</th>
                <th>Preço Atual</th>
                <th>Preço de Custo</th>
                <th>Total Vendido (Qtd)</th>
                <th>Total Vendido (R$)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->nome }}</td>
                <td>{{ $r->categoria }}</td>
                <td>{{ $r->quantidade }}</td>
                <td>R$ {{ number_format($r->preco_atual,2,',','.') }}</td>
                <td>R$ {{ number_format($r->preco_de_custo ?? 0,2,',','.') }}</td>
                <td>{{ $r->total_qtd ?? 0 }}</td>
                <td>R$ {{ number_format($r->total_venda ?? 0,2,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
