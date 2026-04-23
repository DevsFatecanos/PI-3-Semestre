<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BlueSoftService
{
    private const BASE_URL = 'https://cdn-cosmos.bluesoft.com.br/products';

    /**
     * Get product image URL from BlueSoft
     * BlueSoft API returns the image directly (PNG file)
     * If exists (200), returns the URL; if not (404), returns null
     */
    public function obterImagem(string $ean): ?string
    {
        if (!$this->validarEan($ean)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->head(self::BASE_URL . "/{$ean}");
            
            // Se a imagem existe (status 200 ou 2xx), retorna a URL
            if ($response->successful()) {
                return self::BASE_URL . "/{$ean}";
            }

            return null;
        } catch (\Exception $e) {
            Log::warning("Erro ao verificar imagem BlueSoft EAN: {$ean}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get product data from BlueSoft by EAN
     * Returns array with product information or null if not found
     * Note: BlueSoft API primarily returns images, not JSON data
     */
    public function obterProduto(string $ean): ?array
    {
        $imagemUrl = $this->obterImagem($ean);

        if ($imagemUrl) {
            // BlueSoft API returns image directly, not structured data
            // Return basic structured data with just the image URL
            return [
                'image_url' => $imagemUrl,
                'imagem_url' => $imagemUrl,
            ];
        }

        return null;
    }

    /**
     * Get product name from BlueSoft
     * BlueSoft API doesn't provide detailed product data, only images
     */
    public function obterNome(string $ean): ?string
    {
        // BlueSoft doesn't provide name data, return null
        return null;
    }

    /**
     * Get product description from BlueSoft
     * BlueSoft API doesn't provide detailed product data, only images
     */
    public function obterDescricao(string $ean): ?string
    {
        // BlueSoft doesn't provide description data, return null
        return null;
    }

    /**
     * Get product brand from BlueSoft
     * BlueSoft API doesn't provide detailed product data, only images
     */
    public function obterMarca(string $ean): ?string
    {
        // BlueSoft doesn't provide brand data, return null
        return null;
    }

    /**
     * Get product category from BlueSoft
     * BlueSoft API doesn't provide detailed product data, only images
     */
    public function obterCategoria(string $ean): ?string
    {
        // BlueSoft doesn't provide category data, return null
        return null;
    }

    /**
     * Get product price from BlueSoft
     * BlueSoft API doesn't provide detailed product data, only images
     */
    public function obterPreco(string $ean): ?float
    {
        // BlueSoft doesn't provide price data, return null
        return null;
    }

    /**
     * Get all product details in a single array
     * Returns structured array with available information (mainly image URL)
     */
    public function obterProdutoCompleto(string $ean): ?array
    {
        $imagemUrl = $this->obterImagem($ean);

        if (!$imagemUrl) {
            return null;
        }

        return [
            'ean' => $ean,
            'nome' => null,
            'descricao' => null,
            'marca' => null,
            'categoria' => null,
            'preco' => null,
            'imagem_url' => $imagemUrl,
            'sku' => null,
            'codigo_ncm' => null,
            'peso' => null,
            'fonte' => 'bluesoft',
        ];
    }

    /**
     * Validate EAN/barcode format
     * EAN must be numeric
     */
    private function validarEan(string $ean): bool
    {
        return !empty($ean) && ctype_digit($ean);
    }

    /**
     * Validate product response from BlueSoft API
     */
    private function validarRespostaProduto(?array $data): bool
    {
        if (!is_array($data)) {
            return false;
        }

        // Verifica se tem pelo menos um desses campos
        return isset($data['name']) || isset($data['image_url']) || isset($data['sku']);
    }
}
