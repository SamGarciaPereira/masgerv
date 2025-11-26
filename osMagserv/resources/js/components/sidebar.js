document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("sidebar-toggle");
    const sidebarTexts = document.querySelectorAll(".sidebar-text");

    // Elementos do Header
    const sidebarHeader = document.getElementById("sidebar-header");
    const sidebarBranding = document.getElementById("sidebar-branding");

    // Dropdowns
    const dropdownBtnManutencao = document.getElementById("dropdown-btn-manutencao");
    const submenuManutencao = document.getElementById("submenu-manutencao");
    const arrowManutencao = document.getElementById("arrow-manutencao");

    const dropdownBtnFinanceiro = document.getElementById("dropdown-btn-financeiro");
    const submenuFinanceiro = document.getElementById("submenu-financeiro");
    const arrowFinanceiro = document.getElementById("arrow-financeiro");

    // Ajuste inicial dos links dos submenus
    const allSubLinks = document.querySelectorAll("#submenu-manutencao a, #submenu-financeiro a");
    allSubLinks.forEach((link) => {
        link.style.transition = "all 0.3s ease-in-out";
        if (sidebar && sidebar.classList.contains("w-20")) {
            const textSpan = link.querySelector(".sidebar-text");
            if (textSpan) {
                textSpan.classList.add("w-0", "opacity-0");
                textSpan.classList.remove("w-full", "opacity-100");
            }
        }
    });

    let isExpanded = false;

    // Função Principal de Expandir/Recolher Sidebar
    function setExpanded(state) {
        if (!sidebar) return;
        
        isExpanded = state;
        if (isExpanded) {
            sidebar.classList.remove("w-20");
            sidebar.classList.add("w-72");

            if (sidebarHeader) {
                sidebarHeader.classList.remove("justify-center");
                sidebarHeader.classList.add("justify-between");
            }
            if (sidebarBranding) {
                sidebarBranding.classList.remove("hidden");
                setTimeout(() => {
                    sidebarBranding.classList.remove("w-0", "opacity-0");
                    sidebarBranding.classList.add("w-auto", "opacity-100");
                }, 50);
            }

            setTimeout(() => {
                sidebarTexts.forEach((text) => {
                    if (!text.closest("#submenu-manutencao, #submenu-financeiro")) {
                        text.classList.remove("w-0", "opacity-0");
                        text.classList.add("w-full", "opacity-100");
                    }
                });
            }, 100);
        } else {
            // --- RECOLHER ---
            sidebar.classList.add("w-20");
            sidebar.classList.remove("w-72");

            if (sidebarBranding) {
                sidebarBranding.classList.remove("w-auto", "opacity-100");
                sidebarBranding.classList.add("w-0", "opacity-0");
                setTimeout(() => {
                    sidebarBranding.classList.add("hidden");
                    if (sidebarHeader) {
                        sidebarHeader.classList.remove("justify-between");
                        sidebarHeader.classList.add("justify-center");
                    }
                }, 200);
            } else if (sidebarHeader) {
                sidebarHeader.classList.remove("justify-between");
                sidebarHeader.classList.add("justify-center");
            }

            sidebarTexts.forEach((text) => {
                text.classList.add("w-0", "opacity-0");
                text.classList.remove("w-full", "opacity-100");
            });

            closeDropdown(submenuFinanceiro, arrowFinanceiro);
            closeDropdown(submenuManutencao, arrowManutencao);
        }
    }

    if (toggleBtn) {
        toggleBtn.addEventListener("click", () => setExpanded(!isExpanded));
    }

    function closeDropdown(submenu, arrow) {
        if (!submenu) return;
        if (!submenu.classList.contains("hidden")) {
            submenu.classList.add("hidden");
            if (arrow) arrow.classList.remove("rotate-180");
        }
    }

    function toggleDropdown(submenu, arrow) {
        if (!submenu) return;

        const isHidden = submenu.classList.contains("hidden");
        const linksTexts = submenu.querySelectorAll(".sidebar-text");

        if (isHidden) {
            // --- ABRIR ---
            submenu.classList.remove("hidden");
            if (arrow) arrow.classList.add("rotate-180");

            // Delay para mostrar os textos suavemente
            setTimeout(() => {
                linksTexts.forEach((text) => {
                    text.classList.remove("w-0", "opacity-0");
                    text.classList.add("w-full", "opacity-100");
                });
            }, 100);
        } else {
            // --- FECHAR ---
            linksTexts.forEach((text) => {
                text.classList.add("w-0", "opacity-0");
                text.classList.remove("w-full", "opacity-100");
            });

            if (arrow) arrow.classList.remove("rotate-180");

            setTimeout(() => {
                submenu.classList.add("hidden");
            }, 300);
        }
    }

    // Event Listeners dos Dropdowns
    if (dropdownBtnFinanceiro) {
        dropdownBtnFinanceiro.addEventListener("click", () => {
            if (!isExpanded) {
                setExpanded(true);
                setTimeout(() => toggleDropdown(submenuFinanceiro, arrowFinanceiro), 300);
            } else {
                toggleDropdown(submenuFinanceiro, arrowFinanceiro);
            }
        });
    }

    if (dropdownBtnManutencao) {
        dropdownBtnManutencao.addEventListener("click", () => {
            if (!isExpanded) {
                setExpanded(true);
                setTimeout(() => toggleDropdown(submenuManutencao, arrowManutencao), 300);
            } else {
                toggleDropdown(submenuManutencao, arrowManutencao);
            }
        });
    }
});