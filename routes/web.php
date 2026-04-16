<?php


use App\Models\Produto;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    $destaques = Produto::where('destaque', 1)->get();
    $produtosGerais = Produto::all();
    $categorias = Produto::distinct()->pluck('categoria');

    return view('index', compact('destaques', 'produtosGerais', 'categorias'));
});

Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
Route::get('/api/carrinho', [CarrinhoController::class, 'getCarrinho'])->name('carrinho.get');
Route::post('/carrinho/{produto}', [CarrinhoController::class, 'store'])->name('carrinho.add');
Route::put('/carrinho/{produto}', [CarrinhoController::class, 'update'])->name('carrinho.update');
Route::delete('/carrinho/{produto}', [CarrinhoController::class, 'destroy'])->name('carrinho.remove');
Route::delete('/carrinho', [CarrinhoController::class, 'clear'])->name('carrinho.clear');

//Rotas home
Route::get('/index',[UserController::class, 'home']);
Route::post('/index',[UserController::class, 'home']);


//Rotas de Conta
Route::get('/meusdados',[UserController::class, 'meusdados']);
Route::post('/meusdados',[UserController::class, 'meusdados']);

// Rotas de Registro
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/empresa/{cnpj}', [EmpresaController::class, 'consultarCnpj']);
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Rotas de Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::GET('/logout', function () {
    Auth::logout();
    return redirect('/');
});