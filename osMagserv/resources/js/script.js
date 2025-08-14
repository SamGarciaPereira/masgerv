document.addEventListener('DOMContentLoaded', function () {

    const sidebar = document.querySelector('.sidebar');
    const openBtn = document.querySelector('#open-sidebar-btn');
    const closeBtn = document.querySelector('#close-sidebar-btn');

    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
    }

    if (openBtn) {
        openBtn.addEventListener('click', toggleSidebar);
    }
    
    if (closeBtn) {
        closeBtn.addEventListener('click', toggleSidebar);
    }

    const dropdownBtn = document.querySelector('#dropdown-btn');
    const submenu = document.querySelector('#submenu');
    const arrow = document.querySelector('#arrow');

    function toggleDropdown() {
        submenu.classList.toggle('hidden');
        arrow.classList.toggle('rotate-0');
    }

    if (dropdownBtn) {
        dropdownBtn.addEventListener('click', toggleDropdown);
    }
    
});

