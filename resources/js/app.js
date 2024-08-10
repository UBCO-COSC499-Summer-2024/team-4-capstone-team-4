import './bootstrap';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css'; // optional for styling

document.addEventListener('DOMContentLoaded', () => {
    tippy('[data-tippy-content]', {
        animation: 'scale',
        theme: 'material',
        allowHTML: true,
        interactive: true,
        arrow: false,
        // instant hide and show
        delay: [0, 0],
        placement: 'bottom',
    });
});

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
}

function handleClickOutside(event) {
    const sidebar = document.getElementById('sidebar');
    const headerBrand = document.getElementById('header-brand');
    const sidebarToggle = document.querySelector('.sidebar-toggle');

    if (event.target.closest('#sidebar') || event.target.closest('#header-brand') || (sidebarToggle && event.target.closest('.sidebar-toggle'))) {
        return;
    }

    sidebar.classList.remove('active');
}

document.getElementById('header-brand').addEventListener('click', toggleSidebar);

document.addEventListener('click', handleClickOutside);
