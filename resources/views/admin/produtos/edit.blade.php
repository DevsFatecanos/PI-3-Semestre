@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">Editar Produto</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5>Erros encontrados:</h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.produtos.update', $produto->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="nome" class="form-label">Nome do Produto <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                                       id="nome" name="nome" value="{{ old('nome', $produto->nome) }}" required>
                                @error('nome')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text" class="form-control @error('marca') is-invalid @enderror" 
                                       id="marca" name="marca" value="{{ old('marca', $produto->marca) }}">
                                @error('marca')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="codigo_barras" class="form-label">Código de Barras (EAN)</label>
                                <input type="text" class="form-control @error('codigo_barras') is-invalid @enderror" 
                                       id="codigo_barras" name="codigo_barras" value="{{ old('codigo_barras', $produto->codigo_barras) }}">
                                @error('codigo_barras')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="categoria" class="form-label">Categoria <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('categoria') is-invalid @enderror" 
                                       id="categoria" name="categoria" value="{{ old('categoria', $produto->categoria) }}" required 
                                       list="categorias-list">
                                <datalist id="categorias-list">
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat }}">
                                    @endforeach
                                </datalist>
                                @error('categoria')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                          id="descricao" name="descricao" rows="3">{{ old('descricao', $produto->descricao) }}</textarea>
                                @error('descricao')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="preco_de_custo" class="form-label">Preço de Custo</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" step="0.01" class="form-control @error('preco_de_custo') is-invalid @enderror" 
                                           id="preco_de_custo" name="preco_de_custo" value="{{ old('preco_de_custo', $produto->preco_de_custo) }}">
                                    @error('preco_de_custo')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="preco_antigo" class="form-label">Preço Antigo (Oferta)</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" step="0.01" class="form-control @error('preco_antigo') is-invalid @enderror" 
                                           id="preco_antigo" name="preco_antigo" value="{{ old('preco_antigo', $produto->preco_antigo) }}">
                                    @error('preco_antigo')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="preco_atual" class="form-label">Preço Atual <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" step="0.01" class="form-control @error('preco_atual') is-invalid @enderror" 
                                           id="preco_atual" name="preco_atual" value="{{ old('preco_atual', $produto->preco_atual) }}" required>
                                    @error('preco_atual')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="quantidade" class="form-label">Quantidade em Estoque <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('quantidade') is-invalid @enderror" 
                                       id="quantidade" name="quantidade" value="{{ old('quantidade', $produto->quantidade) }}" required>
                                @error('quantidade')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="imagem_url" class="form-label">URL da Imagem</label>
                                <input type="url" class="form-control @error('imagem_url') is-invalid @enderror" 
                                       id="imagem_url" name="imagem_url" value="{{ old('imagem_url', $produto->imagem_url) }}" 
                                       placeholder="https://exemplo.com/imagem.jpg">
                                @error('imagem_url')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Cole uma URL completa da imagem do produto</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="ativo" name="ativo" value="1" 
                                           {{ old('ativo', $produto->ativo) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ativo">
                                        Produto Ativo (visível na loja)
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="destaque" name="destaque" value="1" 
                                           {{ old('destaque', $produto->destaque) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="destaque">
                                        Marcar como Destaque
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-lg px-4" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none; color: white; font-weight: 600;">
                                    <i class="fas fa-save"></i> Atualizar Produto
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-lg px-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none; color: white; font-weight: 600;">
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Prévia da Imagem</h6>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $produto->url_imagem }}" alt="{{ $produto->nome }}" 
                         class="img-fluid" style="max-width: 100%; height: auto; border-radius: 8px;">
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Informações do Produto</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-6">ID:</dt>
                        <dd class="col-sm-6">{{ $produto->id }}</dd>

                        <dt class="col-sm-6">Criado em:</dt>
                        <dd class="col-sm-6">{{ $produto->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-6">Atualizado em:</dt>
                        <dd class="col-sm-6">{{ $produto->updated_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-6">Margem:</dt>
                        <dd class="col-sm-6">
                            @if($produto->preco_de_custo > 0)
                                @php
                                    $margem = (($produto->preco_atual - $produto->preco_de_custo) / $produto->preco_de_custo) * 100;
                                @endphp
                                <span class="badge bg-{{ $margem >= 20 ? 'success' : ($margem >= 10 ? 'warning' : 'danger') }}">
                                    {{ number_format($margem, 1, ',', '.') }}%
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-danger">
                    <h6 class="mb-0 text-white">Zona de Risco</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.produtos.destroy', $produto->id) }}" method="POST"
                          onsubmit="return confirm('Tem certeza que deseja deletar este produto? Esta ação é irreversível!');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> Deletar Produto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
