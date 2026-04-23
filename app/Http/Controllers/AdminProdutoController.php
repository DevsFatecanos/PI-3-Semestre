<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProdutoController extends Controller
{
    /**
     * Exibe o painel de admin com listagem de produtos
     */
    public function dashboard()
    {
        $produtos = Produto::paginate(15);
        $totalProdutos = Produto::count();
        $produtosAtivos = Produto::where('ativo', true)->count();
        $produtosInativos = Produto::where('ativo', false)->count();
        $destaque = Produto::where('destaque', true)->count();

        return view('admin.dashboard', compact('produtos', 'totalProdutos', 'produtosAtivos', 'produtosInativos', 'destaque'));
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
