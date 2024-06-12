<x-app-layout>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <div class="">
        <div class="">
                {{-- header component --}}

                {{-- sidebar component --}}
                <div class="bg-gray-200 min-w-80 rounded-lg p-10 max-w-[300px]">
                    <x-sidebar :items="[]" />
                </div>
                {{-- future visualizations component --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <x-welcome />
                </div>
        </div>
    </div>
</x-app-layout>
