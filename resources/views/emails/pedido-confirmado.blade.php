<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmacao do pedido</title>
</head>
<body style="font-family: Arial, sans-serif; color: #0f172a; line-height: 1.5; background: #f8fafc; padding: 24px;">
    <div style="max-width: 720px; margin: 0 auto; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px;">
        <h1 style="margin-top: 0;">Pedido confirmado</h1>
        <p>Ola, {{ $pedido->nome_cliente }}. Sua compra foi confirmada com sucesso.</p>

        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin: 18px 0;">
            <p style="margin: 4px 0;"><strong>Referencia:</strong> {{ $pedido->referencia }}</p>
            <p style="margin: 4px 0;"><strong>Data da compra:</strong> {{ optional($pedido->data_pagamento ?? $pedido->created_at)->format('d/m/Y H:i') }}</p>
            <p style="margin: 4px 0;"><strong>Meio de pagamento:</strong> {{ strtoupper($pedido->metodo_pagamento) }}</p>
            <p style="margin: 4px 0;"><strong>Total pago:</strong> R$ {{ number_format((float) $pedido->total, 2, ',', '.') }}</p>
        </div>

        <h2 style="margin-top: 0;">Itens</h2>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align: left; padding: 8px; border-bottom: 1px solid #cbd5e1;">Produto</th>
                    <th style="text-align: left; padding: 8px; border-bottom: 1px solid #cbd5e1;">Qtd</th>
                    <th style="text-align: left; padding: 8px; border-bottom: 1px solid #cbd5e1;">Preco</th>
                    <th style="text-align: left; padding: 8px; border-bottom: 1px solid #cbd5e1;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedido->itens as $item)
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{ $item->nome_produto }}</td>
                        <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{ $item->quantidade }}</td>
                        <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">R$ {{ number_format((float) $item->preco_unitario, 2, ',', '.') }}</td>
                        <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">R$ {{ number_format((float) $item->subtotal, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top: 18px;">Obrigado por comprar com a gente.</p>
    </div>
</body>
</html>
