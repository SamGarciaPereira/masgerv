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
        Schema::table('clientes', function (Blueprint $table) {            
            $table->string('email')->nullable()->change();
            $table->string('responsavel')->nullable()->change();
            $table->unique('telefone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
                        
            $table->string('email')->nullable(false)->change();
            $table->string('responsavel')->nullable(false)->change();
            $table->dropUnique(['telefone']);
        });
    }
};
