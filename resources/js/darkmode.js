Livewire.on('toggle-dark-mode', () => {
    const body = document.body;
    body.classList.toggle('dark');
});

document.addEventListener('DOMContentLoaded', () => {
    // Initial load settings
    loadUserSettings();

    // Listen for storage changes
    window.addEventListener('storage', (event) => {
        if (event.key === 'userSettings') {
            loadUserSettings();
        }
    });
});

function loadUserSettings() {
    const settings = JSON.parse(localStorage.getItem('userSettings'));
    if (settings) {
        // Apply settings to the current tab
        applySettings(settings);
    }
}

function applySettings(settings) {
    if (settings.locale) {
        // Set locale (this might need a page reload to fully apply)
        document.documentElement.lang = settings.locale;
    }
    if (settings.theme) {
        // Set theme (assuming you have a way to apply themes)
        const body = document.body;
        body.classList.toggle('dark', settings.theme === 'dark');
    }
}
