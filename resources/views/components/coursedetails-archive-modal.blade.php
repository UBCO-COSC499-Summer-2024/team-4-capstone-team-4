<!-- Confirmation Modal -->
<div id="confirmationModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-red-500 p-4">
                <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">Confirmation</h3>
            </div>
            <div class="bg-white p-6">
                <p class="text-sm text-gray-700">Are you sure you want to archive the selected courses?</p>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button id="confirmYesButton" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-500 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Yes
                </button>
                <button id="confirmNoButton" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm">
                    No
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const confirmationModal = document.getElementById('confirmationModal');
        const confirmYesButton = document.getElementById('confirmYesButton');
        const confirmNoButton = document.getElementById('confirmNoButton');
        const deleteButton = document.querySelector('#deleteButton');

        if (deleteButton) {
            deleteButton.addEventListener('click', function () {
                confirmationModal.classList.remove('hidden');
            });
        }

        confirmYesButton.addEventListener('click', function () {
            Livewire.emit('confirmArchiveCourses'); // Use Livewire.emit to trigger the event
            confirmationModal.classList.add('hidden');
        });

        confirmNoButton.addEventListener('click', function () {
            confirmationModal.classList.add('hidden');
        });
    });
</script>

<style>
#confirmationModal, #archivedSummaryModal {
    z-index: 1000; /* Ensure this value is higher than any other z-index on the page */
    position: fixed; /* Ensure the modals are positioned correctly */
    top: 0; /* Ensure the modals cover the entire screen */
    left: 0;
    width: 100%;
    height: 100%;
    overflow-y: auto; /* Ensure scrolling if content is too long */
    background-color: rgba(0, 0, 0, 0.5); /* To add a semi-transparent background */
}

#confirmationModal .fixed, #archivedSummaryModal .fixed {
    z-index: 1001; /* Ensure the inner content is also at a high z-index */
    position: relative;
}
</style>