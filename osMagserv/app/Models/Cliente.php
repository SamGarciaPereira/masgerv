<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; 
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cliente extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'matriz_id',
        'nome',
        'documento',
        'responsavel',
        'email',
        'telefone',
        'cep',
        'logradouro',
        'numero',
        'bairro',
        'cidade',
        'estado',
    ];

    public function matriz(){
        return $this->belongsTo(Cliente::class, 'matriz_id');
    }

    public function filiais(){
        return $this->hasMany(Cliente::class, 'matriz_id');
    }

    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class);
    }

    public function contratoAtivo(): HasOne
    {
        return $this->hasOne(Contrato::class)
                    ->where('ativo', true)
                    ->latestOfMany(); 
    }
}