<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Cliente;

class ClienteObserver
{
    /**
     * Handle the Cliente "created" event.
     */
    public function created(Cliente $cliente): void
    {
        Activity::create([
            'description' => "Cliente '{$cliente->nome}' foi cadastrado."
        ]);
    }

    /**
     * Handle the Cliente "updated" event.
     */
    public function updated(Cliente $cliente): void
    {
        Activity::create([
            'description' => "Cliente '{$cliente->nome}' foi atualizado."
        ]);
    }

    /**
     * Handle the Cliente "deleted" event.
     */
    public function deleted(Cliente $cliente): void
    {
        Activity::create([
            'description' => "Cliente '{$cliente->nome}' foi removido."
        ]);
    }
}
