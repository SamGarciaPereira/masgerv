<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagamentoParcial extends Model
{
    protected $table = 'pagamentos_parciais';

    protected $fillable = [
        'contas_receber_id',
        'valor',
        'data_pagamento',
        'observacao',
    ];

    protected $casts = [
        'data_pagamento' => 'date',
        'valor' => 'decimal:2',
    ];

    public function contasReceber()
    {
        return $this->belongsTo(ContasReceber::class, 'contas_receber_id');
    }
}
