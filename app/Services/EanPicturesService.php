<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EanPicturesService
{
    private const BASE_URL = 'http://www.eanpictures.com.br:9000/api';

    /**
     * Get product image by EAN/barcode
     * Returns the image URL or null if not found
     */
    public function obterImagem(string $ean): ?string
    {
        if (!$this->validarEan($ean)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get(self::BASE_URL . "/gtin/{$ean}");
            
            if ($response->successful()) {
                // A API retorna a imagem diretamente como arquivo PNG
                // Neste caso, retornamos a URL da imagem
                return self::BASE_URL . "/gtin/{$ean}";
            }

            return null;
        } catch (\Exception $e) {
            Log::warning("Erro ao buscar imagem EAN: {$ean}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get product description by EAN/barcode
     * Returns array with product details or null if not found
     */
    public function obterDescricao(string $ean): ?array
    {
        if (!$this->validarEan($ean)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get(self::BASE_URL . "/desc/{$ean}");
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Verifica se o status é 200 (sucesso)
                if (isset($data['Status']) && $data['Status'] === '200') {
                    return $data;
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::warning("Erro ao buscar descrição EAN: {$ean}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get product description in JSON format with more details
     * Returns array with 200 fields or 404 values if not found
     */
    public function obterDescricao200(string $ean): ?array
    {
        if (!$this->validarEan($ean)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get(self::BASE_URL . "/desc200/{$ean}");
            
            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::warning("Erro ao buscar descrição 200 EAN: {$ean}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get product description in INI format
     * Returns string with INI format data or null if not found
     */
    public function obterDescricaoIni(string $ean): ?string
    {
        if (!$this->validarEan($ean)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get(self::BASE_URL . "/desc_ini/{$ean}");
            
            if ($response->successful()) {
                return $response->body();
            }

            return null;
        } catch (\Exception $e) {
            Log::warning("Erro ao buscar descrição INI EAN: {$ean}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Check if product image exists
     * Returns 'Sim' or 'Nao'
     */
    public function verificarFoto(string $ean): ?string
    {
        if (!$this->validarEan($ean)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get(self::BASE_URL . "/fotoexiste/{$ean}");
            
            if ($response->successful()) {
                return trim($response->body());
            }

            return null;
        } catch (\Exception $e) {
            Log::warning("Erro ao verificar foto EAN: {$ean}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Check if product image exists (JSON response)
     * Returns array with status and description or null if error
     */
    public function verificarFotoJson(string $ean): ?array
    {
        if (!$this->validarEan($ean)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get(self::BASE_URL . "/fotoexistej/{$ean}");
            
            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::warning("Erro ao verificar foto JSON EAN: {$ean}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get unit of measurement description
     * Returns string with unit description or null if not found
     */
    public function obterUnidade(string $codigo): ?string
    {
        if (empty($codigo)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get(self::BASE_URL . "/um/{$codigo}");
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Verifica se o status é 200 (sucesso)
                if (isset($data['Status']) && $data['Status'] === '200') {
                    return $data['nome'] ?? null;
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::warning("Erro ao buscar unidade: {$codigo}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get unit of measurement description (alternative endpoint)
     * Returns string with unit description or null if not found
     */
    public function obterUnidade2(string $codigo): ?string
    {
        if (empty($codigo)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get(self::BASE_URL . "/um2/{$codigo}");
            
            if ($response->successful()) {
                return trim($response->body());
            }

            return null;
        } catch (\Exception $e) {
            Log::warning("Erro ao buscar unidade2: {$codigo}", ['error' => $e->getMessage()]);
            return null;
        }
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
     * Get complete product information from EanPictures
     * Combines description and image availability
     */
    public function obterProdutoCompleto(string $ean): ?array
    {
        if (!$this->validarEan($ean)) {
            return null;
        }

        $descricao = $this->obterDescricao($ean);
        $temFoto = $this->verificarFoto($ean) === 'Sim';
        $fotoUrl = $temFoto ? $this->obterImagem($ean) : null;

        if ($descricao) {
            $descricao['imagem_url'] = $fotoUrl;
            $descricao['tem_foto'] = $temFoto;
            return $descricao;
        }

        return null;
    }
}
