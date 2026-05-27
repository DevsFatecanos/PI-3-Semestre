<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminPedidosController extends Controller
{
    public function index()
    {
        $pedidos = DB::table('pedidos')
            ->leftJoin('users', 'pedidos.user_id', '=', 'users.id')
            ->select(
                'pedidos.id',
                'pedidos.nome_cliente',
                'pedidos.email_cliente',
                'pedidos.telefone_cliente',
                'pedidos.metodo_pagamento',
                'pedidos.status',
                'pedidos.total',
                'pedidos.created_at',
                'users.name as user_name'
            )
            ->orderBy('pedidos.created_at', 'desc')
            ->paginate(15);

        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function show($id)
    {
        $pedido = DB::table('pedidos')
            ->leftJoin('users', 'pedidos.user_id', '=', 'users.id')
            ->where('pedidos.id', $id)
            ->select(
                'pedidos.*',
                'users.name as user_name'
            )
            ->first();

        if (!$pedido) {
            abort(404, 'Pedido não encontrado');
        }

        $itens = DB::table('pedido_itens')
            ->where('pedido_id', $id)
            ->get();

        return view('admin.pedidos.show', compact('pedido', 'itens'));
    }
}
