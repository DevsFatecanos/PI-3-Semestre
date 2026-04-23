<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CheckoutPaymentService
{
    public function criarPagamento(array $itens, array $cliente, string $metodoPreferido): array
    {
        $token = (string) config('services.mercado_pago.access_token', '');

        if ($token !== '') {
            return $this->criarCheckoutMercadoPago($itens, $cliente);
        }

        return $this->simularPagamento($itens, $cliente, $metodoPreferido);
    }

    private function criarCheckoutMercadoPago(array $itens, array $cliente): array
    {
        $payloadItens = array_map(function (array $item): array {
            return [
                'id' => (string) $item['produto']->id,
                'title' => (string) $item['produto']->nome,
                'quantity' => (int) $item['quantidade'],
                'currency_id' => 'BRL',
                'unit_price' => (float) $item['produto']->preco_atual,
            ];
        }, $itens);

        $referencia = 'PED-' . Str::upper(Str::random(10));

        $payload = [
            'items' => $payloadItens,
            'payer' => [
                'name' => $cliente['nome'],
                'email' => $cliente['email'],
            ],
            'external_reference' => $referencia,
            'back_urls' => [
                'success' => route('checkout.retorno'),
                'pending' => route('checkout.retorno'),
                'failure' => route('checkout.retorno'),
            ],
            'auto_return' => 'approved',
        ];

        $webhookUrl = (string) config('services.mercado_pago.webhook_url', '');

        if ($webhookUrl !== '') {
            $payload['notification_url'] = $webhookUrl;
        }

        $response = Http::withToken(config('services.mercado_pago.access_token'))
            ->withHeaders(['X-Idempotency-Key' => (string) Str::uuid()])
            ->post('https://api.mercadopago.com/checkout/preferences', $payload);

        if (! $response->successful()) {
            throw new \RuntimeException('Falha ao criar pagamento no Mercado Pago.');
        }

        $data = $response->json();
        $usarSandbox = (bool) config('services.mercado_pago.sandbox', true);
        $checkoutUrl = $usarSandbox
            ? ($data['sandbox_init_point'] ?? $data['init_point'] ?? null)
            : ($data['init_point'] ?? null);

        if (! is_string($checkoutUrl) || $checkoutUrl === '') {
            throw new \RuntimeException('Gateway retornou checkout sem URL valida.');
        }

        return [
            'tipo' => 'redirect',
            'provedor' => 'mercado_pago',
            'referencia' => $referencia,
            'checkout_url' => $checkoutUrl,
        ];
    }

    private function simularPagamento(array $itens, array $cliente, string $metodoPreferido): array
    {
        $metodos = [
            'pix' => 'PIX',
            'cartao' => 'Cartao',
            'boleto' => 'Boleto',
        ];

        $metodoLabel = $metodos[$metodoPreferido] ?? 'Pagamento';

        return [
            'tipo' => 'simulado',
            'provedor' => 'simulador_local',
            'referencia' => 'SIM-' . Str::upper(Str::random(8)),
            'mensagem' => "Pagamento {$metodoLabel} aprovado em ambiente de desenvolvimento.",
            'detalhes' => [
                'cliente' => $cliente,
                'itens' => count($itens),
            ],
        ];
    }
}
