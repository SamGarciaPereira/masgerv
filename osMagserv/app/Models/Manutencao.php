<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manutencao extends Model
{
    protected $table = 'manutencoes';

    protected $fillable = [
        'cliente_id',
        'chamado',
        'solicitante',
        'descricao',
        'data_inicio_atendimento',
        'data_fim_atendimento',
        'tipo',
        'status',   
    ];

    protected $casts = [
        'data_inicio_atendimento' => 'date',
        'data_fim_atendimento' => 'date',
    ];

     public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    
    
}
