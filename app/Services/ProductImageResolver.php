<?php

namespace App\Services;

class ProductImageResolver
{
    private const EAN_PICTURES_API = 'http://www.eanpictures.com.br:9000/api/gtin/';
    private const BLUESOFT_CDN    = 'https://cdn-cosmos.bluesoft.com.br/products/';

    /**
     * Resolve the primary image URL for a product.
     * Priority: custom imagem_url > storage file > BluSoft CDN (by EAN) > LOGO_FOCCUS fallback.
     */
    public function resolve(?string $nome, ?string $marca = null, ?string $categoria = null, ?string $codigoBarras = null): string
    {
        $ean = $this->extractEan($codigoBarras);

        if ($ean) {
            return self::BLUESOFT_CDN . $ean;
        }

        return asset('/LOGO_FOCCUS.png');
    }

    /**
     * Return the EAN Pictures fallback URL for a barcode, or null if not applicable.
     */
    public function fallbackUrl(?string $codigoBarras): ?string
    {
        $ean = $this->extractEan($codigoBarras);

        return $ean ? self::EAN_PICTURES_API . $ean : null;
    }

    /**
     * Extract a valid EAN (8-14 digits) from a barcode string.
     */
    protected function extractEan(?string $codigoBarras): ?string
    {
        if (empty($codigoBarras)) {
            return null;
        }

        $ean = preg_replace('/\D/', '', $codigoBarras);

        return (strlen($ean) >= 8 && strlen($ean) <= 14) ? $ean : null;
    }
}
