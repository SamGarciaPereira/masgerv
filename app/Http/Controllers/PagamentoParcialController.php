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
            $valorFormatado = number_format($validatedData['valor'], 2, ',', '.');
            $saldoFormatado = number_format($saldoRestante, 2, ',', '.');
            $mensagem = sprintf(
                'O valor do pagamento (R$ %s) excede o saldo restante (R$ %s).',
                $valorFormatado,
                $saldoFormatado
            );

            return redirect()->back()
                ->withErrors(['valor' => $mensagem])
                ->withInput();
        }

        PagamentoParcial::create($validatedData);

        // Atualiza o status da conta automaticamente
        $contaReceber->atualizarStatus();

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

        // Atualiza o status da conta automaticamente
        $contaReceber->atualizarStatus();

        return redirect()->back()
            ->with('success', 'Pagamento parcial removido com sucesso!');
    }
}
