<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasLastUser;

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

    public function orcamento(){
        return $this->belongsTo(Orcamento::class);
    }

    public function anexos(){
        return $this->morphMany(Anexo::class, 'anexable');
    }
    
}
