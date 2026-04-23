<?php
// Script para debugar sincronização do produto 723

require 'bootstrap/app.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Produto;
use App\Services\BlueSoftService;

$produto = Produto::find(723);

if (!$produto) {
    echo "❌ Produto 723 não encontrado!\n";
    exit(1);
}

echo "📦 Produto 723 encontrado:\n";
echo "   ID: " . $produto->id . "\n";
echo "   Nome: " . ($produto->nome ?? "VAZIO") . "\n";
echo "   Código de Barras: " . ($produto->codigo_barras ?? "VAZIO") . "\n";
echo "   Imagem URL Atual: " . ($produto->imagem_url ?? "VAZIO") . "\n";

if (!$produto->codigo_barras) {
    echo "\n❌ Produto não tem código de barras! Impossível sincronizar.\n";
    exit(1);
}

echo "\n🔍 Tentando buscar imagem da BlueSoft...\n";
$blueSoft = $app->make(BlueSoftService::class);
$novaImagem = $blueSoft->obterImagem($produto->codigo_barras);

if ($novaImagem) {
    echo "✅ Imagem encontrada na BlueSoft:\n";
    echo "   URL: " . $novaImagem . "\n";
    echo "\n📝 Atualizando banco de dados...\n";
    $produto->imagem_url = $novaImagem;
    $produto->save();
    echo "✅ Banco atualizado com sucesso!\n";
} else {
    echo "❌ BlueSoft não tem imagem para EAN: " . $produto->codigo_barras . "\n";
}
