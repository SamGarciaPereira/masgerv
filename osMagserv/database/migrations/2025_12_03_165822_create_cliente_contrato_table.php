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
        Schema::create('cliente_contrato', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrato_id')->constrained('contratos')->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('contratos', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']); 
            $table->dropColumn('cliente_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente_contrato');
        
        Schema::table('contratos', function (Blueprint $table) {
            $table->foreignId('cliente_id')->nullable()->constrained('clientes');
        });
    }
};
