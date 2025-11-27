<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orcamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'numero_proposta',
        'data_envio',
        'data_aprovacao',
        'escopo',
        'valor',
        'revisao',
        'status',
    ];

    protected $casts = [
        'data_envio' => 'date',
        'data_aprovacao' => 'date',
        'valor' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function processo(){
        return $this->hasOne(Processo::class);
    }

    public function anexos()
    {
        return $this->morphMany(Anexo::class, 'anexable');
    }
}
