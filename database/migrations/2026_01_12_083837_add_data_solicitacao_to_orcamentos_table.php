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
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->date('data_solicitacao')->nullable()->after('cliente_id');
        });

        DB::statement("UPDATE orcamentos SET data_solicitacao = DATE(created_at) WHERE data_solicitacao IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropColumn('data_solicitacao');
        });
    }
};
