<?php

namespace App\Services;

use Illuminate\Support\Str;

class ProductImageResolver
{
    public function resolve(?string $nome, ?string $marca = null, ?string $categoria = null, ?string $codigoBarras = null): string
    {
        if (!empty($codigoBarras)) {
            $ean = preg_replace('/\D/', '', $codigoBarras);
            if (strlen($ean) >= 8) {
                return "https://cdn-cosmos.bluesoft.com.br/products/{$ean}";
            }
        }

        return asset('/LOGO_FOCCUS.png');
    }
}
