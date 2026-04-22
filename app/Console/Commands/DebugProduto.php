<?php

namespace App\Console\Commands;

use App\Models\Produto;
use App\Services\BlueSoftService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DebugProduto extends Command
{
    protected $signature = 'debug:produto {id}';
    protected $description = 'Debug de um produto específico';

    public function handle()
    {
        $id = $this->argument('id');
        
        $this->line("\n🔍 Consultando produto {$id} no banco PostgreSQL...");
        
        $produto = DB::table('produtos')->where('id', $id)->first();

        if (!$produto) {
            $this->error("❌ Produto {$id} não encontrado no banco!");
            return 1;
        }

        $this->info("✅ Produto encontrado!");
        $this->table(
            ['Campo', 'Valor'],
            [
                ['ID', $produto->id],
                ['Nome', $produto->nome ?? '(vazio)'],
                ['Código de Barras', $produto->codigo_barras ?? '(vazio)'],
                ['Marca', $produto->marca ?? '(vazio)'],
                ['Imagem URL Atual', $produto->imagem_url ?? '(vazio)'],
            ]
        );

        if (!$produto->codigo_barras) {
            $this->error("\n❌ Produto não tem código de barras! Impossível sincronizar.");
            return 1;
        }

        $this->info("\n🔍 Testando BlueSoft com EAN: {$produto->codigo_barras}");
        
        try {
            $blueSoft = app(BlueSoftService::class);
            $novaImagem = $blueSoft->obterImagem($produto->codigo_barras);

            if ($novaImagem) {
                $this->info("✅ Imagem encontrada na BlueSoft!");
                $this->line("URL: {$novaImagem}");
                
                if ($this->confirm('\nDeseja atualizar o banco?')) {
                    DB::table('produtos')->where('id', $id)->update(['imagem_url' => $novaImagem]);
                    $this->info("✅ Banco atualizado com sucesso!");
                }
            } else {
                $this->warn("❌ BlueSoft não encontrou imagem para EAN: {$produto->codigo_barras}");
                $this->line("\n💡 Testando URL diretamente...");
                $this->testBlueSoftUrl($produto->codigo_barras);
            }
        } catch (\Exception $e) {
            $this->error("❌ Erro ao testar BlueSoft: " . $e->getMessage());
        }

        return 0;
    }

    protected function testBlueSoftUrl($ean)
    {
        $url = "https://cdn-cosmos.bluesoft.com.br/products/{$ean}";
        $this->line("Testando: {$url}");
        
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200) {
                $this->info("✅ URL retornou 200 OK - Imagem existe!");
                $this->line("URL válida: {$url}");
            } else {
                $this->warn("❌ URL retornou HTTP {$httpCode} - Imagem não existe na BlueSoft");
            }
        } catch (\Exception $e) {
            $this->error("❌ Erro ao testar URL: " . $e->getMessage());
        }
    }
}
