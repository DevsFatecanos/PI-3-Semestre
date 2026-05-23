<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class PublicProdutoController extends Controller
{
    /**
     * Exibe a página pública de detalhes do produto
     */
    public function show(Produto $produto)
    {
        $produto->loadMissing('favoritos');

        return view('produtos.show', compact('produto'));
    }
}
