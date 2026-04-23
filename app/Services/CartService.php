<?php

namespace App\Services;

use App\Models\Produto;

class CartService
{
    public function resumo(): array
    {
        $itens = $this->montarItens();

        return [
            'itens' => $itens,
            'total' => collect($itens)->sum('subtotal'),
            'quantidadeTotal' => collect($itens)->sum('quantidade'),
        ];
    }

    public function limpar(): void
    {
        session()->forget('carrinho');
    }

    private function montarItens(): array
    {
        $carrinho = session()->get('carrinho', []);
        $produtos = Produto::whereIn('id', array_keys($carrinho))->get()->keyBy('id');

        $itens = [];
        $carrinhoLimpo = [];

        foreach ($carrinho as $produtoId => $quantidadeSolicitada) {
            $produto = $produtos->get($produtoId);

            if (! $produto) {
                continue;
            }

            $estoqueDisponivel = (int) $produto->quantidade;

            if ($estoqueDisponivel <= 0) {
                continue;
            }

            $quantidade = min((int) $quantidadeSolicitada, $estoqueDisponivel);

            if ($quantidade <= 0) {
                continue;
            }

            $subtotal = $quantidade * (float) $produto->preco_atual;

            $itens[] = [
                'produto' => $produto,
                'quantidade' => $quantidade,
                'subtotal' => $subtotal,
            ];

            $carrinhoLimpo[$produto->id] = $quantidade;
        }

        session()->put('carrinho', $carrinhoLimpo);

        return $itens;
    }
}
