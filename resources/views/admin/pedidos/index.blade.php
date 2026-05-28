@extends('layouts.app')

@section('title', 'Gestão de Pedidos')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="mb-2">
            <i class="fas fa-shipping-fast text-primary"></i> Gestão de Pedidos
        </h1>
        <p class="text-muted">Visualize e gerencie todos os pedidos realizados pelos clientes</p>
    </div>
    <script id="#userwayAccessibilityIcon" src = " https://cdn.userway.org/widget.js "  data-account = " wHedvuvp49 " ></script>
    <style>
    #userwayAccessibilityIcon {
        margin-top: 500px;
    }
    </style>

    @if($pedidos->isEmpty())
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i> Nenhum pedido registrado ainda.
        </div>
    @else
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Lista de Pedidos</span>
                <span class="badge bg-primary">{{ $pedidos->total() }} pedido(s)</span>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Método de Pagamento</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $pedido)
                            <tr>
                                <td data-label="#ID"><strong>#{{ $pedido->id }}</strong></td>
                                <td data-label="Data">
                                    <small>{{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y H:i') }}</small>
                                </td>
                                <td data-label="Cliente">
                                    <div>{{ $pedido->nome_cliente }}</div>
                                    @if($pedido->user_name)
                                        <small class="text-muted">({{ $pedido->user_name }})</small>
                                    @endif
                                </td>
                                <td data-label="Email"><small>{{ $pedido->email_cliente }}</small></td>
                                <td data-label="Telefone"><small>{{ $pedido->telefone_cliente ?? '—' }}</small></td>
                                <td data-label="Método de Pagamento">
                                    <span class="badge bg-info">
                                        {{ ucfirst($pedido->metodo_pagamento) }}
                                    </span>
                                </td>
                                <td data-label="Valor Total"><strong>R$ {{ number_format($pedido->total, 2, ',', '.') }}</strong></td>
                                <td data-label="Status">
                                    @php
                                        $statusClass = match($pedido->status) {
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'completed' => 'success',
                                            'failed' => 'danger',
                                            'cancelled' => 'secondary',
                                            default => 'secondary'
                                        };
                                        $statusLabel = match($pedido->status) {
                                            'pending' => 'Pendente',
                                            'approved' => 'Aprovado',
                                            'completed' => 'Concluído',
                                            'failed' => 'Falhou',
                                            'cancelled' => 'Cancelado',
                                            default => ucfirst($pedido->status)
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td data-label="Ações">
                                    <a href="{{ route('admin.pedidos.show', $pedido->id) }}"
                                       class="btn btn-sm btn-primary"
                                       title="Visualizar detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($pedidos->hasPages())
                <div class="card-footer">
                    {{ $pedidos->links() }}
                </div>
            @endif
        </div>
    @endif
</div>

<style>
    .table-responsive {
        overflow-x: auto;
    }

    @media (max-width: 768px) {
        .table thead {
            display: none;
        }

        .table tbody tr {
            display: block;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px;
        }

        .table tbody td {
            display: block;
            text-align: right;
            padding-left: 50%;
            position: relative;
        }

        .table tbody td::before {
            content: attr(data-label);
            position: absolute;
            left: 6px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: #6c757d;
        }
    }
</style>
@endsection
