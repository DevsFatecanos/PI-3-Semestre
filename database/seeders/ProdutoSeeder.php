<?php

namespace Database\Seeders;

use App\Models\Produto;
use App\Services\ProductImageResolver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = base_path("database/data/ESTOQUE_VIRTUS_032026.csv");
        $resolver = app(ProductImageResolver::class);

        if (($handle = fopen($filePath, "r")) !== FALSE) {
            // Pula o cabeçalho
            fgetcsv($handle, 1000, ",");

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                
                // Se o PHP não achar a vírgula, ele joga a linha inteira no índice [0].
                // Se isso acontecer, tentamos separar por ponto e vírgula (;).
                if (count($data) < 2) {
                    $data = explode(';', $data[0]);
                }

                // Pula linhas que não tenham o nome do produto (índice 1) ou que estejam vazias
                if (!isset($data[1]) || empty($data[1])) {
                    continue;
                }

                $precoFake = rand(15, 90) + 0.90;

                Produto::create([
                    'nome'           => ucfirst(mb_strtolower($data[1])),
                    'codigo_barras'  => $data[0] ?? null,
                    'descricao'      => "Descrição do produto " . ($data[1] ?? ''),
                    'preco_antigo'   => $precoFake + 10,
                    'preco_atual'    => $precoFake,
                    'preco_de_custo' => $precoFake * 0.6,
                    'quantidade'     => rand(10, 100),
                    'marca'          => $data[2] ?? 'Foccus',
                    'categoria'      => $data[3] ?? 'Geral',
                    'imagem_url'     => $resolver->resolve(
                        ucfirst(mb_strtolower($data[1])),
                        $data[2] ?? 'Foccus',
                        $data[3] ?? 'Geral',
                        $data[0] ?? null,
                    ),
                    'destaque'       => rand(0, 1),
                    'ativo'          => true,
                ]);
            }
            fclose($handle);
        }
    }
}