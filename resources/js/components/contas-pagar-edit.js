document.addEventListener('DOMContentLoaded', function() {
    const tipoRecorrenciaInput = document.getElementById('tipo_recorrencia'); 
    const statusSelect = document.getElementById('status');
    
    if (!tipoRecorrenciaInput || !statusSelect) return;

    const divDiaFixo = document.getElementById('div_dia_fixo');
    const divDataVencimento = document.getElementById('div_data_vencimento');
    const inputVencimento = document.getElementById('data_vencimento');
    const divDataPagamento = document.getElementById('div_data_pagamento');
    const inputPagamento = document.getElementById('data_pagamento');

    function toggleFields() {
        const tipo = tipoRecorrenciaInput.value; 

        if (tipo === 'fixa') {
            divDiaFixo.classList.remove('hidden');
            divDataVencimento.classList.add('hidden');
            
            if(inputVencimento) inputVencimento.disabled = true;
        } else {
            divDiaFixo.classList.add('hidden');
            divDataVencimento.classList.remove('hidden');
            
            if(inputVencimento) inputVencimento.disabled = false;
        }
    }

    function togglePagamento() {
        if (statusSelect.value === 'Pago') {
            divDataPagamento.classList.remove('opacity-50');
            inputPagamento.disabled = false;
            inputPagamento.required = true;
            
            if(!inputPagamento.value) {
                inputPagamento.value = new Date().toISOString().split('T')[0];
            }
        } else {
            divDataPagamento.classList.add('opacity-50');
            inputPagamento.required = false;
        }
    }

    statusSelect.addEventListener('change', togglePagamento);

    toggleFields();
    togglePagamento();
});