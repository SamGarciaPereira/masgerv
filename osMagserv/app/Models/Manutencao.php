<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manutencao extends Model
{
    protected $fillabe = [
        'cliente_id',
        'chamado',
        'descricao',
        'data_inicio_agendamento',
        'data_fim_agendamento',
        'tipo',
        'status',   
    ];

    protected $casts = [
        'data_agendamento' => 'date',
    ];

     public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    
    
}
