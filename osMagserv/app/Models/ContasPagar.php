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
        'status',
    ];
    protected $casts = [
        'data_vencimento' => 'date',
    ];
}
