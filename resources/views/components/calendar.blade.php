<div {{ $attributes->merge(['class' => 'calendar']) }}>
    <section class="calendar-header">
        <button id="prevYear">
            <span class="material-symbols-outlined icon">arrow_back</span>
        </button>
        <span id="year"></span>
        <button id="nextYear">
            <span class="material-symbols-outlined icon">arrow_forward</span>
        </button>
    </section>
    <section class="calendar-grid">
    </section>
</div>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    const calendarGrid = document.querySelector('.calendar-grid');
    const yearElement = document.getElementById('year');
    const prevYearButton = document.getElementById('prevYear');
    const nextYearButton = document.getElementById('nextYear');

    let currentYear = new Date().getFullYear();

    function validateInput(event) {
        const input = event.target;
        const value = parseInt(input.value, 10);
        if (value < 0) {
            input.value = 0;
        }
        if (value > 730) {
            input.value = 730;
        }
    }

    function renderCalendar() {
        calendarGrid.innerHTML = '';
        yearElement.textContent = currentYear;

        monthNames.forEach(month => {
            const monthDiv = document.createElement('div');
            monthDiv.classList.add('month', 'glass');
            monthDiv.innerHTML = `
                <div>${month}</div>
                <input 
                    type="number" 
                    name="${month}-hrs" 
                    placeholder="Hrs" 
                    max="730" 
                    min="0"
                >
            `;
            const input = monthDiv.querySelector('input');
            input.addEventListener('input', validateInput);
            calendarGrid.appendChild(monthDiv);
        });
    }

    prevYearButton.addEventListener('click', () => {
        currentYear--;
        renderCalendar();
    });

    nextYearButton.addEventListener('click', () => {
        currentYear++;
        renderCalendar();
    });

    renderCalendar();
});
</script>