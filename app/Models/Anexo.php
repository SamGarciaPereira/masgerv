<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anexo extends Model
{
    protected $fillable = [
        'nome_original',
        'caminho',
        'anexable_id',
        'anexable_type',
    ];

    public function anexable()
    {
        return $this->morphTo();
    }
}
