<?php

namespace Tests\Feature;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardReportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_data_endpoint_returns_chart_payload(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $produto = Produto::create([
            'nome' => 'Produto A',
            'codigo_barras' => '789000000001',
            'descricao' => 'Produto de teste',
            'preco_antigo' => 12.00,
            'preco_atual' => 15.00,
            'preco_de_custo' => 9.00,
            'quantidade' => 8,
            'marca' => 'Marca X',
            'categoria' => 'Categoria 1',
            'destaque' => false,
            'ativo' => true,
        ]);

        $pedido = Pedido::create([
            'user_id' => $admin->id,
            'nome_cliente' => 'Cliente Teste',
            'email_cliente' => 'cliente@example.com',
            'telefone_cliente' => '11999999999',
            'metodo_pagamento' => 'pix',
            'status' => 'approved',
            'referencia' => 'PED-0001',
            'provedor' => 'simulador_local',
            'total' => 30.00,
            'observacoes' => null,
            'data_pagamento' => now(),
            'email_enviado_em' => null,
        ]);

        PedidoItem::create([
            'pedido_id' => $pedido->id,
            'produto_id' => $produto->id,
            'nome_produto' => $produto->nome,
            'categoria_produto' => $produto->categoria,
            'preco_unitario' => 15.00,
            'quantidade' => 2,
            'subtotal' => 30.00,
        ]);

        $response = $this->actingAs($admin)->getJson(route('admin.dashboard.data'));

        $response->assertOk();
        $response->assertJsonPath('chartData.orders', 1);
        $response->assertJsonPath('chartData.topProdutos.0.nome', 'Produto A');
    }

    public function test_product_export_downloads_csv_with_rows(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $produto = Produto::create([
            'nome' => 'Produto B',
            'codigo_barras' => '789000000002',
            'descricao' => 'Produto de teste 2',
            'preco_antigo' => 20.00,
            'preco_atual' => 25.00,
            'preco_de_custo' => 14.00,
            'quantidade' => 5,
            'marca' => 'Marca Y',
            'categoria' => 'Categoria 2',
            'destaque' => false,
            'ativo' => true,
        ]);

        $pedido = Pedido::create([
            'user_id' => $admin->id,
            'nome_cliente' => 'Cliente Teste',
            'email_cliente' => 'cliente@example.com',
            'telefone_cliente' => '11999999999',
            'metodo_pagamento' => 'cartao',
            'status' => 'approved',
            'referencia' => 'PED-0002',
            'provedor' => 'simulador_local',
            'total' => 25.00,
            'observacoes' => null,
            'data_pagamento' => now(),
            'email_enviado_em' => null,
        ]);

        PedidoItem::create([
            'pedido_id' => $pedido->id,
            'produto_id' => $produto->id,
            'nome_produto' => $produto->nome,
            'categoria_produto' => $produto->categoria,
            'preco_unitario' => 25.00,
            'quantidade' => 1,
            'subtotal' => 25.00,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard.export', ['type' => 'products']));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Produto ID,Nome,Categoria', $response->getContent());
        $this->assertStringContainsString('Produto B', $response->getContent());
    }

    public function test_product_report_page_renders_product_rows(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $produto = Produto::create([
            'nome' => 'Produto C',
            'codigo_barras' => '789000000003',
            'descricao' => 'Produto de teste 3',
            'preco_antigo' => 30.00,
            'preco_atual' => 35.00,
            'preco_de_custo' => 20.00,
            'quantidade' => 3,
            'marca' => 'Marca Z',
            'categoria' => 'Categoria 3',
            'destaque' => false,
            'ativo' => true,
        ]);

        $pedido = Pedido::create([
            'user_id' => $admin->id,
            'nome_cliente' => 'Cliente Teste',
            'email_cliente' => 'cliente@example.com',
            'telefone_cliente' => '11999999999',
            'metodo_pagamento' => 'pix',
            'status' => 'approved',
            'referencia' => 'PED-0003',
            'provedor' => 'simulador_local',
            'total' => 70.00,
            'observacoes' => null,
            'data_pagamento' => now(),
            'email_enviado_em' => null,
        ]);

        PedidoItem::create([
            'pedido_id' => $pedido->id,
            'produto_id' => $produto->id,
            'nome_produto' => $produto->nome,
            'categoria_produto' => $produto->categoria,
            'preco_unitario' => 35.00,
            'quantidade' => 2,
            'subtotal' => 70.00,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard.print', ['type' => 'products']));

        $response->assertOk();
        $response->assertSee('Relatório de Produtos');
        $response->assertSee('Produto C');
    }
}