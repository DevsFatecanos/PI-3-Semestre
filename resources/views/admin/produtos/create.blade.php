@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">Adicionar Novo Produto</h1>
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

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.produtos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-8">
                        <label for="nome" class="form-label">Nome do Produto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                               id="nome" name="nome" value="{{ old('nome') }}" required>
                        @error('nome')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" class="form-control @error('marca') is-invalid @enderror" 
                               id="marca" name="marca" value="{{ old('marca') }}">
                        @error('marca')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="codigo_barras" class="form-label">Código de Barras (EAN)</label>
                        <input type="text" class="form-control @error('codigo_barras') is-invalid @enderror" 
                               id="codigo_barras" name="codigo_barras" value="{{ old('codigo_barras') }}">
                        @error('codigo_barras')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="categoria" class="form-label">Categoria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('categoria') is-invalid @enderror" 
                               id="categoria" name="categoria" value="{{ old('categoria') }}" required 
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
                                  id="descricao" name="descricao" rows="3">{{ old('descricao') }}</textarea>
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
                                   id="preco_de_custo" name="preco_de_custo" value="{{ old('preco_de_custo', '0.00') }}">
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
                                   id="preco_antigo" name="preco_antigo" value="{{ old('preco_antigo', '0.00') }}">
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
                                   id="preco_atual" name="preco_atual" value="{{ old('preco_atual', '0.00') }}" required>
                            @error('preco_atual')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="quantidade" class="form-label">Quantidade em Estoque <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('quantidade') is-invalid @enderror" 
                               id="quantidade" name="quantidade" value="{{ old('quantidade', '0') }}" required>
                        @error('quantidade')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="imagem_url" class="form-label">URL da Imagem</label>
                        <input type="url" class="form-control @error('imagem_url') is-invalid @enderror" 
                               id="imagem_url" name="imagem_url" value="{{ old('imagem_url') }}" 
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
                                   {{ old('ativo') ? 'checked' : 'checked' }}>
                            <label class="form-check-label" for="ativo">
                                Produto Ativo (visível na loja)
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="destaque" name="destaque" value="1" 
                                   {{ old('destaque') ? 'checked' : '' }}>
                            <label class="form-check-label" for="destaque">
                                Marcar como Destaque
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-lg px-4" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none; color: white; font-weight: 600;">
                    <i class="fas fa-save"></i> Adicionar Produto
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-lg px-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none; color: white; font-weight: 600;">
                    Cancelar
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
