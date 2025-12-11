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
        'data_recebimento',
        'status',
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'data_recebimento' => 'date',
    ];

    public function processo()
    {
        return $this->belongsTo(Processo::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function anexos()
    {
        return $this->morphMany(Anexo::class, 'anexable');
    }

    public function pagamentosParciais()
    {
        return $this->hasMany(PagamentoParcial::class, 'contas_receber_id');
    }

    /**
     * Calcula o total pago através de pagamentos parciais
     */
    public function totalPago()
    {
        return $this->pagamentosParciais()->sum('valor');
    }

    /**
     * Calcula o saldo restante a receber
     */
    public function saldoRestante()
    {
        return $this->valor - $this->totalPago();
    }

    /**
     * Verifica se está totalmente pago
     */
    public function isTotalmentePago()
    {
        return $this->saldoRestante() <= 0;
    }
}
