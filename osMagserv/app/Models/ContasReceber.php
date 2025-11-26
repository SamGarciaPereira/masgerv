<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContasReceber extends Model
{
    protected $fillable = [
        'processo_id',
        'cliente_id',
        'descricao',
        'nf',
        'valor',
        'data_vencimento',
        'status',
    ];

    protected $casts = [
        'data_vencimento' => 'date',
    ];

    public function processo()
    {
        return $this->belongsTo(Processo::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function anexos(){
        return $this->morphMany(Anexo::class, 'anexavel');
    }
}
