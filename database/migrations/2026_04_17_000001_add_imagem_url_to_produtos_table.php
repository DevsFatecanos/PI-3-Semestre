<?php

use App\Services\ProductImageResolver;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->string('imagem_url')->nullable()->after('categoria');
        });

        $resolver = app(ProductImageResolver::class);

        DB::table('produtos')
            ->select('id', 'nome', 'marca', 'categoria', 'codigo_barras')
            ->orderBy('id')
            ->chunkById(100, function ($produtos) use ($resolver) {
                foreach ($produtos as $produto) {
                    DB::table('produtos')
                        ->where('id', $produto->id)
                        ->update([
                            'imagem_url' => $resolver->resolve(
                                $produto->nome,
                                $produto->marca,
                                $produto->categoria,
                                $produto->codigo_barras,
                            ),
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn('imagem_url');
        });
    }
};