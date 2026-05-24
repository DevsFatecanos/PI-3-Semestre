@extends('layouts.app')

@section('content')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        background-color: #ffffff;
    }
</style>
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">Painel de Administração de Produtos</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.produtos.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Novo Produto
            </a>
            @if(isset($lowCount) && $lowCount > 0)
            <a href="{{ route('admin.dashboard', ['filter' => 'low']) }}" class="btn btn-outline-danger ms-2">
                <i class="fas fa-exclamation-triangle"></i>
                Estoque baixo
                <span class="badge bg-danger ms-1">{{ $lowCount }}</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
           <div class="card text-white shadow-sm" style="background: linear-gradient(135deg, #010d76 0%, #000d87 120%); border: none; border-radius: 12px;">
                <div class="card-body">
                    <h6 class="card-title">Total de Produtos</h6>
                    <h2 class="fw-bold" style="color: #fff;">{{ $totalProdutos ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
           <div class="card text-white shadow-sm" style="background: linear-gradient(135deg, #010d76 0%, #000d87 120%); border: none; border-radius: 12px;">
                <div class="card-body">
                    <h6 class="card-title">Produtos Ativos</h6>
                    <h2 class="fw-bold" style="color: #fff;">{{ $produtosAtivos ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
           <div class="card text-white shadow-sm" style="background: linear-gradient(135deg, #010d76 0%, #000d87 120%); border: none; border-radius: 12px;">
                <div class="card-body">
                    <h6 class="card-title">Produtos Inativos</h6>
                    <h2 class="fw-bold" style="color: #fff;">{{ $produtosInativos ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white shadow-sm" style="background: linear-gradient(135deg, #010d76 0%, #000d87 120%); border: none; border-radius: 12px;">
                <div class="card-body">
                    <h6 class="card-title">Em Destaque</h6>
                    <h2 class="fw-bold" style="color: #fff;">{{ $destaque ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações de exportação -->
    <div class="mb-4 text-end">
        <a href="{{ route('admin.dashboard.export', ['type' => 'sales']) }}" class="btn btn-outline-secondary">Exportar Vendas (CSV)</a>
        <a href="{{ route('admin.dashboard.export', ['type' => 'products']) }}" class="btn btn-outline-secondary">Exportar Produtos (CSV)</a>
        <a href="{{ route('admin.dashboard.print', ['type' => 'sales']) }}" target="_blank" class="btn btn-outline-primary">Imprimir Relatório</a>
    </div>

    <!-- Gráficos do Dashboard -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Distribuição de Estoque</h5>
                <canvas id="stockChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Top Categorias</h5>
                <canvas id="categoriesChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card p-3">
                <h5>Top Produtos</h5>
                <canvas id="produtosChart" height="120"></canvas>
            </div>
        </div>
    </div>

    @if($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Busca e Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.produtos.search') }}" method="GET" class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="termo" class="form-control" placeholder="Buscar por nome, categoria ou código de barras..." value="{{ $termo ?? '' }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100" style="background: linear-gradient(135deg, #001aff 0%, #00063d 120%); border: none;">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Produtos -->
    @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
    <div class="alert alert-warning">
        <strong>Atenção:</strong> Produtos com baixo estoque:
        <ul class="mb-0">
            @foreach($lowStockProducts as $p)
                <li style="font-size:0.95rem;">{{ $p->nome }} — <strong>{{ $p->quantidade }}</strong> unidades</li>
            @endforeach
        </ul>
        <div class="mt-2">
            <a href="{{ route('admin.dashboard', ['filter' => 'low']) }}" class="btn btn-sm btn-outline-primary">Ver todos com baixo estoque</a>
        </div>
    </div>
    @endif
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Preço</th>
                        <th>Estoque</th>
                        <th>Status</th>
                        <th>Destaque</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produtos as $produto)
                    <tr>
                        <td>
                            <figure class="w-12 h-12 overflow-hidden rounded-lg bg-slate-100">
                                <x-product-image :url="$produto->url_imagem" :ean="$produto->codigo_barras" class="h-full w-full object-contain" />
                            </figure>
                        </td>
                        <td>
                            <strong>{{ $produto->nome }}</strong><br>
                            <small class="text-muted">{{ $produto->codigo_barras }}</small>
                        </td>
                        <td>{{ $produto->categoria }}</td>
                        <td>
                            <strong>R$ {{ number_format($produto->preco_atual, 2, ',', '.') }}</strong><br>
                            <small class="text-muted">Custo: R$ {{ number_format($produto->preco_de_custo ?? 0, 2, ',', '.') }}</small>
                        </td>
                        <td>
                            @php $status = $produto->stock_status; @endphp
                            @if($status === 'out')
                                <span class="badge bg-danger" title="Sem estoque">
                                    <i class="fas fa-times-circle"></i> {{ $produto->quantidade }}
                                </span>
                            @elseif($status === 'critical')
                                <span class="badge bg-danger" title="Estoque crítico">
                                    <i class="fas fa-exclamation-circle"></i> {{ $produto->quantidade }}
                                </span>
                            @elseif($status === 'low')
                                <span class="badge bg-warning text-dark" title="Baixo estoque">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $produto->quantidade }}
                                </span>
                            @else
                                <span class="badge bg-success" title="Estoque ok">
                                    {{ $produto->quantidade }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($produto->ativo)
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-secondary">Inativo</span>
                            @endif
                        </td>
                        <td>
                            @if($produto->destaque)
                                <span class="badge bg-warning" style="display: inline-flex; align-items: center;">
                                   <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m311-228 45-192-149-129 196-17 77-181 77 181 196 17-149 129 45 192-169-102-169 102Z"/></svg>
                                    Destaque
                                </span>
                            @else
                                <span class="badge bg-light text-dark">Normal</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-inline-flex align-items-center">
                                <button type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Editar"
                                        style="border-radius: 0.375rem 0 0 0.375rem; margin-right: -1px;"
                                        onclick="document.location='{{ route('admin.produtos.edit', $produto->id) }}'">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button type="button"
                                        class="btn btn-sm btn-outline-success"
                                        title="{{ $produto->ativo ? 'Desativar' : 'Ativar' }}"
                                        style="border-radius: 0; margin-right: -1px;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#toggleModal{{ $produto->id }}">
                                    <i class="fas fa-{{ $produto->ativo ? 'eye-slash' : 'eye' }}"></i>
                                </button>

                                <button type="button"
                                        class="btn btn-sm btn-outline-warning"
                                        title="{{ $produto->destaque ? 'Remover de destaque' : 'Marcar como destaque' }}"
                                        style="border-radius: 0; margin-right: -1px;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#destaqueModal{{ $produto->id }}">
                                    <i class="fas fa-star"></i>
                                </button>

                                <button type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Deletar"
                                        style="border-radius: 0 0.375rem 0.375rem 0;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $produto->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <p class="text-muted">Nenhum produto encontrado.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginação -->
    @if(isset($produtos) && method_exists($produtos, 'render'))
    <div class="mt-4">
        {{ $produtos->render() }}
    </div>
    @endif
</div>

<!-- Modais personalizados para cada produto -->
@foreach($produtos as $produto)
    <!-- Modal Toggle (Ativar/Desativar) -->
    <div class="modal fade" id="toggleModal{{ $produto->id }}" tabindex="-1" aria-labelledby="toggleModalLabel{{ $produto->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title" id="toggleModalLabel{{ $produto->id }}">
                        <i class="fas fa-{{ $produto->ativo ? 'eye-slash' : 'eye' }}"></i>
                        Confirmar {{ $produto->ativo ? 'Desativação' : 'Ativação' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja <strong>{{ $produto->ativo ? 'desativar' : 'ativar' }}</strong> o produto
                    <strong>{{ $produto->nome }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('admin.produtos.toggle', $produto->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $produto->ativo ? 'warning' : 'success' }}">
                            <i class="fas fa-{{ $produto->ativo ? 'eye-slash' : 'eye' }}"></i>
                            Sim, {{ $produto->ativo ? 'desativar' : 'ativar' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Destaque -->
    <div class="modal fade" id="destaqueModal{{ $produto->id }}" tabindex="-1" aria-labelledby="destaqueModalLabel{{ $produto->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="destaqueModalLabel{{ $produto->id }}">
                        <i class="fas fa-star"></i>
                        Confirmar {{ $produto->destaque ? 'Remoção de Destaque' : 'Destaque' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja <strong>{{ $produto->destaque ? 'remover o destaque de' : 'marcar como destaque o' }}</strong> produto
                    <strong>{{ $produto->nome }}</strong>?
                    @if(!$produto->destaque)
                    <p class="text-muted small mt-2 mb-0">Produtos em destaque aparecem na seção principal da loja.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('admin.produtos.toggleDestaque', $produto->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-star"></i>
                            Sim, {{ $produto->destaque ? 'remover destaque' : 'marcar como destaque' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Deletar -->
    <div class="modal fade" id="deleteModal{{ $produto->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $produto->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel{{ $produto->id }}">
                        <i class="fas fa-exclamation-triangle"></i>
                        Confirmar Exclusão
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja <strong class="text-danger">deletar permanentemente</strong> o produto:</p>
                    <p class="fw-bold">{{ $produto->nome }}</p>
                    <p class="text-muted small mb-0">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('admin.produtos.destroy', $produto->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Sim, deletar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = @json($chartData ?? []);

    // Stock chart
    try {
        const stockCtx = document.getElementById('stockChart').getContext('2d');
        const stockLabels = Object.keys(chartData.stockDistribution || {});
        const stockValues = Object.values(chartData.stockDistribution || {});
        new Chart(stockCtx, { type: 'pie', data: { labels: stockLabels, datasets: [{ data: stockValues, backgroundColor: ['#e74c3c','#f1c40f','#3498db','#2ecc71'] }] } });

        // Categories
        const catCtx = document.getElementById('categoriesChart').getContext('2d');
        const categories = (chartData.topCategories || []).map(i => i.categoria || 'N/A');
        const catValues = (chartData.topCategories || []).map(i => Number(i.total_qtd) || 0);
        new Chart(catCtx, { type: 'bar', data: { labels: categories, datasets: [{ label: 'Quantidade vendida', data: catValues, backgroundColor: '#3498db' }] }, options: { responsive: true } });

        // Produtos
        const prodCtx = document.getElementById('produtosChart').getContext('2d');
        const produtos = (chartData.topProdutos || []).map(i => i.nome || 'N/A');
        const prodValues = (chartData.topProdutos || []).map(i => Number(i.total_qtd) || 0);
        new Chart(prodCtx, { type: 'bar', data: { labels: produtos, datasets: [{ label: 'Quantidade vendida', data: prodValues, backgroundColor: '#2ecc71' }] }, options: { indexAxis: 'y', responsive: true } });
    } catch (e) {
        console.warn('Erro ao renderizar gráficos do dashboard', e);
    }
</script>
@endpush
