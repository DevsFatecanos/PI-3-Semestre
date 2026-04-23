<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Services\CartService;
use Illuminate\Http\Request;

class CarrinhoController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function getCarrinho()
    {
        $resumo = $this->cartService->resumo();

        return response()->json([
            'carrinho' => $resumo['itens'],
            'total' => $resumo['total'],
            'quantidadeTotal' => $resumo['quantidadeTotal'],
        ]);
    }

    public function index()
    {
        $resumo = $this->cartService->resumo();

        return view('carrinho', [
            'itens' => $resumo['itens'],
            'total' => $resumo['total'],
            'quantidadeTotal' => $resumo['quantidadeTotal'],
        ]);
    }

    public function store(Request $request, Produto $produto)
    {
        $validated = $request->validate([
            'quantidade' => ['required', 'integer', 'min:1'],
        ]);

        if ((int) $produto->quantidade <= 0) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este produto está esgotado.',
                ], 400);
            }
            return back()->with('error', 'Este produto está esgotado.');
        }

        $carrinho = session()->get('carrinho', []);
        $quantidadeAtual = (int) ($carrinho[$produto->id] ?? 0);
        $novaQuantidade = min($quantidadeAtual + (int) $validated['quantidade'], (int) $produto->quantidade);

        $carrinho[$produto->id] = $novaQuantidade;
        session()->put('carrinho', $carrinho);

        if ($request->wantsJson()) {
            $resumo = $this->cartService->resumo();
            
            return response()->json([
                'success' => true,
                'message' => "{$produto->nome} foi adicionado ao carrinho.",
                'carrinho' => $resumo['itens'],
                'total' => $resumo['total'],
                'quantidadeTotal' => $resumo['quantidadeTotal'],
            ]);
        }

        return back()->with('success', "{$produto->nome} foi adicionado ao carrinho.");
    }

    public function update(Request $request, Produto $produto)
    {
        $validated = $request->validate([
            'quantidade' => ['required', 'integer', 'min:1'],
        ]);

        if ((int) $produto->quantidade <= 0) {
            return $this->destroy($request, $produto);
        }

        $carrinho = session()->get('carrinho', []);
        $carrinho[$produto->id] = min((int) $validated['quantidade'], (int) $produto->quantidade);
        session()->put('carrinho', $carrinho);

        if ($request->wantsJson()) {
            $resumo = $this->cartService->resumo();
            
            return response()->json([
                'success' => true,
                'message' => 'Quantidade atualizada.',
                'carrinho' => $resumo['itens'],
                'total' => $resumo['total'],
                'quantidadeTotal' => $resumo['quantidadeTotal'],
            ]);
        }

        return back()->with('success', 'Quantidade atualizada.');
    }

    public function destroy(Request $request, Produto $produto)
    {
        $carrinho = session()->get('carrinho', []);
        unset($carrinho[$produto->id]);
        session()->put('carrinho', $carrinho);

        if ($request->wantsJson()) {
            $resumo = $this->cartService->resumo();
            
            return response()->json([
                'success' => true,
                'message' => 'Produto removido do carrinho.',
                'carrinho' => $resumo['itens'],
                'total' => $resumo['total'],
                'quantidadeTotal' => $resumo['quantidadeTotal'],
            ]);
        }

        return back()->with('success', 'Produto removido do carrinho.');
    }

    public function clear(Request $request)
    {
        $this->cartService->limpar();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Carrinho limpo.',
                'carrinho' => [],
                'total' => 0,
                'quantidadeTotal' => 0,
            ]);
        }

        return back()->with('success', 'Carrinho limpo.');
    }
}