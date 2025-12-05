<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContasPagar extends Model
{
    protected $fillable = [
        'fornecedor',
        'descricao',
        'danfe',
        'valor',
        'data_vencimento',
        'data_pagamento',
        'status',
    ];
    protected $casts = [
        'data_vencimento' => 'date',
        'data_pagamento' => 'date'
    ];

    public function anexos()
    {
        return $this->morphMany(Anexo::class, 'anexable');
    }
}
