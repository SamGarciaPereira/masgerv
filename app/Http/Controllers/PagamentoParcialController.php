<?php

namespace App\Http\Controllers;

use App\Models\ContasReceber;
use App\Models\PagamentoParcial;
use Illuminate\Http\Request;

class PagamentoParcialController extends Controller
{
    /**
     * Store a newly created partial payment in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'contas_receber_id' => 'required|exists:contas_recebers,id',
            'valor' => 'required|numeric|min:0.01',
            'data_pagamento' => 'required|date',
            'observacao' => 'nullable|string|max:255',
        ]);

        $contaReceber = ContasReceber::findOrFail($validatedData['contas_receber_id']);

        // Verifica se o valor não excede o saldo restante
        $saldoRestante = $contaReceber->saldoRestante();
        if ($validatedData['valor'] > $saldoRestante) {
            return redirect()->back()
                ->withErrors(['valor' => 'O valor do pagamento (R$ '.number_format($validatedData['valor'], 2, ',', '.').') excede o saldo restante (R$ '.number_format($saldoRestante, 2, ',', '.').')'])
                ->withInput();
        }

        PagamentoParcial::create($validatedData);

        // Atualiza o status da conta se estiver totalmente pago
        if ($contaReceber->isTotalmentePago()) {
            $contaReceber->update(['status' => 'Pago']);
        } else {
            // Se há pagamentos parciais mas não está totalmente pago, marca como Pendente
            $contaReceber->update(['status' => 'Pendente']);
        }

        return redirect()->back()
            ->with('success', 'Pagamento parcial registrado com sucesso!');
    }

    /**
     * Remove the specified partial payment from storage.
     */
    public function destroy(PagamentoParcial $pagamentoParcial)
    {
        $contaReceber = $pagamentoParcial->contasReceber;
        $pagamentoParcial->delete();

        // Atualiza o status da conta após remover o pagamento
        if ($contaReceber->isTotalmentePago()) {
            $contaReceber->update(['status' => 'Pago']);
        } else {
            $contaReceber->update(['status' => 'Pendente']);
        }

        return redirect()->back()
            ->with('success', 'Pagamento parcial removido com sucesso!');
    }
}
