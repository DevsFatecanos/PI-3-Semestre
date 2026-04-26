<?php
$file = 'C:/xampp/htdocs/PI-3-Semestre/resources/views/index.blade.php';
$c = file($file);
$o = [];
$i = 0;
$inPhpBlock = false;

while ($i < count($c)) {
    $line = $c[$i];

    // Detecta inicio do bloco @php antes do $img
    if (trim($line) === '@php') {
        $inPhpBlock = true;
        $o[] = $line;
        $i++;
        continue;
    }

    // Se encontrou a linha do $img dentro de um bloco @php
    if ($inPhpBlock && strpos($line, '$img = $item->url_imagem') !== false) {
        // Remove desde o @php ate aqui
        while (count($o) > 0 && trim(end($o)) !== '@php') {
            array_pop($o);
        }
        if (count($o) > 0 && trim(end($o)) === '@php') {
            array_pop($o); // remove @php
        }

        // Insere novo bloco
        $o[] = '@php' . PHP_EOL;
        $o[] = '                            $ean = preg_replace("/\D/", "", $item->codigo_barras ?? "");' . PHP_EOL;
        $o[] = '                            $img = $item->url_imagem ?? $item->imagem ?? asset("/LOGO_FOCCUS.png");' . PHP_EOL;
        $o[] = '                            $eanPictures = $ean ? "http://www.eanpictures.com.br:9000/api/gtin/{$ean}" : "";' . PHP_EOL;
        $o[] = '                        @endphp' . PHP_EOL;
        $inPhpBlock = false;
        $i++;
        continue;
    }

    // Se encontrou a linha da imagem <img src=>
    if (strpos($line, '<img src="{{ $img }}"') !== false) {
        $o[] = '                                <img src="{{ $img }}"' . PHP_EOL;
        $o[] = '                                     @if($eanPictures)' . PHP_EOL;
        $o[] = '                                     onerror="if(this.src!==\'{{ $eanPictures }}\'){this.src=\'{{ $eanPictures }}\';}else{this.onerror=null;this.src=\'{{ asset("/LOGO_FOCCUS.png") }\';}"' . PHP_EOL;
        $o[] = '                                     @else' . PHP_EOL;
        $o[] = '                                     onerror="this.onerror=null;this.src=\'{{ asset("/LOGO_FOCCUS.png") }\';"' . PHP_EOL;
        $o[] = '                                     @endif' . PHP_EOL;
        $o[] = '                                     class="h-52 w-full object-cover">' . PHP_EOL;
        $i++;
        continue;
    }

    if ($inPhpBlock && trim($line) === '@endphp') {
        $inPhpBlock = false;
    }

    $o[] = $line;
    $i++;
}

file_put_contents($file, implode('', $o));
echo "Concluido\n";
