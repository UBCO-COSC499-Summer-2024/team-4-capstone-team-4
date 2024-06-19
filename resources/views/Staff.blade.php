<x-app-layout>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="flex items-center justify-between flex-wrap md:flex-nowrap space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-900">
            <x-staff-search />
            <div class="flex items-center space-x-4">
                <x-staff-filter />
                <x-staff-dropdown />
            </div>
        </div>              
        <x-staff-table>
            <x-staff-table-header :sortField="$sortField" :sortDirection="$sortDirection" :query="$query" :areas="$areas" />
            <tbody>
                @if(isset($users))
                    @if($users->isEmpty())
                        <p>No users found.</p>
                    @else
                        @foreach ($users as $user)
                            @php
                                $areas = [];
                               /*  foreach ($user->roles as $role) {
                                    if ($role->area) {
                                        $areas[] = $role->area->name;
                                    }
                                } */
                            @endphp
                            <x-staff-table-row 
                                name="{{ $user->firstname }} {{ $user->lastname }}" 
                                email="{{ $user->email }}" 
                                subarea="{{ empty($areas) ? '-' : implode(', ', $areas) }}" 
                                completedHours="50" 
                                targetHours="{{ App\Model\InstructorPeformance::where('instructor_id', $user->id)->get()->target_hours}}" 
                                rating="4.2" 
                                src="{{ $user->profile_photo_url }}" 
                            />
                        @endforeach
                    @endif
                @endif
            </tbody>
        </x-staff-table>
    </div>   
</x-app-layout>