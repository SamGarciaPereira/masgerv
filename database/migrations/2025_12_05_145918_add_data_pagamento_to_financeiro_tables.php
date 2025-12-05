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
        Schema::table('contas_pagars', function (Blueprint $table) {
            $table->date('data_pagamento')->nullable()->after('valor');
        });

        Schema::table('contas_recebers', function(Blueprint $table){
            $table->date('data_recebimento')->nullable()->after('valor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contas_pagars', function (Blueprint $table) {
        $table->dropColumn('data_pagamento');
        });

        Schema::table('contas_recebers', function (Blueprint $table) {
            $table->dropColumn('data_recebimento');
        });
        }
};
