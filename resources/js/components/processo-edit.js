document.addEventListener('DOMContentLoaded', function() {
    const formProcesso = document.getElementById('form-processo');
    const statusSelect = document.getElementById('status');
    const btnOpenModal = document.getElementById('btn-open-modal');
    const modal = document.getElementById('faturamentoModal');
    const btnConfirm = document.getElementById('btn-confirm-modal');
    const containerParcelas = document.getElementById('container-parcelas');
    const btnAddParcela = document.getElementById('btn-add-parcela');
    const spanTotal = document.getElementById('total-parcelas');
    const msgValidacao = document.getElementById('msg-validacao');
    const resumoParcelas = document.getElementById('resumo-parcelas');

    const valorOrcamento = parseFloat(formProcesso.getAttribute('data-valor-orcamento')) || 0;
    
    let parcelasIndex = containerParcelas.querySelectorAll('tr').length;
    let previousStatus = statusSelect.getAttribute('data-original');

    function formatCurrency(value) {
        return value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function atualizarTotal() {
        let total = 0;
        let count = 0;

        document.querySelectorAll('.parcela-valor').forEach(input => {
            total += parseFloat(input.value) || 0;
            count++;
        });

        spanTotal.innerText = formatCurrency(total);
        
        if (Math.abs(total - valorOrcamento) < 0.01) {
            msgValidacao.innerHTML = "(Confere)";
            msgValidacao.className = "ml-1 text-xs font-bold text-green-600";
        } else {
            let diff = valorOrcamento - total;
            msgValidacao.innerHTML = `(Diferença: R$ ${formatCurrency(diff)})`;
            msgValidacao.className = "ml-1 text-xs font-bold text-orange-600";
        }

        if(resumoParcelas) {
            resumoParcelas.innerText = `${count} parcela(s). Total: R$ ${formatCurrency(total)}`;
        }
    }

    window.openFaturamentoModal = function() {
        modal.classList.remove('hidden');
        atualizarTotal();
    }

    window.closeFaturamentoModal = function(save = false) {
        modal.classList.add('hidden');
        if (!save) {
            if (statusSelect.value === 'Faturado' && previousStatus !== 'Faturado') {
                statusSelect.value = previousStatus;
            }
        } else {
            previousStatus = statusSelect.value;
        }
    }

    statusSelect.addEventListener('change', function() {
        if (this.value === 'Faturado') {
            window.openFaturamentoModal();
        } else {
            previousStatus = this.value;
        }
    });

    if(btnOpenModal) btnOpenModal.addEventListener('click', window.openFaturamentoModal);
    if(btnConfirm) btnConfirm.addEventListener('click', () => window.closeFaturamentoModal(true));

    btnAddParcela.addEventListener('click', function() {
        const row = `
            <tr>
                <td class="px-3 py-2">
                    <input type="text" name="parcelas[${parcelasIndex}][descricao]" required class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </td>
                <td class="px-3 py-2">
                    <input type="number" step="0.01" name="parcelas[${parcelasIndex}][valor]" required class="parcela-valor block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </td>
                <td class="px-3 py-2">
                    <input type="date" name="parcelas[${parcelasIndex}][data_vencimento]" class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </td>
                <td class="px-3 py-2">
                    <input type="text" name="parcelas[${parcelasIndex}][nf]" class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </td>
                <td class="px-3 py-2 text-center">
                    <button type="button" class="text-red-500 hover:text-red-700 btn-remove-parcela">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        containerParcelas.insertAdjacentHTML('beforeend', row);
        parcelasIndex++;
        atualizarTotal();
    });

    containerParcelas.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-parcela')) {
            e.target.closest('tr').remove();
            atualizarTotal();
        }
    });

    containerParcelas.addEventListener('input', function(e) {
        if (e.target.classList.contains('parcela-valor')) {
            atualizarTotal();
        }
    });

    atualizarTotal();
});