<?php


use App\Models\Produto;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminProdutoController;

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
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::get('/checkout/processar', fn () => redirect()->route('checkout.index'));
Route::post('/checkout/processar', [CheckoutController::class, 'finalizar'])->name('checkout.processar');
Route::get('/checkout/retorno', [CheckoutController::class, 'retorno'])->name('checkout.retorno');

//Rotas de Conta
Route::get('/meusdados',[UserController::class, 'meusdados'])->middleware('auth');;
Route::post('/meusdados/update', function (\Illuminate\Http\Request $request) {
    
    $user = auth()->user();

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'cpf' => $request->cpf,
        'telefone' => $request->telefone
    ]);

    return back()->with('success', 'Dados atualizados!');
});

// Rotas de Trocar Senha
Route::middleware('auth')->group(function () {
    Route::get('/change-password', [UserController::class, 'changePasswordForm'])->name('change-password.form');
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('change-password.update');
});

// Rotas de Registro
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
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminProdutoController::class, 'dashboard'])->name('dashboard');
    Route::get('/produtos/search', [AdminProdutoController::class, 'search'])->name('produtos.search');
    
    // CRUD de Produtos
    Route::resource('produtos', AdminProdutoController::class);
    
    // Ações especiais
    Route::patch('/produtos/{produto}/toggle', [AdminProdutoController::class, 'toggle'])->name('produtos.toggle');
    Route::patch('/produtos/{produto}/toggle-destaque', [AdminProdutoController::class, 'toggleDestaque'])->name('produtos.toggleDestaque');
});