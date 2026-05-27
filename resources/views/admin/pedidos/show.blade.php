@extends('layouts.app')

@section('title', 'Detalhes do Pedido #' . $pedido->id)

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.pedidos.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
        <h1 class="mb-2">
            <i class="fas fa-receipt text-primary"></i> Pedido #{{ $pedido->id }}
        </h1>
        <p class="text-muted">Detalhes completos do pedido</p>
    </div>

    <div class="row">
        <!-- Informações do Pedido -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Informações do Pedido
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Data do Pedido</label>
                            <p class="mb-3">
                                <strong>{{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y às H:i') }}</strong>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Status</label>
                            <p class="mb-3">
                                @php
                                    $statusClass = match($pedido->status) {
                                        'pending' => 'warning',
                                        'completed' => 'success',
                                        'failed' => 'danger',
                                        'cancelled' => 'secondary',
                                        default => 'secondary'
                                    };
                                    $statusLabel = match($pedido->status) {
                                        'pending' => 'Pendente',
                                        'completed' => 'Concluído',
                                        'failed' => 'Falhou',
                                        'cancelled' => 'Cancelado',
                                        default => ucfirst($pedido->status)
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }} p-2">{{ $statusLabel }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Valor Total</label>
                            <p class="mb-3">
                                <strong class="text-success fs-5">R$ {{ number_format($pedido->total, 2, ',', '.') }}</strong>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Método de Pagamento</label>
                            <p class="mb-3">
                                <strong>{{ ucfirst($pedido->metodo_pagamento) }}</strong>
                            </p>
                        </div>
                    </div>

                    @if($pedido->observacoes)
                        <div class="mb-3">
                            <label class="form-label text-muted">Observações</label>
                            <p class="mb-0">{{ $pedido->observacoes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Itens do Pedido -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-box"></i> Produtos ({{ $itens->count() }} item(ns))
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th>Qtd</th>
                                <th>Preço Unit.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($itens as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->nome_produto }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $item->categoria_produto ?? '—' }}</small>
                                    </td>
                                    <td><strong>{{ $item->quantidade }}</strong></td>
                                    <td>R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                                    <td>
                                        <strong>R$ {{ number_format($item->subtotal, 2, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="border-top: 2px solid #dee2e6;">
                                <td colspan="4" class="text-end">
                                    <strong>Total do Pedido:</strong>
                                </td>
                                <td>
                                    <strong class="text-success fs-5">R$ {{ number_format($pedido->total, 2, ',', '.') }}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Dados do Cliente -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user"></i> Dados do Cliente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Nome</label>
                        <p class="mb-3"><strong>{{ $pedido->nome_cliente }}</strong></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="mb-3">
                            <a href="mailto:{{ $pedido->email_cliente }}">
                                {{ $pedido->email_cliente }}
                            </a>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Telefone</label>
                        <p class="mb-3">
                            @if($pedido->telefone_cliente)
                                <a href="tel:{{ preg_replace('/\D/', '', $pedido->telefone_cliente) }}">
                                    {{ $pedido->telefone_cliente }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </p>
                    </div>

                    @if($pedido->user_name)
                        <div class="mb-3">
                            <label class="form-label text-muted">Usuário (Conta)</label>
                            <p class="mb-0"><strong>{{ $pedido->user_name }}</strong></p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Resumo -->
            <div class="card bg-light">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie"></i> Resumo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Quantidade de Itens:</span>
                        <strong>{{ $itens->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total de Produtos:</span>
                        <strong>{{ $itens->sum('quantidade') }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fs-6">Valor Total:</span>
                        <strong class="fs-5 text-success">R$ {{ number_format($pedido->total, 2, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
