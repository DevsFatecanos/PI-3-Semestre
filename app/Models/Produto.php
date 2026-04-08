<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    // Definimos quais campos podem ser preenchidos
    protected $fillable = [
        'nome',
        'codigo_barras',
        'descricao',
        'preco_antigo',
        'preco_atual',
        'preco_de_custo',
        'quantidade',
        'marca',
        'categoria',
        'destaque',
        'ativo',
    ];
public function getUrlImagemAttribute()
{
    if ($this->imagem && file_exists(public_path('storage/' . $this->imagem))) {
        return asset('storage/' . $this->imagem);
    }

    // Retorna o placeholder se a imagem não existir
    return "https://placehold.co/400x400/e2e8f0/1e293b?text=" . urlencode($this->nome);
}
   
    protected $casts = [
        'preco_antigo' => 'decimal:2',
        'preco_atual' => 'decimal:2',
        'preco_de_custo' => 'decimal:2',
        'destaque' => 'boolean',
        'ativo' => 'boolean',
    ];
}