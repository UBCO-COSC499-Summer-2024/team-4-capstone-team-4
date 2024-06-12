<x-app-layout>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <div class="">
            {{-- header component --}}
        <div class="flex m-5">
                {{-- sidebar component --}}
            <div class="bg-gray-200 rounded-lg p-10 min-w-[300px] min-h-screen">
                <x-sidebar :items="[]" />
            </div>
            {{-- future visualizations component --}}
            <div class="mx-2">
                <x-welcome />
            </div>
        </div>
    </div>
</x-app-layout>
