<?php

namespace App\Models;

use App\Services\ProductImageResolver;
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
        'imagem_url',
        'destaque',
        'ativo',
    ];

    protected $appends = ['url_imagem'];

    public function getUrlImagemAttribute()
    {
        if (!empty($this->attributes['imagem_url'] ?? null)) {
            return $this->attributes['imagem_url'];
        }

        if (isset($this->attributes['imagem']) && file_exists(public_path('storage/' . $this->attributes['imagem']))) {
            return asset('storage/' . $this->attributes['imagem']);
        }

        return app(ProductImageResolver::class)->resolve(
            $this->nome,
            $this->marca,
            $this->categoria,
            $this->codigo_barras,
        );
    }
   
    protected $casts = [
        'preco_antigo' => 'decimal:2',
        'preco_atual' => 'decimal:2',
        'preco_de_custo' => 'decimal:2',
        'destaque' => 'boolean',
        'ativo' => 'boolean',
    ];
}