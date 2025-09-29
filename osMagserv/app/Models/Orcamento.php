<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orcamento extends Model
{
    use HasFactory;

    // Se a tabela não for 'orcamentos', descomente e ajuste:
    // protected $table = 'nome_da_tabela';

    protected $fillable = [
        'cliente_id',
        'numero_proposta',
        'data_envio',
        'data_limite_envio',
        'data_aprovacao',
        'escopo',
        'valor',
        'revisao',
        'status',
    ];

    protected $casts = [
        'data_envio' => 'date',
        'data_limite_envio' => 'date',
        'data_aprovacao' => 'date',
        'valor' => 'decimal:2',
    ];

    /**
     * Define o relacionamento: um Orçamento pertence a um Cliente.
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
