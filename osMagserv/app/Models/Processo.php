<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Processo extends Model
{
    use HasFactory;

    protected $fillable = [
        'orcamento_id',
        'nf',
        'status',
    ];

    public function orcamento(){
        return $this->belongsTo(Orcamento::class);
    }
    
}
