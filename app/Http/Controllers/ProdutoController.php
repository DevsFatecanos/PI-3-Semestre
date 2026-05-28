<?php

namespace App\Http\Controllers;

use App\Models\Favorito;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProdutoController extends Controller
{
    public function index()
    {
        $categorias = Produto::distinct()->pluck('categoria')->filter()->values();
        $destaques = Produto::where('destaque', true)->where('ativo', true)->get();
        $produtosGerais = Produto::where('ativo', true)->paginate(12);
        $favoritosIds = auth()->check()
            ? Favorito::query()
                ->where('user_id', auth()->id())
                ->pluck('produto_id')
                ->all()
            : [];

        return view('index', compact('categorias', 'destaques', 'produtosGerais', 'favoritosIds'));
    }

    public function buscarProduto(string $ean)
    {
        $response = Http::withHeaders([
            'User-Agent' => config('app.name', 'Foccus Comercial'),
            'X-Cosmos-Token' => env('COSMOS_API_TOKEN', ''),
        ])->get("https://api.cosmos.bluesoft.com.br/gtins/{$ean}.json");

        $produto = $response->successful() ? $response->json() : null;

        return view('produtos.show', compact('produto'));
    }
}