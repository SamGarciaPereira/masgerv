<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tables = [
        'orcamentos',
        'processos',
        'clientes',
        'contratos',
        'solicitacoes',
        'manutencoes',
        'contas_pagars',   
        'contas_recebers'
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'last_user_id')) {
                        $table->foreignId('last_user_id')
                              ->nullable()
                              ->after('updated_at')
                              ->constrained('users')
                              ->nullOnDelete();
                    }
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['last_user_id']);
                    $table->dropColumn('last_user_id');
                });
            }
        }
    }
};