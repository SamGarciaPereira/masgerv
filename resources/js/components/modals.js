window.openAnexoModal = function (id, nome) {
    const modal = document.getElementById("anexoModal");
    if (modal) {
        // Preenche os inputs hidden e o texto do modal
        const idInput = document.getElementById("modalModelId");
        const nameLabel = document.getElementById("modalModelName");

        if (idInput) idInput.value = id;
        if (nameLabel) nameLabel.innerText = nome;

        modal.classList.remove("hidden");
    }
};

window.closeAnexoModal = function () {
    const modal = document.getElementById("anexoModal");
    if (modal) {
        modal.classList.add("hidden");
    }
};
