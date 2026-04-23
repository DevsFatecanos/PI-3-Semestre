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
                            <img src="{{ $produto->url_imagem }}" alt="{{ $produto->nome }}" 
                                 style="max-width: 50px; height: auto; border-radius: 4px;">
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
                            <span class="badge bg-{{ $produto->quantidade > 0 ? 'success' : 'danger' }}">
                                {{ $produto->quantidade }}
                            </span>
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
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.produtos.edit', $produto->id) }}" 
                                   class="btn btn-outline-primary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('admin.produtos.toggle', $produto->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-warning" 
                                            title="{{ $produto->ativo ? 'Desativar' : 'Ativar' }}">
                                        <i class="fas fa-{{ $produto->ativo ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>

                                <form action="{{ route('admin.produtos.toggleDestaque', $produto->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-info" 
                                            title="{{ $produto->destaque ? 'Remover de destaque' : 'Marcar como destaque' }}">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </form>

                                <form action="{{ route('admin.produtos.destroy', $produto->id) }}" method="POST" style="display: inline;"
                                      onsubmit="return confirm('Tem certeza que deseja deletar este produto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Deletar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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

<style>
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
