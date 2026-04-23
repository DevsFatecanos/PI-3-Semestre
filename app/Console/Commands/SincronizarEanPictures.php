<?php

namespace App\Console\Commands;

use App\Models\Produto;
use App\Services\EanPicturesService;
use App\Services\BlueSoftService;
use App\Services\ProdutoApiAggregatorService;
use Illuminate\Console\Command;

class SincronizarEanPictures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ean:sincronizar
                            {--id= : ID do produto específico para sincronizar}
                            {--force : Sobrescrever dados existentes}
                            {--only-image : Sincronizar apenas imagens}
                            {--only-description : Sincronizar apenas descrições}
                            {--source= : Fonte específica (ean, bluesoft, ambas)}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Sincroniza dados de produtos com APIs de EanPictures e BlueSoft';

    protected EanPicturesService $eanService;
    protected BlueSoftService $blueSoftService;
    protected ProdutoApiAggregatorService $aggregatorService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        EanPicturesService $eanService,
        BlueSoftService $blueSoftService,
        ProdutoApiAggregatorService $aggregatorService
    ) {
        parent::__construct();
        $this->eanService = $eanService;
        $this->blueSoftService = $blueSoftService;
        $this->aggregatorService = $aggregatorService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $force = $this->option('force');
        $onlyImage = $this->option('only-image');
        $onlyDescription = $this->option('only-description');
        $idEspecifico = $this->option('id');
        $source = $this->option('source') ?? 'ambas';

        // Build query
        $query = Produto::query();

        if ($idEspecifico) {
            $query->where('id', $idEspecifico);
        } else {
            // Only sync products with barcode
            $query->whereNotNull('codigo_barras')
                  ->where('codigo_barras', '!=', '');
        }

        $produtos = $query->get();

        if ($produtos->isEmpty()) {
            $this->info('Nenhum produto encontrado para sincronizar.');
            return 0;
        }

        $this->info("Iniciando sincronização de {$produtos->count()} produtos...\n");
        $this->info("Fonte: {$source}");
        $this->info("Força: " . ($force ? 'Sim' : 'Não'));
        $this->info("---\n");

        $atualizado = 0;
        $processBar = $this->output->createProgressBar($produtos->count());
        $processBar->start();

        foreach ($produtos as $produto) {
            $ean = $produto->codigo_barras;

            try {
                $atualizado += $this->sincronizarProduto(
                    $produto,
                    $ean,
                    $force,
                    $onlyImage,
                    $onlyDescription,
                    $source
                );
            } catch (\Exception $e) {
                $this->error("Erro ao sincronizar produto ID {$produto->id}: " . $e->getMessage());
            }

            $processBar->advance();
        }

        $processBar->finish();
        $this->info("\n\nSincronização concluída!");
        $this->info("Produtos atualizados: {$atualizado}");

        return 0;
    }

    protected function sincronizarProduto(
        Produto $produto,
        string $ean,
        bool $force = false,
        bool $onlyImage = false,
        bool $onlyDescription = false,
        string $source = 'ambas'
    ): int {
        $atualizado = 0;

        // Usar o agregador para buscar dados da melhor fonte
        if ($source === 'ambas' || $source === 'all') {
            $dadosCompletos = $this->aggregatorService->obterProdutoCompleto($ean);
        } elseif ($source === 'bluesoft') {
            $dadosCompletos = $this->blueSoftService->obterProdutoCompleto($ean);
        } else {
            // EanPictures
            $descricao = $this->eanService->obterDescricao($ean);
            $temFoto = $this->eanService->verificarFoto($ean) === 'Sim';
            $fotoUrl = $temFoto ? $this->eanService->obterImagem($ean) : null;

            if ($descricao) {
                $descricao['imagem_url'] = $fotoUrl;
                $dadosCompletos = $descricao;
            } else {
                $dadosCompletos = null;
            }
        }

        if (!$dadosCompletos) {
            return 0;
        }

        // Sincronizar imagem
        if (!$onlyDescription) {
            $imagemUrl = $dadosCompletos['imagem_url'] ?? null;
            if ($imagemUrl && ($force || !$produto->imagem_url)) {
                $produto->imagem_url = $imagemUrl;
                $atualizado++;
            }
        }

        // Sincronizar descrição
        if (!$onlyImage) {
            // Dados da BlueSoft
            if (isset($dadosCompletos['nome']) && ($force || !$produto->nome)) {
                $produto->nome = $dadosCompletos['nome'];
                $atualizado++;
            }

            if (isset($dadosCompletos['marca']) && ($force || !$produto->marca)) {
                $produto->marca = $dadosCompletos['marca'];
                $atualizado++;
            }

            if (isset($dadosCompletos['categoria']) && ($force || !$produto->categoria)) {
                $produto->categoria = $dadosCompletos['categoria'];
                $atualizado++;
            }

            // Dados do EanPictures (fallback)
            if (isset($dadosCompletos['Nome']) && ($force || !$produto->nome)) {
                $produto->nome = $dadosCompletos['Nome'];
                $atualizado++;
            }

            if (isset($dadosCompletos['Marca']) && ($force || !$produto->marca)) {
                $produto->marca = $dadosCompletos['Marca'];
                $atualizado++;
            }

            if (isset($dadosCompletos['Categoria']) && ($force || !$produto->categoria)) {
                $produto->categoria = $dadosCompletos['Categoria'];
                $atualizado++;
            }
        }

        if ($atualizado > 0) {
            $produto->save();
        }

        return $atualizado > 0 ? 1 : 0;
    }
}

