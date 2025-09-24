document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("sidebar-toggle");
    const sidebarTexts = document.querySelectorAll(".sidebar-text");
    const dropdownBtn = document.getElementById("dropdown-btn");
    const submenu = document.getElementById("submenu");
    const arrow = document.getElementById("arrow");
    const submenuLinks = submenu.querySelectorAll("a");
    const toggleButtons = document.querySelectorAll(".toggle-details-btn");

    submenuLinks.forEach((link) => {
        link.classList.add("w-0", "opacity-0");
    });

    let isExpanded = false;

    function setExpanded(state) {
        isExpanded = state;
        if (isExpanded) {
            // EXPANDIR
            sidebar.classList.remove("w-20");
            sidebar.classList.add("w-72");

            setTimeout(() => {
                sidebarTexts.forEach((text) => {
                    if (!text.closest("#submenu")) {
                        text.classList.remove("w-0", "opacity-0");
                        text.classList.add("w-full", "opacity-100");
                    }
                });
            }, 100);
        } else {
            // RECOLHER
            sidebar.classList.add("w-20");
            sidebar.classList.remove("w-72");

            sidebarTexts.forEach((text) => {
                text.classList.add("w-0", "opacity-0");
                text.classList.remove("w-full", "opacity-100");
            });

            submenu.classList.add("hidden");
            if (arrow) {
                arrow.classList.remove("rotate-180");
            }
        }
    }

    toggleBtn.addEventListener("click", () => setExpanded(!isExpanded));

    dropdownBtn.addEventListener("click", function (e) {
        if (!isExpanded) {
            setExpanded(true);
            return;
        }

        const isSubmenuVisible = !submenu.classList.contains("hidden");
        if (arrow) {
            arrow.classList.toggle("rotate-180");
        }

        if (isSubmenuVisible) {
            submenuLinks.forEach((link) => {
                link.classList.add("w-0", "opacity-0");
                link.classList.remove("w-full", "opacity-100");
            });
            setTimeout(() => {
                submenu.classList.add("hidden");
            }, 300);
        } else {
            submenu.classList.remove("hidden");
            setTimeout(() => {
                submenuLinks.forEach((link) => {
                    link.classList.remove("w-0", "opacity-0");
                    link.classList.add("w-full", "opacity-100");
                });
            }, 10);
        }
    });

    // --- LÓGICA PARA EXPANDIR/RECOLHER DETALHES DA TABELA ---
    toggleButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const targetId = this.dataset.targetId;
            const detailsRow = document.getElementById(`details-${targetId}`);
            const icon = this.querySelector("i");

            if (detailsRow) {
                detailsRow.classList.toggle("hidden");
                icon.classList.toggle("rotate-180"); // Gira o ícone da seta
            }
        });
    });
});
