<section class="faq">
    <h1 class="faq-title">FAQ</h1>
    <p>Here are some frequently asked questions:</p>
    <div class="accordion">
        @foreach ($faqs as $index => $faq)
            <div class="accordion-item">
                <button class="accordion-header glass" type="button" aria-expanded="false" aria-controls="accordion-content-{{ $index }}">
                    {{ $faq['question'] }}

                    <span class="material-symbols-outlined icon">keyboard_arrow_down</span>
                </button>
                <div id="accordion-content-{{ $index }}" class="accordion-content glass" hidden>
                    <p>{{ $faq['answer'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const headers = document.querySelectorAll('.accordion-header');

        headers.forEach(header => {
            header.addEventListener('click', () => {
                const content = document.getElementById(header.getAttribute('aria-controls'));
                const icon = header.querySelector('.icon');

                if (content.hidden) {
                    content.hidden = false;
                    header.setAttribute('aria-expanded', 'true');
                    // scroll to
                    content.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    icon.textContent = 'keyboard_arrow_up';
                } else {
                    content.hidden = true;
                    header.setAttribute('aria-expanded', 'false');
                    icon.textContent = 'keyboard_arrow_down';
                }
            });
        });
    });
</script>
