<?php

use App\Models\Produto;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    // Busca no banco usando o Eloquent ORM
    $destaques = Produto::where('destaque', 1)->get();
    $produtosGerais = Produto::get();

    return view('index', compact('destaques', 'produtosGerais'));
});
Route::get('/', function () {
    // Pega apenas os nomes das categorias, sem repetir
    $categorias = Produto::distinct()->pluck('categoria'); 
    
    $destaques = Produto::where('destaque', 1)->get();
    $produtosGerais = Produto::all();

    return view('index', compact('destaques', 'produtosGerais', 'categorias'));
});

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/empresa/{cnpj}', [EmpresaController::class, 'consultarCnpj']);
