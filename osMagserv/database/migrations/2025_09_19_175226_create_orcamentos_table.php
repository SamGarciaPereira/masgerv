<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orcamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('numero_proposta')->unique()->nullable();
            $table->date('data_envio')->nullable();
            $table->date('data_aprovacao')->nullable();
            $table->text('escopo')->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->integer('revisao')->default(0);
            $table->enum('status', ['Pendente', 'Em Andamento', 'Enviado', 'Aprovado'])->default('Pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamentos');
    }
};
