<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'user_id',
        'nome_cliente',
        'email_cliente',
        'telefone_cliente',
        'metodo_pagamento',
        'status',
        'referencia',
        'provedor',
        'total',
        'observacoes',
        'data_pagamento',
        'email_enviado_em',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'data_pagamento' => 'datetime',
        'email_enviado_em' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itens()
    {
        return $this->hasMany(PedidoItem::class);
    }
}
