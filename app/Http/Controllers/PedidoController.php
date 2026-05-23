<?php

namespace App\Http\Controllers;

use App\Models\Favorito;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $pedidos = Pedido::query()
            ->with('itens')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $categoriasCompradas = $pedidos
            ->flatMap(fn ($pedido) => $pedido->itens->pluck('categoria_produto'))
            ->filter()
            ->unique()
            ->values();

        $sugestoes = Produto::query()
            ->when(
                $categoriasCompradas->isNotEmpty(),
                fn ($query) => $query->whereIn('categoria', $categoriasCompradas),
                fn ($query) => $query->where('destaque', true),
            )
            ->where('ativo', true)
            ->inRandomOrder()
            ->limit(8)
            ->get();

        $favoritosIds = Favorito::query()
            ->where('user_id', $user->id)
            ->pluck('produto_id')
            ->all();

        return view('pedidos.index', [
            'pedidos' => $pedidos,
            'sugestoes' => $sugestoes,
            'favoritosIds' => $favoritosIds,
        ]);
    }
}
