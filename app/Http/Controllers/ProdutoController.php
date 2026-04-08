use Illuminate\Support\Facades\Http;

public function index() {
    $destaques = Produto::where('destaque', true)->get();
    $categorias = Produto::where('categoria', $categoria)->get();
    
    // Certifique-se de que não há nenhum ->where() aqui
    $produtosGerais = Produto::all(); 

    return view('index', compact('destaques', 'produtosGerais'));
}
public function buscarProduto($ean)
{
    $response = Http::withHeaders([
        'User-Agent' => 'SuaApp',
        'X-Cosmos-Token' => 'SEU_TOKEN_AQUI'
    ])->get("https://api.cosmos.bluesoft.com.br/gtins/{$ean}.json");

    $produto = $response->successful() ? $response->json() : null;

    return view('produtos.show', compact('produto'));
}
public function index()
{
    // Pega as categorias únicas baseadas no que você importou do CSV
    $categorias = Produto::distinct()->pluck('categoria');

    // Produtos marcados como destaque (para o Swiper)
    $destaques = Produto::where('destaque', true)->where('ativo', true)->get();

    // Restante dos produtos
    $produtosGerais = Produto::where('ativo', true)->paginate(12);

    return view('home', compact('categorias', 'destaques', 'produtosGerais'));
}