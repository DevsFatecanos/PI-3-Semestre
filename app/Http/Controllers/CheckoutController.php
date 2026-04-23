<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CheckoutPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CheckoutPaymentService $checkoutPaymentService,
    ) {
    }

    public function index(): View|RedirectResponse
    {
        $resumo = $this->cartService->resumo();

        if ($resumo['quantidadeTotal'] === 0) {
            return redirect()->to('/carrinho')->with('error', 'Seu carrinho esta vazio.');
        }

        return view('checkout', [
            'itens' => $resumo['itens'],
            'total' => $resumo['total'],
            'quantidadeTotal' => $resumo['quantidadeTotal'],
        ]);
    }

    public function finalizar(Request $request): RedirectResponse|JsonResponse
    {
        $resumo = $this->cartService->resumo();

        if ($resumo['quantidadeTotal'] === 0) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Seu carrinho esta vazio.',
                ], 422);
            }

            return redirect()->to('/carrinho')->with('error', 'Seu carrinho esta vazio.');
        }

        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'metodo_preferido' => ['required', 'in:pix,cartao,boleto'],
            'observacoes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $pagamento = $this->checkoutPaymentService->criarPagamento(
                $resumo['itens'],
                [
                    'nome' => $validated['nome'],
                    'email' => $validated['email'],
                    'telefone' => $validated['telefone'] ?? null,
                ],
                $validated['metodo_preferido'],
            );

            if (($pagamento['tipo'] ?? '') === 'redirect' && ! empty($pagamento['checkout_url'])) {
                session()->put('checkout_cliente', [
                    'nome' => $validated['nome'],
                    'email' => $validated['email'],
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Pagamento iniciado com sucesso.',
                        'redirect_url' => $pagamento['checkout_url'],
                    ]);
                }

                return redirect()->away($pagamento['checkout_url']);
            }

            $this->cartService->limpar();

            $retornoUrl = '/checkout/retorno?' . http_build_query([
                'status' => 'approved',
                'external_reference' => $pagamento['referencia'] ?? null,
                'provedor' => $pagamento['provedor'] ?? 'simulador_local',
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $pagamento['mensagem'] ?? 'Pedido finalizado com sucesso.',
                    'redirect_url' => route('checkout.retorno', [
                        'status' => 'approved',
                        'external_reference' => $pagamento['referencia'] ?? null,
                        'provedor' => $pagamento['provedor'] ?? 'simulador_local',
                    ], false),
                ]);
            }

            return redirect()->away($retornoUrl)->with('success', $pagamento['mensagem'] ?? 'Pedido finalizado com sucesso.');
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nao foi possivel iniciar o pagamento no momento. Tente novamente em instantes.',
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Nao foi possivel iniciar o pagamento no momento. Tente novamente em instantes.');
        }
    }

    public function retorno(Request $request): View
    {
        $status = $request->string('status')->toString();

        if ($status === 'approved') {
            $this->cartService->limpar();
        }

        $mensagens = [
            'approved' => 'Pagamento aprovado! Seu pedido foi confirmado.',
            'pending' => 'Pagamento pendente. Assim que confirmado, seu pedido sera processado.',
            'failure' => 'Pagamento nao concluido. Voce pode tentar novamente.',
        ];

        return view('checkout-retorno', [
            'status' => $status ?: 'pending',
            'mensagemStatus' => $mensagens[$status] ?? 'Retorno recebido. Estamos validando seu pedido.',
            'referencia' => $request->get('external_reference')
                ?? $request->get('merchant_order_id')
                ?? 'N/A',
            'provedor' => $request->get('provedor', 'mercado_pago'),
        ]);
    }
}
