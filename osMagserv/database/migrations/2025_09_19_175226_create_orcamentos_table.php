<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orcamentos', function (Blueprint $table) {
            $table->id(); // ID único para o orçamento

            // Relação com a tabela de clientes
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');

            $table->string('numero_proposta')->unique(); // N. PROPOSTA (único)
            $table->date('data_envio');               // DATA DE ENVIO
            $table->text('escopo');                   // ESCOPO 
            $table->decimal('valor', 10, 2);          // VALOR (ex: 99999999.99)
            $table->integer('revisao')->default(0);   // REVISÃO (começa em 0)
            
            $table->timestamps(); // Cria as colunas created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orcamentos');
    }
};
