<div id="successModal" class="hidden fixed inset-0 z-50 overflow-auto bg-smoke-light flex">
    <div class="relative p-8 bg-white w-full max-w-md m-auto flex-col flex rounded-lg">
        <span class="material-symbols-outlined text-[#3B784F]">
            check_circle
        </span>
        <h2 class="text-2xl font-bold">Successfully Saved!</h2>
        <div class="flex justify-center mt-4">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
            <button onclick="closeModal()" class="btn btn-secondary ml-2">Insert More</button>
        </div>
    </div>
</div>