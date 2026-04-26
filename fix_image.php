<?php

$file = 'C:/xampp/htdocs/PI-3-Semestre/resources/views/index.blade.php';
$content = file_get_contents($file);

// Novo bloco PHP para substituir
$newPhpBlock = '@php
                            $ean = preg_replace("/\D/", "", $item->codigo_barras ?? "");
                            $img = $item->url_imagem ?? $item->imagem ?? asset("/LOGO_FOCCUS.png");
                            $eanPictures = $ean ? "http://www.eanpictures.com.br:9000/api/gtin/{$ean}" : "";
                        @endphp';

// Linha da imagem com fallback
$newImgTag = '<img src="{{ $img }}"
                                     @if($eanPictures)
                                     onerror="if(this.src!==\'{{ $eanPictures }}\'){this.src=\'{{ $eanPictures }}\';}else{this.onerror=null;this.src=\'{{ asset("/LOGO_FOCCUS.png") }}\';}"
                                     @else
                                     onerror="this.onerror=null;this.src=\'{{ asset("/LOGO_FOCCUS.png") }\';"
                                     @endif
                                     class="h-52 w-full object-cover">';

// Remove o bloco antigo da variavel $img e a linha anterior @php
$content = preg_replace(
    '/\s+@php\s+\$img = \$item->url_imagem \?\? \$item->imagem \?\? .*?;\s+@endphp\s+<img src="\{\{ \$img \}\}" class="h-52 w-full object-cover">/s',
    "\n                        " . $newPhpBlock . "\n                        <div class=\"relative\">\n                                " . $newImgTag . "\n                                @if(\$temDesconto)",
    $content
);

// O padrão acima removeu o <div class="relative"> e o @if, preciso ajustar
// Vamos fazer de forma mais simples - substituir a linha da imagem apenas

file_put_contents($file, $content);
echo "Script executado. Verifique o arquivo.\n";
