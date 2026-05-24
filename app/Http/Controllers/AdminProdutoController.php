<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class AdminProdutoController extends Controller
{
    /**
     * Exibe o painel de admin com listagem de produtos
     */
    public function dashboard(Request $request)
    {
        $filter = $request->get('filter');

        $query = Produto::query();

        if ($filter === 'low') {
            $low = config('stock.low_threshold', 5);
            $query->where('quantidade', '<=', $low)->orderBy('quantidade', 'asc');
        } else {
            $query->orderBy('nome');
        }

        $produtos = $query->paginate(15)->withQueryString();
        $totalProdutos = Produto::count();
        $produtosAtivos = Produto::where('ativo', true)->count();
        $produtosInativos = Produto::where('ativo', false)->count();
        $destaque = Produto::where('destaque', true)->count();

        // Contagem de produtos com baixo estoque para notificações
        $lowCount = Produto::where('quantidade', '<=', config('stock.low_threshold', 5))->count();
        $criticalCount = Produto::where('quantidade', '<=', config('stock.critical_threshold', 1))->count();

        // Produtos em baixo estoque (lista curta para exibir alertas)
        $lowStockProducts = Produto::where('quantidade', '<=', config('stock.low_threshold', 5))
                                  ->orderBy('quantidade', 'asc')
                                  ->limit(5)
                                  ->get();

        // --- Dados do dashboard ---
        $approvedPedidos = Pedido::query()->where('status', 'approved');

        $totalOrders = $approvedPedidos->count();
        $totalRevenue = (float) $approvedPedidos->sum('total');

        $profitRow = DB::table('pedido_itens')
            ->join('pedidos', 'pedido_itens.pedido_id', '=', 'pedidos.id')
            ->leftJoin('produtos', 'pedido_itens.produto_id', '=', 'produtos.id')
            ->where('pedidos.status', 'approved')
            ->selectRaw('SUM(pedido_itens.subtotal) as revenue, SUM(COALESCE(produtos.preco_de_custo,0) * pedido_itens.quantidade) as cost')
            ->first();

        $revenueFromItems = (float) ($profitRow->revenue ?? 0);
        $costFromItems = (float) ($profitRow->cost ?? 0);
        $grossProfit = $revenueFromItems - $costFromItems;
        $grossMarginPercent = $revenueFromItems > 0 ? ($grossProfit / $revenueFromItems) * 100 : 0;

        // Top categorias
        $topCategories = DB::table('pedido_itens')
            ->join('pedidos', 'pedido_itens.pedido_id', '=', 'pedidos.id')
            ->where('pedidos.status', 'approved')
            ->select('pedido_itens.categoria_produto as categoria', DB::raw('SUM(pedido_itens.quantidade) as total_qtd'), DB::raw('SUM(pedido_itens.subtotal) as total_venda'))
            ->groupBy('pedido_itens.categoria_produto')
            ->orderByDesc('total_qtd')
            ->limit(8)
            ->get();

        // Top produtos
        $topProdutos = DB::table('pedido_itens')
            ->join('pedidos', 'pedido_itens.pedido_id', '=', 'pedidos.id')
            ->where('pedidos.status', 'approved')
            ->select('pedido_itens.produto_id', 'pedido_itens.nome_produto as nome', DB::raw('SUM(pedido_itens.quantidade) as total_qtd'), DB::raw('SUM(pedido_itens.subtotal) as total_venda'))
            ->groupBy('pedido_itens.produto_id', 'pedido_itens.nome_produto')
            ->orderByDesc('total_qtd')
            ->limit(8)
            ->get();

        // Distribuição de estoque
        $critical = config('stock.critical_threshold', 1);
        $low = config('stock.low_threshold', 5);

        $stockDistribution = DB::table('produtos')
            ->selectRaw("CASE WHEN quantidade <= 0 THEN 'out' WHEN quantidade <= ? THEN 'critical' WHEN quantidade <= ? THEN 'low' ELSE 'ok' END as status", [$critical, $low])
            ->selectRaw('count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Dados para gráficos (JSON)
        $chartData = [
            'topCategories' => $topCategories,
            'topProdutos' => $topProdutos,
            'stockDistribution' => $stockDistribution,
            'revenue' => $totalRevenue,
            'orders' => $totalOrders,
            'grossProfit' => $grossProfit,
            'grossMarginPercent' => round($grossMarginPercent, 2),
        ];

        return view('admin.dashboard', compact(
            'produtos',
            'totalProdutos',
            'produtosAtivos',
            'produtosInativos',
            'destaque',
            'lowCount',
            'criticalCount',
            'lowStockProducts',
            'filter'
        ))->with('chartData', $chartData);
        ));

    /**
     * Exporta relatórios em CSV (type: sales|products|stock)
     */
    public function exportCsv(Request $request)
    {
        $type = $request->get('type', 'sales');

        $filename = 'report_' . $type . '_' . date('Ymd_His') . '.csv';

        $handle = fopen('php://memory', 'w');

        if ($type === 'products') {
            fputcsv($handle, ['Produto ID', 'Nome', 'Categoria', 'Estoque', 'Preco Atual', 'Preco de Custo', 'Total Vendido Qtd', 'Total Vendido Valor']);

            $rows = DB::table('produtos')
                ->leftJoin('pedido_itens', 'produtos.id', '=', 'pedido_itens.produto_id')
                ->leftJoin('pedidos', 'pedido_itens.pedido_id', '=', 'pedidos.id')
                ->select('produtos.id', 'produtos.nome', 'produtos.categoria', 'produtos.quantidade', 'produtos.preco_atual', 'produtos.preco_de_custo', DB::raw('COALESCE(SUM(CASE WHEN pedidos.status = "approved" THEN pedido_itens.quantidade ELSE 0 END),0) as total_qtd'), DB::raw('COALESCE(SUM(CASE WHEN pedidos.status = "approved" THEN pedido_itens.subtotal ELSE 0 END),0) as total_venda'))
                ->groupBy('produtos.id')
                ->get();

            foreach ($rows as $r) {
                fputcsv($handle, [(int) $r->id, $r->nome, $r->categoria, (int) $r->quantidade, (float) $r->preco_atual, (float) $r->preco_de_custo, (int) $r->total_qtd, (float) $r->total_venda]);
            }
        } else { // sales
            fputcsv($handle, ['Pedido ID', 'Referencia', 'Data Pagamento', 'Cliente', 'Email', 'Total', 'Itens']);

            $pedidos = Pedido::withCount('itens')->where('status', 'approved')->orderByDesc('data_pagamento')->get();

            foreach ($pedidos as $p) {
                fputcsv($handle, [$p->id, $p->referencia, $p->data_pagamento?->toDateTimeString() ?? '', $p->nome_cliente, $p->email_cliente, (float) $p->total, $p->itens_count]);
            }
        }

        rewind($handle);

        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Retorna uma página imprimível/HTML que pode ser salva como PDF pelo navegador
     */
    public function printReport(Request $request)
    {
        $type = $request->get('type', 'sales');

        if ($type === 'products') {
            $rows = DB::table('produtos')
                ->leftJoin('pedido_itens', 'produtos.id', '=', 'pedido_itens.produto_id')
                ->leftJoin('pedidos', 'pedido_itens.pedido_id', '=', 'pedidos.id')
                ->select('produtos.*', DB::raw('COALESCE(SUM(CASE WHEN pedidos.status = "approved" THEN pedido_itens.quantidade ELSE 0 END),0) as total_qtd'), DB::raw('COALESCE(SUM(CASE WHEN pedidos.status = "approved" THEN pedido_itens.subtotal ELSE 0 END),0) as total_venda'))
                ->groupBy('produtos.id')
                ->get();

            return view('admin.reports.products', compact('rows'));
        }

        $pedidos = Pedido::with('itens')->where('status', 'approved')->orderByDesc('data_pagamento')->get();
        return view('admin.reports.sales', compact('pedidos'));
    }
    }

    /**
     * Exibe o formulário para criar um novo produto
     */
    public function create()
    {
        $categorias = Produto::distinct()->pluck('categoria')->sort();
        return view('admin.produtos.create', compact('categorias'));
    }

    /**
     * Armazena um novo produto no banco de dados
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'codigo_barras' => 'nullable|string|unique:produtos,codigo_barras',
            'descricao' => 'nullable|string',
            'preco_de_custo' => 'nullable|numeric|min:0',
            'preco_antigo' => 'nullable|numeric|min:0',
            'preco_atual' => 'required|numeric|min:0',
            'quantidade' => 'required|integer|min:0',
            'marca' => 'nullable|string|max:255',
            'categoria' => 'required|string|max:255',
            'imagem_url' => 'nullable|url',
            'destaque' => 'boolean',
            'ativo' => 'boolean',
        ]);

        $produto = Produto::create($validated);

        return redirect()->route('admin.produtos.edit', $produto->id)
                        ->with('success', 'Produto criado com sucesso!');
    }

    /**
     * Exibe o formulário para editar um produto
     */
    public function edit(Produto $produto)
    {
        $categorias = Produto::distinct()->pluck('categoria')->sort();
        return view('admin.produtos.edit', compact('produto', 'categorias'));
    }

    /**
     * Atualiza os dados de um produto
     */
    public function update(Request $request, Produto $produto)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'codigo_barras' => 'nullable|string|unique:produtos,codigo_barras,' . $produto->id,
            'descricao' => 'nullable|string',
            'preco_de_custo' => 'nullable|numeric|min:0',
            'preco_antigo' => 'nullable|numeric|min:0',
            'preco_atual' => 'required|numeric|min:0',
            'quantidade' => 'required|integer|min:0',
            'marca' => 'nullable|string|max:255',
            'categoria' => 'required|string|max:255',
            'imagem_url' => 'nullable|url',
            'destaque' => 'boolean',
            'ativo' => 'boolean',
        ]);

        $produto->update($validated);

        return redirect()->route('admin.produtos.edit', $produto->id)
                        ->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove um produto do banco de dados
     */
    public function destroy(Produto $produto)
    {
        $nome = $produto->nome;
        $produto->delete();

        return redirect()->route('admin.dashboard')
                        ->with('success', "Produto '{$nome}' deletado com sucesso!");
    }

    /**
     * Ativa ou desativa um produto
     */
    public function toggle(Produto $produto)
    {
        $produto->update(['ativo' => !$produto->ativo]);

        $status = $produto->ativo ? 'ativado' : 'desativado';

        return redirect()->back()
                        ->with('success', "Produto '{$produto->nome}' {$status} com sucesso!");
    }

    /**
     * Marca ou desmarca um produto como destaque
     */
    public function toggleDestaque(Produto $produto)
    {
        $produto->update(['destaque' => !$produto->destaque]);

        $status = $produto->destaque ? 'marcado' : 'desmarcado';

        return redirect()->back()
                        ->with('success', "Produto '{$produto->nome}' {$status} como destaque!");
    }

    /**
     * Busca produtos por nome ou categoria
     */
    public function search(Request $request)
    {
        $termo = $request->get('termo', '');

        $produtos = Produto::where('nome', 'like', "%{$termo}%")
                          ->orWhere('categoria', 'like', "%{$termo}%")
                          ->orWhere('codigo_barras', 'like', "%{$termo}%")
                          ->paginate(15);

        return view('admin.dashboard', compact('produtos', 'termo'));
    }
}
