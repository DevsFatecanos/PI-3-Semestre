<?php

namespace App\Services;

use App\Mail\PedidoConfirmadoMail;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PedidoService
{
    public function criarPedido(
        array $itens,
        array $cliente,
        string $metodoPagamento,
        array $pagamento,
        ?string $observacoes = null,
    ): Pedido {
        $status = ($pagamento['tipo'] ?? '') === 'simulado' ? 'approved' : 'pending';

        $pedido = Pedido::create([
            'user_id' => Auth::id(),
            'nome_cliente' => (string) $cliente['nome'],
            'email_cliente' => (string) $cliente['email'],
            'telefone_cliente' => $cliente['telefone'] ?? null,
            'metodo_pagamento' => $metodoPagamento,
            'status' => $status,
            'referencia' => (string) ($pagamento['referencia'] ?? ('PED-' . strtoupper(uniqid()))),
            'provedor' => (string) ($pagamento['provedor'] ?? 'simulador_local'),
            'total' => collect($itens)->sum('subtotal'),
            'observacoes' => $observacoes,
            'data_pagamento' => $status === 'approved' ? now() : null,
        ]);

        foreach ($itens as $item) {
            $produto = $item['produto'];

            $pedido->itens()->create([
                'produto_id' => $produto->id,
                'nome_produto' => $produto->nome,
                'categoria_produto' => $produto->categoria,
                'preco_unitario' => (float) $produto->preco_atual,
                'quantidade' => (int) $item['quantidade'],
                'subtotal' => (float) $item['subtotal'],
            ]);
        }

        if ($pedido->status === 'approved') {
            $this->enviarEmailConfirmacao($pedido);
        }

        return $pedido;
    }

    public function marcarComoAprovadoPorReferencia(string $referencia): ?Pedido
    {
        $pedido = Pedido::where('referencia', $referencia)->first();

        if (! $pedido) {
            return null;
        }

        if ($pedido->status !== 'approved') {
            $pedido->update([
                'status' => 'approved',
                'data_pagamento' => now(),
            ]);
        }

        $this->enviarEmailConfirmacao($pedido);

        return $pedido;
    }

    private function enviarEmailConfirmacao(Pedido $pedido): void
    {
        if ($pedido->email_enviado_em !== null) {
            return;
        }

        Mail::to($pedido->email_cliente)->send(new PedidoConfirmadoMail($pedido->loadMissing('itens')));

        $pedido->update([
            'email_enviado_em' => now(),
        ]);
    }
}
