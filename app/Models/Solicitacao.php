<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solicitacao extends Model
{

    use HasFactory;

    protected $table = 'solicitacoes';

    protected $fillable = [
        'tipo',
        'dados',
        'status',
        'data_solicitacao',
        'data_resolucao',
    ];

    protected $casts = [
        'dados' => 'array',
        'data_solicitacao' => 'datetime',
        'data_resolucao' => 'datetime',
    ];
}
