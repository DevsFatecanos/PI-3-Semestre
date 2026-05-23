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

    protected $appends = ['url_imagem', 'ean_pictures_url', 'stock_status'];

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

    /**
     * Retorna a URL da API EAN Pictures para fallback de imagem.
     */
    public function getEanPicturesUrlAttribute(): ?string
    {
        return app(ProductImageResolver::class)->fallbackUrl($this->codigo_barras);
    }

    /**
     * Retorna o status do estoque com base em thresholds configuráveis
     * Valores retornados: 'critical' (quase esgotado), 'low', 'ok', 'out'
     */
    public function getStockStatusAttribute()
    {
        $qty = (int) ($this->attributes['quantidade'] ?? 0);

        if ($qty <= 0) {
            return 'out';
        }

        $critical = config('stock.critical_threshold', 1);
        $low = config('stock.low_threshold', 5);

        if ($qty <= $critical) {
            return 'critical';
        }

        if ($qty <= $low) {
            return 'low';
        }

        return 'ok';
    }

    protected $casts = [
        'preco_antigo' => 'decimal:2',
        'preco_atual' => 'decimal:2',
        'preco_de_custo' => 'decimal:2',
        'destaque' => 'boolean',
        'ativo' => 'boolean',
    ];

    public function favoritos()
    {
        return $this->hasMany(Favorito::class);
    }

    public function pedidoItens()
    {
        return $this->hasMany(PedidoItem::class);
    }
}
