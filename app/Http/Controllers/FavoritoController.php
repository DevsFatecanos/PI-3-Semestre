<?php

namespace App\Http\Controllers;

use App\Models\Favorito;
use App\Models\Produto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FavoritoController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $favoritos = Produto::query()
            ->whereHas('favoritos', fn ($query) => $query->where('user_id', $user->id))
            ->orderBy('nome')
            ->get();

        $sugestoes = Produto::query()
            ->whereIn('categoria', $favoritos->pluck('categoria')->filter()->unique()->values())
            ->whereNotIn('id', $favoritos->pluck('id')->values())
            ->where('ativo', true)
            ->inRandomOrder()
            ->limit(8)
            ->get();

        return view('favoritos.index', [
            'favoritos' => $favoritos,
            'sugestoes' => $sugestoes,
        ]);
    }

    public function store(Produto $produto): RedirectResponse|JsonResponse
    {
        $userId = auth()->id();

        Favorito::firstOrCreate([
            'user_id' => $userId,
            'produto_id' => $produto->id,
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produto adicionado aos favoritos.',
            ]);
        }

        return back()->with('success', 'Produto adicionado aos favoritos.');
    }

    public function destroy(Produto $produto): RedirectResponse|JsonResponse
    {
        Favorito::query()
            ->where('user_id', auth()->id())
            ->where('produto_id', $produto->id)
            ->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produto removido dos favoritos.',
            ]);
        }

        return back()->with('success', 'Produto removido dos favoritos.');
    }
}
