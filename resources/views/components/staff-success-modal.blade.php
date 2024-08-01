<div class="relative bg-white rounded-lg shadow-lg w-11/12 max-w-lg">
    <div class="relative p-2 bg-[#3D8B57] rounded-t-lg">
        <button class="absolute text-white text-4xl font-bold right-3 top-1">
            <span wire:click="closeSuccessModal" class="material-symbols-outlined">close</span>
        </button>
        <div class="flex justify-center my-6">
            <span class="material-symbols-outlined text-white text-5xl">check_circle</span>
        </div>
    </div>
    <div class="p-6 mt-2 mb-10 text-center">   
        <p class="text-lg font-light mb-6">Successfully Saved!</p>
        <div class="flex justify-center mt-6">
            <button class="bg-white text-[#3D8B57] border border-[#3D8B57] py-2 px-4 mx-2 rounded-lg hover:bg-[#3D8B57] hover:text-white" 
                    onclick="window.location.href = '{{ route('dashboard') }}'">
            Go to Dashboard
            </button>
            <button class="bg-white text-[#3D8B57] border border-[#3D8B57] py-2 px-4 mx-2 rounded-lg hover:bg-[#3D8B57] hover:text-white" 
                    wire:click="closeSuccessModal">
            Go to Staff
            </button>
        </div>
    </div>
</div>