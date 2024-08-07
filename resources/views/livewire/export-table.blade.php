<div>
    <div class="relative inline-block text-left">
        <button id="exportCSVButton" type="button" class="ubc-blue hover:text-white focus:ring-1 focus:outline-none font-bold rounded-lg text-sm px-5 py-2 text-center me-1 mb-2" aria-expanded="true" aria-haspopup="true">
            <span class="material-symbols-outlined">ios_share</span>
        </button>
        <div id="dropdownMenu" class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" role="menu" aria-orientation="vertical" aria-labelledby="exportCSVButton">
            <div class="py-1" role="none">
                <a href="#" wire:click.prevent="exportCSV" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem">Export as CSV</a>
            </div>
        </div>
    </div>
    
    <style>
        .tooltip {
            display: block;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        #exportCSVButton:hover .tooltip {
            opacity: 1;
        }
        #dropdownMenu {
            z-index: 1050;
            position: absolute;
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const exportCSVButton = document.getElementById('exportCSVButton');
            const dropdownMenu = document.getElementById('dropdownMenu');
    
            exportCSVButton.addEventListener('click', function (event) {
                event.preventDefault();
                dropdownMenu.classList.toggle('hidden');
            });
    
            document.addEventListener('click', function (event) {
                if (!exportCSVButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        });
    </script>
    
</div>
