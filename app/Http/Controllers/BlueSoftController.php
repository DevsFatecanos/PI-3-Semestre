<?php

namespace App\Http\Controllers;

use App\Services\BlueSoftService;
use Illuminate\Http\JsonResponse;

class BlueSoftController extends Controller
{
    protected BlueSoftService $blueSoftService;

    public function __construct(BlueSoftService $blueSoftService)
    {
        $this->blueSoftService = $blueSoftService;
    }

    /**
     * Get product from BlueSoft by EAN
     * GET /api/bluesoft/{ean}
     */
    public function obterProduto(string $ean): JsonResponse
    {
        $produto = $this->blueSoftService->obterProduto($ean);

        if ($produto) {
            return response()->json([
                'status' => 'success',
                'ean' => $ean,
                'data' => $produto,
            ]);
        }

        return response()->json([
            'status' => 'not_found',
            'ean' => $ean,
            'message' => 'Produto não encontrado na BlueSoft',
        ], 404);
    }

    /**
     * Get product image from BlueSoft
     * GET /api/bluesoft/{ean}/imagem
     */
    public function obterImagem(string $ean): JsonResponse
    {
        $imagem = $this->blueSoftService->obterImagem($ean);

        if ($imagem) {
            return response()->json([
                'status' => 'success',
                'ean' => $ean,
                'imagem_url' => $imagem,
            ]);
        }

        return response()->json([
            'status' => 'not_found',
            'ean' => $ean,
            'message' => 'Imagem não encontrada',
        ], 404);
    }

    /**
     * Get complete product data from BlueSoft
     * GET /api/bluesoft/{ean}/completo
     */
    public function obterProdutoCompleto(string $ean): JsonResponse
    {
        $produto = $this->blueSoftService->obterProdutoCompleto($ean);

        if ($produto) {
            return response()->json([
                'status' => 'success',
                'ean' => $ean,
                'data' => $produto,
            ]);
        }

        return response()->json([
            'status' => 'not_found',
            'ean' => $ean,
            'message' => 'Produto não encontrado na BlueSoft',
        ], 404);
    }
}
