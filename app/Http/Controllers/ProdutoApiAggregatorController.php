<?php

namespace App\Http\Controllers;

use App\Services\ProdutoApiAggregatorService;
use Illuminate\Http\JsonResponse;

class ProdutoApiAggregatorController extends Controller
{
    protected ProdutoApiAggregatorService $aggregatorService;

    public function __construct(ProdutoApiAggregatorService $aggregatorService)
    {
        $this->aggregatorService = $aggregatorService;
    }

    /**
     * Get product data from all available sources
     * GET /api/produtos/{ean}
     */
    public function obterProduto(string $ean): JsonResponse
    {
        $produto = $this->aggregatorService->obterProdutoCompleto($ean);

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
            'message' => 'Produto não encontrado em nenhuma fonte',
        ], 404);
    }

    /**
     * Get product image from best available source
     * GET /api/produtos/{ean}/imagem
     */
    public function obterImagem(string $ean): JsonResponse
    {
        $imagem = $this->aggregatorService->obterImagem($ean);

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
            'message' => 'Imagem não encontrada em nenhuma fonte',
        ], 404);
    }

    /**
     * Get product name from best available source
     * GET /api/produtos/{ean}/nome
     */
    public function obterNome(string $ean): JsonResponse
    {
        $nome = $this->aggregatorService->obterNome($ean);

        if ($nome) {
            return response()->json([
                'status' => 'success',
                'ean' => $ean,
                'nome' => $nome,
            ]);
        }

        return response()->json([
            'status' => 'not_found',
            'ean' => $ean,
            'message' => 'Nome não encontrado',
        ], 404);
    }

    /**
     * Get product brand from best available source
     * GET /api/produtos/{ean}/marca
     */
    public function obterMarca(string $ean): JsonResponse
    {
        $marca = $this->aggregatorService->obterMarca($ean);

        if ($marca) {
            return response()->json([
                'status' => 'success',
                'ean' => $ean,
                'marca' => $marca,
            ]);
        }

        return response()->json([
            'status' => 'not_found',
            'ean' => $ean,
            'message' => 'Marca não encontrada',
        ], 404);
    }

    /**
     * Get which sources have data for this EAN
     * GET /api/produtos/{ean}/fontes
     */
    public function verificarFontes(string $ean): JsonResponse
    {
        $fontes = $this->aggregatorService->verificarFontes($ean);

        return response()->json([
            'status' => 'success',
            'ean' => $ean,
            'fontes_disponiveis' => $fontes,
        ]);
    }
}
