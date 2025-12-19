document.addEventListener('DOMContentLoaded', function() {
    const selectTipo = document.getElementById('tipo_recorrencia');
    const divQtdParcelas = document.getElementById('div_qtd_parcelas');
    const divDiaFixo = document.getElementById('div_dia_fixo');
    const divDataVencimento = document.getElementById('div_data_vencimento');
    const divDataPagamento = document.getElementById('div_data_pagamento');
    
    const inputVencimento = document.getElementById('data_vencimento');
    const labelVencimento = document.getElementById('label_vencimento');
    const labelValor = document.querySelector('label[for="valor"]');
    const avisoPagamento = document.getElementById('aviso_pagamento_parcial');

    function toggleFields() {
        const val = selectTipo.value;

        divQtdParcelas.classList.add('hidden');
        divDiaFixo.classList.add('hidden');
        divDataVencimento.classList.add('hidden'); 
        avisoPagamento.classList.add('hidden');
        
        if(labelValor) labelValor.innerHTML = 'Valor (R$) <span class="text-red-500">*</span>';
        if(labelVencimento) labelVencimento.innerHTML = 'Data Vencimento <span class="text-red-500">*</span>';
        
        inputVencimento.disabled = false;

        if (val === 'unica') {
            divDataVencimento.classList.remove('hidden');
        } 
        else if (val === 'parcelada') {
            divQtdParcelas.classList.remove('hidden');
            divDataVencimento.classList.remove('hidden');
            
            if(labelValor) labelValor.innerHTML = 'Valor da Parcela (R$) <span class="text-red-500">*</span>';
            if(labelVencimento) labelVencimento.innerHTML = 'Data Vencimento (1ª Parcela) <span class="text-red-500">*</span>';
            
            avisoPagamento.classList.remove('hidden'); 
        } 
        else if (val === 'fixa') {
            divDiaFixo.classList.remove('hidden');
            
            inputVencimento.value = '';
            inputVencimento.disabled = true; 
            
            avisoPagamento.classList.remove('hidden'); 
        }
    }

    selectTipo.addEventListener('change', toggleFields);
    toggleFields(); 
});