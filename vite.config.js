import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/var.css',
                'resources/css/app.css',
                'resources/css/scrollbar.css',
                'resources/css/form.css',
                'resources/css/tabs.css',
                'resources/css/toolbar.css',
                'resources/css/switch.css',
                'resources/css/toastify.css',
                'resources/css/course-details.css',
                'resources/css/calendar.css',
                'resources/css/card.css',
                'resources/css/dropdown.css',
                'resources/css/import.css',
                'resources/css/svcr.css',
                'resources/css/reports.css',
                'resources/js/app.js',
                'resources/js/events.js',
                'resources/js/sidebar.js',
                'resources/js/tabs.js',
                'resources/js/dropdown.js',
                'resources/js/staff.js',
                'resources/js/sortTable.js',
                'resources/js/buttons.js',
                'resources/js/coursedetails-search.js',
                'resources/js/exportReport.js',
                'resources/js/darkmode.js',
            ],
            refresh: true,
        }),
    ],
});
