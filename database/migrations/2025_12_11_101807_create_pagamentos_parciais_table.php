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
        Schema::create('pagamentos_parciais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contas_receber_id')->constrained('contas_recebers')->onDelete('cascade');
            $table->decimal('valor', 10, 2);
            $table->date('data_pagamento');
            $table->string('observacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos_parciais');
    }
};
