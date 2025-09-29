<?php

namespace App\Observers;

use App\Models\ContasPagar;

class ContasPagarObserver
{
    /**
     * Handle the ContasPagar "created" event.
     */
    public function created(ContasPagar $contasPagar): void
    {
        //
    }

    /**
     * Handle the ContasPagar "updated" event.
     */
    public function updated(ContasPagar $contasPagar): void
    {
        //
    }

    /**
     * Handle the ContasPagar "deleted" event.
     */
    public function deleted(ContasPagar $contasPagar): void
    {
        //
    }

    /**
     * Handle the ContasPagar "restored" event.
     */
    public function restored(ContasPagar $contasPagar): void
    {
        //
    }

    /**
     * Handle the ContasPagar "force deleted" event.
     */
    public function forceDeleted(ContasPagar $contasPagar): void
    {
        //
    }
}
