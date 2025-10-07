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
        Schema::create('processos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('orcamento_id')->unique()->constrained('orcamentos')->onDelete('cascade');
            $table->string('nf')->nullable();
            $table->enum('status', ['Em aberto', 'Finalizado', 'Faturado'])->default('Em aberto');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processos');
    }
};
