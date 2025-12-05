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
        Schema::create('contas_pagars', function (Blueprint $table) {
            $table->id();
            $table->string('fornecedor');
            $table->string('descricao');
            $table->string('danfe')->nullable();
            $table->decimal('valor', 10, 2);
            $table->date('data_vencimento')->nullable();
            $table->enum('status', ['Pendente', 'Pago', 'Atrasado'])->default('Pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas_pagars');
    }
};
