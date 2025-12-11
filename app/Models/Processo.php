<?php

namespace App\Models;

use App\Traits\HasLastUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Processo extends Model
{
    use HasFactory;
    use HasLastUser;

    protected $fillable = [
        'orcamento_id',
        'nf',
        'status',
        'last_user_id',
    ];

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function anexos()
    {
        return $this->morphMany(Anexo::class, 'anexable');
    }

    public function contasReceber()
    {
        return $this->hasMany(ContasReceber::class);
    }
}
