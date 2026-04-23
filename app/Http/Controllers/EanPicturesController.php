<?php

namespace App\Http\Controllers;

use App\Services\EanPicturesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EanPicturesController extends Controller
{
    protected EanPicturesService $eanService;

    public function __construct(EanPicturesService $eanService)
    {
        $this->eanService = $eanService;
    }

    /**
     * Obter imagem do produto por EAN
     * GET /api/ean-pictures/{ean}/imagem
     */
    public function obterImagem(string $ean): JsonResponse
    {
        $imagemUrl = $this->eanService->obterImagem($ean);

        if ($imagemUrl) {
            return response()->json([
                'status' => 'success',
                'ean' => $ean,
                'imagem_url' => $imagemUrl,
            ]);
        }

        return response()->json([
            'status' => 'not_found',
            'ean' => $ean,
            'message' => 'Imagem não encontrada para este EAN',
        ], 404);
    }

    /**
     * Obter descrição do produto por EAN
     * GET /api/ean-pictures/{ean}/descricao
     */
    public function obterDescricao(string $ean): JsonResponse
    {
        $descricao = $this->eanService->obterDescricao($ean);

        if ($descricao && $descricao['Status'] === '200') {
            return response()->json([
                'status' => 'success',
                'ean' => $ean,
                'data' => $descricao,
            ]);
        }

        return response()->json([
            'status' => 'not_found',
            'ean' => $ean,
            'message' => 'Descrição não encontrada para este EAN',
        ], 404);
    }

    /**
     * Obter descrição completa (200 fields) do produto por EAN
     * GET /api/ean-pictures/{ean}/descricao-200
     */
    public function obterDescricao200(string $ean): JsonResponse
    {
        $descricao = $this->eanService->obterDescricao200($ean);

        if ($descricao && $descricao['Status'] === '200') {
            return response()->json([
                'status' => 'success',
                'ean' => $ean,
                'data' => $descricao,
            ]);
        }

        return response()->json([
            'status' => 'not_found',
            'ean' => $ean,
            'message' => 'Descrição não encontrada para este EAN',
        ], 404);
    }

    /**
     * Obter descrição em formato INI do produto por EAN
     * GET /api/ean-pictures/{ean}/descricao-ini
     */
    public function obterDescricaoIni(string $ean): JsonResponse
    {
        $descricaoIni = $this->eanService->obterDescricaoIni($ean);

        if ($descricaoIni) {
            return response()->json([
                'status' => 'success',
                'ean' => $ean,
                'data' => $descricaoIni,
            ]);
        }

        return response()->json([
            'status' => 'not_found',
            'ean' => $ean,
            'message' => 'Descrição INI não encontrada para este EAN',
        ], 404);
    }

    /**
     * Verificar se existe foto para o produto
     * GET /api/ean-pictures/{ean}/verificar-foto
     */
    public function verificarFoto(string $ean): JsonResponse
    {
        $temFoto = $this->eanService->verificarFoto($ean);

        if ($temFoto !== null) {
            return response()->json([
                'status' => 'success',
                'ean' => $ean,
                'tem_foto' => $temFoto === 'Sim',
                'resposta' => $temFoto,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'ean' => $ean,
            'message' => 'Erro ao verificar foto',
        ], 500);
    }

    /**
     * Verificar se existe foto (JSON response)
     * GET /api/ean-pictures/{ean}/verificar-foto-json
     */
    public function verificarFotoJson(string $ean): JsonResponse
    {
        $resultado = $this->eanService->verificarFotoJson($ean);

        if ($resultado) {
            return response()->json([
                'status' => 'success',
                'ean' => $ean,
                'data' => $resultado,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'ean' => $ean,
            'message' => 'Erro ao verificar foto',
        ], 500);
    }

    /**
     * Obter unidade de medida
     * GET /api/ean-pictures/unidade/{codigo}
     */
    public function obterUnidade(string $codigo): JsonResponse
    {
        $unidade = $this->eanService->obterUnidade($codigo);

        if ($unidade) {
            return response()->json([
                'status' => 'success',
                'codigo' => $codigo,
                'unidade' => $unidade,
            ]);
        }

        return response()->json([
            'status' => 'not_found',
            'codigo' => $codigo,
            'message' => 'Unidade de medida não encontrada',
        ], 404);
    }

    /**
     * Obter produto completo (descrição + imagem)
     * GET /api/ean-pictures/{ean}/completo
     */
    public function obterProdutoCompleto(string $ean): JsonResponse
    {
        $produto = $this->eanService->obterProdutoCompleto($ean);

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
            'message' => 'Produto não encontrado',
        ], 404);
    }
}
