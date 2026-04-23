<?php

use App\Models\Produto;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminProdutoController;
use App\Http\Controllers\EanPicturesController;
use App\Http\Controllers\BlueSoftController;
use App\Http\Controllers\ProdutoApiAggregatorController;

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
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Rotas de Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Rotas de Admin (Painel de Administração)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminProdutoController::class, 'dashboard'])->name('dashboard');
    Route::get('/produtos/search', [AdminProdutoController::class, 'search'])->name('produtos.search');
    
    // CRUD de Produtos
    Route::resource('produtos', AdminProdutoController::class);
    
    // Ações especiais
    Route::patch('/produtos/{produto}/toggle', [AdminProdutoController::class, 'toggle'])->name('produtos.toggle');
    Route::patch('/produtos/{produto}/toggle-destaque', [AdminProdutoController::class, 'toggleDestaque'])->name('produtos.toggleDestaque');
});

// Rotas da API EanPictures
Route::prefix('api/ean-pictures')->group(function () {
    // Imagem
    Route::get('/{ean}/imagem', [EanPicturesController::class, 'obterImagem']);
    
    // Descrições
    Route::get('/{ean}/descricao', [EanPicturesController::class, 'obterDescricao']);
    Route::get('/{ean}/descricao-200', [EanPicturesController::class, 'obterDescricao200']);
    Route::get('/{ean}/descricao-ini', [EanPicturesController::class, 'obterDescricaoIni']);
    
    // Fotos
    Route::get('/{ean}/verificar-foto', [EanPicturesController::class, 'verificarFoto']);
    Route::get('/{ean}/verificar-foto-json', [EanPicturesController::class, 'verificarFotoJson']);
    
    // Produto completo
    Route::get('/{ean}/completo', [EanPicturesController::class, 'obterProdutoCompleto']);
    
    // Unidade de medida
    Route::get('/unidade/{codigo}', [EanPicturesController::class, 'obterUnidade']);
});

// Rotas da API BlueSoft
Route::prefix('api/bluesoft')->group(function () {
    // Produto completo
    Route::get('/{ean}', [BlueSoftController::class, 'obterProduto']);
    Route::get('/{ean}/imagem', [BlueSoftController::class, 'obterImagem']);
    Route::get('/{ean}/completo', [BlueSoftController::class, 'obterProdutoCompleto']);
});

// Rotas da API Agregadora (combina múltiplas fontes)
Route::prefix('api/produtos')->group(function () {
    // Produto com dados de todas as fontes
    Route::get('/{ean}', [ProdutoApiAggregatorController::class, 'obterProduto']);
    Route::get('/{ean}/imagem', [ProdutoApiAggregatorController::class, 'obterImagem']);
    Route::get('/{ean}/nome', [ProdutoApiAggregatorController::class, 'obterNome']);
    Route::get('/{ean}/marca', [ProdutoApiAggregatorController::class, 'obterMarca']);
    
    // Verificar quais fontes têm dados
    Route::get('/{ean}/fontes', [ProdutoApiAggregatorController::class, 'verificarFontes']);
});