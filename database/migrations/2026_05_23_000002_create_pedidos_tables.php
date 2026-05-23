<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nome_cliente', 120);
            $table->string('email_cliente', 120);
            $table->string('telefone_cliente', 30)->nullable();
            $table->string('metodo_pagamento', 20);
            $table->string('status', 20)->default('pending');
            $table->string('referencia', 80)->unique();
            $table->string('provedor', 50)->default('simulador_local');
            $table->decimal('total', 12, 2);
            $table->text('observacoes')->nullable();
            $table->timestamp('data_pagamento')->nullable();
            $table->timestamp('email_enviado_em')->nullable();
            $table->timestamps();
        });

        Schema::create('pedido_itens', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->foreignId('produto_id')->nullable()->constrained('produtos')->nullOnDelete();
            $table->string('nome_produto');
            $table->string('categoria_produto')->nullable();
            $table->decimal('preco_unitario', 12, 2);
            $table->unsignedInteger('quantidade');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_itens');
        Schema::dropIfExists('pedidos');
    }
};
