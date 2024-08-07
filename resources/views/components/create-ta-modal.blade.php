<div id="createTAModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-custom-green p-4">
                <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">Create New TA</h3>
            </div>
            <div class="bg-white p-6">
                <form id="createTAForm" method="POST" action="{{ route('createTA') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="taName" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="taName" name="name" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="taRating" class="block text-sm font-medium text-gray-700">Rating</label>
                        <input type="number" id="taRating" name="rating" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="0" max="5" step="0.1" required>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-custom-green text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm close-create-ta-modal-button">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Initial Confirmation Modal -->
<div id="initialConfirmationModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-custom-green p-4">
                <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">Confirmation</h3>
            </div>
            <div class="bg-white p-6">
                <p class="text-sm text-gray-700">Are you sure you want to add this TA?</p>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button id="confirmYesButton" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-custom-green text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Yes
                </button>
                <button id="confirmNoButton" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    No
                </button>
            </div>
        </div>
    </div>
</div>

<!-- TA Added Confirmation Modal -->
<div id="taAddedConfirmationModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-custom-green p-4">
                <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">TA Added</h3>
            </div>
            <div class="bg-white p-6">
                <p class="text-sm text-gray-700">The TA has been added successfully. What would you like to do next?</p>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button id="assignTABtn" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-custom-green text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Assign TA
                </button>
                <button id="addAnotherTABtn" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Add Another TA
                </button>
                <button id="closeModalBtn" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const createNewTAButton = document.getElementById('createNewTAButton');
    const createTAModal = document.getElementById('createTAModal');
    const closeCreateTAModalButtons = document.querySelectorAll('.close-create-ta-modal-button');
    const initialConfirmationModal = document.getElementById('initialConfirmationModal');
    const taAddedConfirmationModal = document.getElementById('taAddedConfirmationModal');
    const confirmYesButton = document.getElementById('confirmYesButton');
    const confirmNoButton = document.getElementById('confirmNoButton');
    const okButton = document.getElementById('okButton');
    const assignMoreButton = document.getElementById('assignMoreButton');
    const assignTABtn = document.getElementById('assignTABtn');
    const addAnotherTABtn = document.getElementById('addAnotherTABtn');
    const closeModalBtn = document.getElementById('closeModalBtn');

    function openCreateTAModal() {
        console.log("Opening create TA modal");
        createTAModal.classList.remove('hidden');
    }

    function closeCreateTAModal() {
        console.log("Closing create TA modal");
        createTAModal.classList.add('hidden');
    }

    function openInitialConfirmationModal() {
        console.log("Opening initial confirmation modal");
        initialConfirmationModal.classList.remove('hidden');
    }

    function closeInitialConfirmationModal() {
        console.log("Closing initial confirmation modal");
        initialConfirmationModal.classList.add('hidden');
    }

    function openTAAddedConfirmationModal() {
        console.log("Opening TA added confirmation modal");
        taAddedConfirmationModal.classList.remove('hidden');
    }

    function closeTAAddedConfirmationModal() {
        console.log("Closing TA added confirmation modal");
        taAddedConfirmationModal.classList.add('hidden');
    }

    if (createNewTAButton) {
        createNewTAButton.addEventListener('click', openCreateTAModal);
    }

    if (closeCreateTAModalButtons) {
        closeCreateTAModalButtons.forEach(button => {
            button.addEventListener('click', closeCreateTAModal);
        });
    }

    document.getElementById('createTAForm').addEventListener('submit', function(event) {
        event.preventDefault();
        openInitialConfirmationModal();
    });

    confirmYesButton.addEventListener('click', function() {
        const formData = new FormData(document.getElementById('createTAForm'));
        fetch(document.getElementById('createTAForm').action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Form submitted successfully:', data);
            closeInitialConfirmationModal();
            closeCreateTAModal();
            openTAAddedConfirmationModal();
        })
        .catch(error => console.error('Error submitting form:', error));
    });

    confirmNoButton.addEventListener('click', function() {
        closeInitialConfirmationModal();
    });

    okButton.addEventListener('click', function() {
        location.reload();
    });

    assignMoreButton.addEventListener('click', function() {
        closeTAAddedConfirmationModal();
        openCreateTAModal();
    });

    assignTABtn.addEventListener('click', function() {
        closeTAAddedConfirmationModal();
        document.getElementById('assignTAButton').click();
    });

    addAnotherTABtn.addEventListener('click', function() {
        closeTAAddedConfirmationModal();
        openCreateTAModal();
    });

    closeModalBtn.addEventListener('click', function() {
        closeTAAddedConfirmationModal();
    });
});

</script>