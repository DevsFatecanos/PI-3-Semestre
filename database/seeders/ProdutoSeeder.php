<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        $produtos = [
    // --- HIGIENE PESSOAL ---
    [
        'nome' => 'Desodorante Aerosol Rexona Men 150ml',
        'codigo_barras' => '7891150033421',
        'descricao' => 'Antitranspirante com tecnologia MotionSense para proteção 48h.',
        'preco_antigo' => 18.90,
        'preco_atual' => 15.50,
        'preco_de_custo' => 9.20,
        'quantidade' => 120,
        'marca' => 'Rexona',
        'categoria' => 'Higiene Pessoal',
        'destaque' => true,
        'ativo' => true,
    ],
    [
        'nome' => 'Creme Dental Colgate Total 12 90g',
        'codigo_barras' => '7891024131505',
        'descricao' => 'Saúde bucal completa, reduz bactérias nos dentes, língua e bochechas.',
        'preco_antigo' => 11.00,
        'preco_atual' => 8.90,
        'preco_de_custo' => 5.10,
        'quantidade' => 200,
        'marca' => 'Colgate',
        'categoria' => 'Higiene Pessoal',
        'destaque' => false,
        'ativo' => true,
    ],

    // --- DESCARTÁVEIS ---
    [
        'nome' => 'Papel Higiênico Neve Folha Dupla c/ 12',
        'codigo_barras' => '7891100012544',
        'descricao' => 'Papel higiênico premium com toque de seda e máxima absorção.',
        'preco_antigo' => 25.90,
        'preco_atual' => 21.90,
        'preco_de_custo' => 14.50,
        'quantidade' => 85,
        'marca' => 'Neve',
        'categoria' => 'Descartáveis',
        'destaque' => true,
        'ativo' => true,
    ],
    [
        'nome' => 'Guardanapo Nobre Folha Simples 30x30 50un',
        'codigo_barras' => '7898275330122',
        'descricao' => 'Guardanapo de papel de alta gramatura para uso institucional.',
        'preco_antigo' => 6.50,
        'preco_atual' => 4.99,
        'preco_de_custo' => 2.30,
        'quantidade' => 300,
        'marca' => 'Nobre',
        'categoria' => 'Descartáveis',
        'destaque' => false,
        'ativo' => true,
    ],

    // --- LIMPEZA ---
    [
        'nome' => 'Detergente Líquido Ypê Clean 500ml',
        'codigo_barras' => '7896098900108',
        'descricao' => 'Detergente biodegradável com alto poder desengordurante.',
        'preco_antigo' => 3.20,
        'preco_atual' => 2.75,
        'preco_de_custo' => 1.60,
        'quantidade' => 500,
        'marca' => 'Ypê',
        'categoria' => 'Limpeza',
        'destaque' => true,
        'ativo' => true,
    ],
    [
        'nome' => 'Amaciante Downy Concentrado Brisa de Verão 500ml',
        'codigo_barras' => '7500435129672',
        'descricao' => 'Amaciante concentrado que rende até 22 lavagens.',
        'preco_antigo' => 19.90,
        'preco_atual' => 16.80,
        'preco_de_custo' => 10.40,
        'quantidade' => 60,
        'marca' => 'Downy',
        'categoria' => 'Limpeza',
        'destaque' => true,
        'ativo' => true,
    ],
    [
        'nome' => 'Água Sanitária Qboa 2 Litros',
        'codigo_barras' => '7896001000017',
        'descricao' => 'Desinfetante para uso geral e branqueador de roupas.',
        'preco_antigo' => 9.50,
        'preco_atual' => 7.90,
        'preco_de_custo' => 4.20,
        'quantidade' => 150,
        'marca' => 'Qboa',
        'categoria' => 'Limpeza',
        'destaque' => false,
        'ativo' => true,
    ],
];

        foreach ($produtos as $produto) {
            Produto::create($produto);
        }
    }
}