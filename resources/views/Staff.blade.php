<x-app-layout>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-900">
            <x-staff-search/>
            <x-staff-dropdown/>
        </div>
        <x-staff-table>
            <x-staff-table-header/>
            <tbody>
                @foreach ($users as $user)
                    <x-staff-table-row name="{{ $user->firstname }} {{ $user->lastname}}" email="{{ $user->email}}" subarea="physics" completedHours="50" targetHours="80" rating="4.2" src="{{ $user->profile_photo_url}}" />
                @endforeach
            </tbody>
        </x-staff-table>
    </div>   
</x-app-layout>