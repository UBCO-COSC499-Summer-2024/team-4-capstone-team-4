<x-app-layout>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <div class="">
            {{-- header component --}}
            <div class="flex justify-between">
                <x-header.brand />
                <div class="flex m-5">
                    <div class="sidebar-item m-1">
                        <span class="material-symbols-outlined">notifications</span>
                    </div>
                    <div class="sidebar-item m-1">
                        <span class="material-symbols-outlined">brightness_4</span>
                    </div>
                    <div class="sidebar-item m-1">
                        <span class="material-symbols-outlined">settings</span>
                    </div>
                    <div class="">
                        <!-- Settings Dropdown -->
                        <div class="">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                        </button>
                                    @else
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                {{ Auth::user()->name }}
        
                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    @endif
                                </x-slot>
            
                        
                                <x-slot name="content">
                                    <!-- Account Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Account') }}
                                    </div>
        
                                    <x-dropdown-link href="{{ route('profile.show') }}">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>
        
                                    <div class="border-t border-gray-200"></div>
        
                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf
        
                                        <x-dropdown-link href="{{ route('logout') }}"
                                                    @click.prevent="$root.submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>  
            </div>
        <div class="flex m-5">
                {{-- sidebar component --}}
            <div class="bg-gray-200 rounded-lg p-3 min-w-[300px] min-h-screen">
                <x-sidebar :items="[]" />
            </div>
            {{-- future visualizations component --}}
            <div class="mx-2">
                <x-welcome />
            </div>
        </div>
    </div>
</x-app-layout>
