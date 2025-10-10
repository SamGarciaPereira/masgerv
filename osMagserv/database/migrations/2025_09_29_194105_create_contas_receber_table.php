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
        Schema::create('contas_receber', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_id')->nullable()->constrained('processos')->onDelete('set null');
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('descricao');    
            $table->string('nf')->nullable();
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
        Schema::dropIfExists('contas_receber');
    }
};
