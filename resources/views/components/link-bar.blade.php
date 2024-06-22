{{-- @props(['links' => []]) --}}
<section {{ $attributes->merge(['class' => 'link-bar']) }}>
    @foreach ($links as $link)
        <x-link href="{{ $link['href'] }}" title="{{ $link['title'] }}" :active="$link['active']" icon="{{ $link['icon'] }}" />
    @endforeach
</section>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.querySelector('#sidebar') ?? document.querySelector('.sidebar');
        const toolbar = document.querySelector('.toolbar');
        const linkBar = document.querySelector('.link-bar');
        let siebarToggle, toggleToolbar;

        const genLink = (href, title, icon, classitem) => {
            const link = document.createElement('a');
            link.setAttribute('href', href);
            link.setAttribute('title', title);
            link.classList.add('link', classitem);
            link.innerHTML = `<span class="material-symbols-outlined">${icon}</span>`;
            link.innerHTML += `<span class="link-title">${title}</span>`;
            return link;
        };

        const toggleElement = (clicker, element) => {
            clicker.addEventListener('click', function(e) {
                e.preventDefault();
                element.classList.toggle('active');
            });
        }
        if (toolbar) {
            toggleToolbar = genLink('#', 'Toggle Toolbar', 'more_vert', 'toolbar-toggle');
            linkBar.prepend(toggleToolbar);
            toggleElement(toggleToolbar, toolbar);
        }

        if (sidebar) {
            siebarToggle = genLink('#', 'Toggle Sidebar', 'menu', 'sidebar-toggle');
            linkBar.prepend(siebarToggle);
            toggleElement(siebarToggle, sidebar);
        }

        if (linkBar) {
            if (window.innerWidth < 768) {
                linkBar.classList.add('vert');
            } else {
                linkBar.classList.remove('vert');
            }
        }
    });
</script>