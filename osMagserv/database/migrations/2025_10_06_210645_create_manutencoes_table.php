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
        Schema::create('manutencoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('chamado')->nullable();
            $table->string('solicitante')->nullable();
            $table->text('descricao');
            $table->date('data_inicio_atendimento');
            $table->date('data_fim_atendimento')->nullable();
            $table->enum('tipo', ['Preventiva', 'Corretiva']);
            $table->enum('status', ['Agendada', 'Em Andamento', 'Concluída', 'Cancelada']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manutencoes');
    }
};
