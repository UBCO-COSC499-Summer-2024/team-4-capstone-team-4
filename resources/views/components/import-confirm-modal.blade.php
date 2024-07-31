<div class="relative bg-white rounded-lg shadow-lg w-11/12 max-w-lg">
    <div class="relative p-2 bg-red-500 rounded-t-lg">
        <button class="absolute text-white text-4xl font-bold right-3 top-1">
            <span wire:click="closeConfirmModal" class="material-symbols-outlined">close</span>
        </button>
        <div class="flex justify-center my-6">
            <span class="material-symbols-outlined text-white text-5xl">warning</span>
        </div>
    </div>
    <div class="p-6 mt-2 mb-10 text-center">   
        <p class="text-2xl font-bold mb-6">The Following Courses already exist and will be updated if confirmed</p>
        @if($duplicateCourses)
            @foreach($duplicateCourses as $course)
                <p class="text-2xl">{{ $course->prefix }} {{$course->number}} {{ $course->section }} - {{ $course->year }}{{ $course->session }}{{ $course->term }}</p>
            @endforeach
        @endif
        <div class="flex justify-center mt-6">
            <button class="bg-white text-red-500 border border-red-500 py-2 px-4 mx-2 rounded-lg hover:bg-red-500 hover:text-white" 
                    wire:click="closeConfirmModal">
                Cancel
            </button>
            <button class="bg-white text-[#3D8B57] border border-[#3D8B57] py-2 px-4 mx-2 rounded-lg hover:bg-[#3D8B57] hover:text-white" 
                    wire:click="userConfirmDuplicate">
                Confirm Update
            </button>
            
        </div>
    </div>
</div>
