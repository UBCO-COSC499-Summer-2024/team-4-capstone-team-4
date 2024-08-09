<div>
    <button id="createNewTAButton" wire:click="openModal" class="ubc-blue hover:text-white focus:ring-1 focus:outline-none font-bold rounded-lg text-sm px-4 py-2 text-center me-1 mb-2">
        Create New TA
    </button>
    @if ($showModal)
        <div class="fixed z-[1004] inset-0 overflow-y-auto flex items-center justify-center modal-centered" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-custom-green p-4">
                    <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">Create New TA</h3>
                </div>
                <div class="bg-white p-6">
                    <form wire:submit.prevent="submit">
                        @csrf
                        <div id="taFieldsContainer">
                            @foreach ($tas as $index => $ta)
                                <div class="taFieldsBlock flex space-x-4 mb-4">
                                    <div class="flex-1">
                                        <label for="taName{{ $index }}" class="block text-sm font-medium text-gray-700">Name</label>
                                        <input type="text" id="taName{{ $index }}" wire:model.defer="tas.{{ $index }}.name" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm input-bordered" required>
                                    </div>
                                    <div class="flex-1">
                                        <label for="taRating{{ $index }}" class="block text-sm font-medium text-gray-700">Rating</label>
                                        <input type="number" id="taRating{{ $index }}" wire:model.defer="tas.{{ $index }}.rating" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm input-bordered" min="0" max="5" step="0.1" required>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" wire:click="addMore" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Add More
                        </button>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-custom-green text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Save
                            </button>
                            <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if ($showTAAddedModal)
        <div class="fixed z-[1004] inset-0 overflow-y-auto flex items-center justify-center modal-centered" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-custom-green p-4">
                    <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">TA Added</h3>
                </div>
                <div class="bg-white p-6">
                    <p class="text-sm text-gray-700">The TA has been added successfully. What would you like to do next?</p>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="closeTAAddedModal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-custom-green text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                    <button type="button" wire:click="openModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Add Another TA
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
