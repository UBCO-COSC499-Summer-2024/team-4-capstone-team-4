document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const titles = sidebar.querySelectorAll('.sidebar-link-title');

    // initial is mouseleave
    sidebar.style.width = 'auto';
    // sidebar.style.transition = 'width 0.5s ease-in-out';
    titles.forEach(title => {
        title.style.opacity = 0;
        title.style.display = 'none';
        // transition
    });

    sidebar.addEventListener('mouseenter', function () {
        sidebar.style.width = '300px';
        setTimeout(() => {
            titles.forEach(title => {
                title.style.display = 'inline';
                title.style.opacity = 1;
            });
        }, 200); // Delay showing titles until the sidebar width has started to expand
    });

    sidebar.addEventListener('mouseleave', function () {
        sidebar.style.width = '60px';
        titles.forEach(title => {
            title.style.opacity = 0;
            setTimeout(() => {
                title.style.display = 'none';
            }, 200); // Delay hiding titles until the opacity transition has finished
        });
    });
});
