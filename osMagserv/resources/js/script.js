document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const sidebarTexts = document.querySelectorAll('.sidebar-text');
    const dropdownBtn = document.getElementById('dropdown-btn');
    const submenu = document.getElementById('submenu');
    const arrow = document.getElementById('arrow');
    const submenuLinks = submenu.querySelectorAll('a');

    submenuLinks.forEach(link => {
        link.classList.add('w-0', 'opacity-0');});

    // Estado inicial da sidebar (recolhida)
    let isExpanded = false;

    toggleBtn.addEventListener('click', function () {
        isExpanded = !isExpanded;

        if (isExpanded) {
            // EXPANDIR
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-72');
            mainContent.classList.remove('ml-20');
            mainContent.classList.add('ml-72');
            
            // Mostra os textos com um pequeno atraso para sincronizar com a transição da sidebar
            setTimeout(() => {
                sidebarTexts.forEach(text => {
                    text.classList.remove('w-0', 'opacity-0');
                    text.classList.add('w-full', 'opacity-100');
                });
            }, 100);

        } else {
            // RECOLHER
            sidebar.classList.add('w-20');
            sidebar.classList.remove('w-72');
            mainContent.classList.add('ml-20');
            mainContent.classList.remove('ml-72');
            
            sidebarTexts.forEach(text => {
                text.classList.add('w-0', 'opacity-0');
                text.classList.remove('w-full', 'opacity-100');
            });
            
            // Fecha o submenu se estiver aberto
            submenu.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    });

    dropdownBtn.addEventListener('click', function (e) {
    // Só permite abrir o dropdown se a sidebar estiver expandida
    if (!isExpanded) return;

    // Verifica se o submenu está visível para decidir a ação
    const isSubmenuVisible = !submenu.classList.contains('hidden');

    arrow.classList.toggle('rotate-180');

    if (isSubmenuVisible) {
        // FECHAR SUBMENU
        // Primeiro, anima os links para que desapareçam
        submenuLinks.forEach(link => {
            link.classList.add('w-0', 'opacity-0');
            link.classList.remove('w-full', 'opacity-100');
        });

        // Depois que a animação terminar, esconde o contêiner do submenu
        setTimeout(() => {
            submenu.classList.add('hidden');
        }, 300); // O tempo deve ser igual ou maior que a duração da transição no CSS

    } else {
        // ABRIR SUBMENU
        // Primeiro, torna o contêiner visível
        submenu.classList.remove('hidden');

        // Depois, anima os links para que apareçam
        setTimeout(() => {
             submenuLinks.forEach(link => {
                link.classList.remove('w-0', 'opacity-0');
                link.classList.add('w-full', 'opacity-100');
            });
        }, 10); // Pequeno atraso para garantir que o contêiner esteja visível antes da animação começar
    }
});
});
