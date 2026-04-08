<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('produtos', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('codigo_barras')->nullable(); // Adicione esta linha
        $table->text('descricao')->nullable();
        $table->decimal('preco_antigo', 10, 2)->nullable();
        $table->decimal('preco_atual', 10, 2);
        $table->decimal('preco_de_custo', 10, 2);
        $table->integer('quantidade')->default(0);
        $table->string('marca')->nullable();
        $table->string('categoria')->nullable();
        $table->boolean('destaque')->default(false);
        $table->boolean('ativo')->default(true);
        $table->timestamps();
    });

    
}

    /**
     * Reverse the migrations.
     */
  
};
