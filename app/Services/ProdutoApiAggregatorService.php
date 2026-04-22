<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ProdutoApiAggregatorService
{
    protected EanPicturesService $eanPicturesService;
    protected BlueSoftService $blueSoftService;

    public function __construct(
        EanPicturesService $eanPicturesService,
        BlueSoftService $blueSoftService
    ) {
        $this->eanPicturesService = $eanPicturesService;
        $this->blueSoftService = $blueSoftService;
    }

    /**
     * Get product image trying multiple sources
     * Priority: BlueSoft > EanPictures > null
     */
    public function obterImagem(string $ean): ?string
    {
        // Try BlueSoft first (usually better quality images)
        $imagem = $this->blueSoftService->obterImagem($ean);
        if ($imagem) {
            Log::info("Imagem obtida de BlueSoft para EAN: {$ean}");
            return $imagem;
        }

        // Fallback to EanPictures
        $imagem = $this->eanPicturesService->obterImagem($ean);
        if ($imagem) {
            Log::info("Imagem obtida de EanPictures para EAN: {$ean}");
            return $imagem;
        }

        Log::warning("Imagem não encontrada para EAN: {$ean}");
        return null;
    }

    /**
     * Get product name trying multiple sources
     * Priority: BlueSoft > EanPictures > null
     */
    public function obterNome(string $ean): ?string
    {
        $nome = $this->blueSoftService->obterNome($ean);
        if ($nome) {
            return $nome;
        }

        // Try EanPictures description endpoint
        $descricao = $this->eanPicturesService->obterDescricao($ean);
        if ($descricao && isset($descricao['Nome'])) {
            return $descricao['Nome'];
        }

        return null;
    }

    /**
     * Get product brand trying multiple sources
     * Priority: BlueSoft > EanPictures > null
     */
    public function obterMarca(string $ean): ?string
    {
        $marca = $this->blueSoftService->obterMarca($ean);
        if ($marca) {
            return $marca;
        }

        $descricao = $this->eanPicturesService->obterDescricao($ean);
        if ($descricao && isset($descricao['Marca'])) {
            return $descricao['Marca'];
        }

        return null;
    }

    /**
     * Get product category trying multiple sources
     * Priority: BlueSoft > EanPictures > null
     */
    public function obterCategoria(string $ean): ?string
    {
        $categoria = $this->blueSoftService->obterCategoria($ean);
        if ($categoria) {
            return $categoria;
        }

        $descricao = $this->eanPicturesService->obterDescricao($ean);
        if ($descricao && isset($descricao['Categoria'])) {
            return $descricao['Categoria'];
        }

        return null;
    }

    /**
     * Get product description trying multiple sources
     * Priority: BlueSoft > EanPictures > null
     */
    public function obterDescricao(string $ean): ?string
    {
        $descricao = $this->blueSoftService->obterDescricao($ean);
        if ($descricao) {
            return $descricao;
        }

        return null;
    }

    /**
     * Get complete product data from all available sources
     * Merges data from BlueSoft and EanPictures for maximum information
     */
    public function obterProdutoCompleto(string $ean): ?array
    {
        $dadosBlueSoft = $this->blueSoftService->obterProdutoCompleto($ean);
        $dadosEanPictures = $this->eanPicturesService->obterProdutoCompleto($ean);

        // If neither API has data, return null
        if (!$dadosBlueSoft && !$dadosEanPictures) {
            return null;
        }

        // Merge data with BlueSoft as primary source
        $dadosMergidos = [
            'ean' => $ean,
            'nome' => $dadosBlueSoft['nome'] ?? $dadosEanPictures['Nome'] ?? null,
            'descricao' => $dadosBlueSoft['descricao'] ?? $dadosEanPictures['descricao'] ?? null,
            'marca' => $dadosBlueSoft['marca'] ?? $dadosEanPictures['Marca'] ?? null,
            'categoria' => $dadosBlueSoft['categoria'] ?? $dadosEanPictures['Categoria'] ?? null,
            'preco' => $dadosBlueSoft['preco'] ?? null,
            'imagem_url' => $dadosBlueSoft['imagem_url'] ?? $dadosEanPictures['imagem_url'] ?? null,
            'fonte_imagem' => $dadosBlueSoft['imagem_url'] ? 'bluesoft' : ($dadosEanPictures['imagem_url'] ? 'eanpictures' : null),
            'sku' => $dadosBlueSoft['sku'] ?? null,
            'peso' => $dadosBlueSoft['peso'] ?? null,
            'tem_foto_bluesoft' => isset($dadosBlueSoft['imagem_url']),
            'tem_foto_eanpictures' => isset($dadosEanPictures['imagem_url']),
            'dados_bluesoft' => $dadosBlueSoft,
            'dados_eanpictures' => $dadosEanPictures,
        ];

        return $dadosMergidos;
    }

    /**
     * Check which sources have data for a specific EAN
     */
    public function verificarFontes(string $ean): array
    {
        return [
            'bluesoft' => $this->blueSoftService->obterProduto($ean) !== null,
            'eanpictures' => $this->eanPicturesService->obterDescricao($ean) !== null,
        ];
    }
}
