document.addEventListener("DOMContentLoaded", function () {
    const toggleButtons = document.querySelectorAll(".toggle-details-btn");

    if (toggleButtons && toggleButtons.length) {
        toggleButtons.forEach((button) => {
            button.addEventListener("click", function () {
                try {
                    // Pega o ID alvo
                    const targetId = this.dataset.targetId;

                    // Procura a linha oculta
                    const detailsRow = document.getElementById(
                        `details-${targetId}`
                    );

                    // Procura o ícone dentro do botão clicado
                    const icon = this.querySelector("i");

                    if (detailsRow) {
                        detailsRow.classList.toggle("hidden");
                        // Gira a seta se ela existir
                        if (icon) icon.classList.toggle("rotate-180");
                    }
                } catch (err) {
                    console.error("Erro ao alternar detalhes da tabela:", err);
                }
            });
        });
    }
});
