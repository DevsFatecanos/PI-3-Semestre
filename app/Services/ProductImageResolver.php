<?php

namespace App\Services;

use Illuminate\Support\Str;

class ProductImageResolver
{
    public function resolve(?string $nome, ?string $marca = null, ?string $categoria = null, ?string $codigoBarras = null): string
    {
        $keywords = array_filter([
            $this->categoryKeyword($categoria),
            $this->brandKeyword($marca),
            $this->nameKeyword($nome),
        ]);

        $query = trim(implode(' ', array_unique($keywords)));

        if ($query === '') {
            $query = 'product';
        }

        $seed = $codigoBarras ?: Str::slug($nome ?: $query);

        $photoId = abs(crc32($seed)) % 1084;

        return 'https://picsum.photos/id/' . $photoId . '/900/900';
    }

    private function categoryKeyword(?string $categoria): string
    {
        $categoriaSlug = Str::lower((string) $categoria);

        return match (true) {
            str_contains($categoriaSlug, 'beb') => 'beverage',
            str_contains($categoriaSlug, 'limpeza') => 'cleaning product',
            str_contains($categoriaSlug, 'higiene') => 'personal care',
            str_contains($categoriaSlug, 'mercearia') => 'grocery',
            str_contains($categoriaSlug, 'bazar') => 'household product',
            default => 'product',
        };
    }

    private function brandKeyword(?string $marca): string
    {
        $marcaSlug = Str::lower((string) $marca);

        return match (true) {
            str_contains($marcaSlug, 'dove') => 'dove deodorant',
            str_contains($marcaSlug, 'axe') => 'axe deodorant',
            str_contains($marcaSlug, 'rexona') => 'rexona deodorant',
            str_contains($marcaSlug, 'nivea') => 'nivea personal care',
            str_contains($marcaSlug, 'softys') => 'paper tissue',
            str_contains($marcaSlug, 'pompom') => 'baby care',
            str_contains($marcaSlug, 'piracanjuba') => 'milk',
            str_contains($marcaSlug, 'canoinhas') => 'paper towel',
            str_contains($marcaSlug, 'aromasil') => 'cleaning product',
            str_contains($marcaSlug, 'kelldrin') => 'cleaning product',
            str_contains($marcaSlug, 'luar magico') => 'cleaning product',
            str_contains($marcaSlug, 'vela aib') => 'candle',
            str_contains($marcaSlug, 'upa bebe') => 'baby care',
            default => 'product',
        };
    }

    private function nameKeyword(?string $nome): string
    {
        $nome = Str::lower((string) $nome);
        $tokens = array_filter(explode(' ', preg_replace('/[^\pL\pN ]+/u', ' ', $nome) ?: ''));
        $tokens = array_slice($tokens, 0, 3);

        return trim(implode(' ', $tokens));
    }
}
