document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("sidebar-toggle");
    const sidebarTexts = document.querySelectorAll(".sidebar-text");
    const dropdownBtn = document.getElementById("dropdown-btn");
    const submenu = document.getElementById("submenu");
    const arrow = document.getElementById("arrow");
    const submenuLinks = submenu ? submenu.querySelectorAll("a") : [];
    const toggleButtons = document.querySelectorAll(".toggle-details-btn");

    try {
        submenuLinks.forEach((link) => {
            link.classList.add("w-0", "opacity-0");
        });
    } catch (e) {
        console.debug("submenu links init skipped:", e);
    }

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

    if (toggleBtn) {
        toggleBtn.addEventListener("click", () => setExpanded(!isExpanded));
    } else {
        console.debug("sidebar-toggle not found");
    }

    if (dropdownBtn) {
        dropdownBtn.addEventListener("click", function (e) {
            try {
                if (!isExpanded) {
                    setExpanded(true);
                    return;
                }

                const isSubmenuVisible = submenu
                    ? !submenu.classList.contains("hidden")
                    : false;
                if (arrow) {
                    arrow.classList.toggle("rotate-180");
                }

                if (isSubmenuVisible) {
                    submenuLinks.forEach((link) => {
                        link.classList.add("w-0", "opacity-0");
                        link.classList.remove("w-full", "opacity-100");
                    });
                    setTimeout(() => {
                        if (submenu) submenu.classList.add("hidden");
                    }, 300);
                } else {
                    if (submenu) submenu.classList.remove("hidden");
                    setTimeout(() => {
                        submenuLinks.forEach((link) => {
                            link.classList.remove("w-0", "opacity-0");
                            link.classList.add("w-full", "opacity-100");
                        });
                    }, 10);
                }
            } catch (err) {
                console.error("dropdown click error", err);
            }
        });
    } else {
        console.debug("dropdown-btn not found");
    }

    // --- LÓGICA PARA EXPANDIR/RECOLHER DETALHES DA TABELA ---
    if (toggleButtons && toggleButtons.length) {
        toggleButtons.forEach((button) => {
            button.addEventListener("click", function () {
                try {
                    const targetId = this.dataset.targetId;
                    const detailsRow = document.getElementById(
                        `details-${targetId}`
                    );
                    const icon = this.querySelector("i");

                    if (detailsRow) {
                        detailsRow.classList.toggle("hidden");
                        if (icon) icon.classList.toggle("rotate-180"); // Gira o ícone da seta
                    }
                } catch (err) {
                    console.error("toggle details error", err);
                }
            });
        });
    } else {
        console.debug("no toggle-details-btn elements found");
    }
});
