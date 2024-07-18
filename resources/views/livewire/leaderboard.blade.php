<div class="relative sm:rounded-lg">
    <div class="sticky top-0 z-10 flex items-center justify-between h-20 bg-white dark:bg-gray-900">
        <div class="flex justify-between items-center w-full px-4">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('LEADERBOARD') }}</h1>
            <div class="ml-auto flex items-center">
                <x-leaderboard-filter />
            </div>
        </div>
    </div>
    <form wire:submit.prevent="submit">
        @csrf
        <x-leaderboard-table>
            <x-leaderboard-header />
            <tbody>
                @if(isset($users))
                    @foreach ($users as $user)
                        @php
                            $area_names = [];
                            $instructor = App\Models\UserRole::find($user->instructor_id);
                            $performance = App\Models\InstructorPerformance::where('instructor_id', $user->instructor_id)
                                                                            ->where('year', date('Y'))
                                                                            ->first();
                        @endphp
                        <x-leaderboard-row
                            rank="{{ $loop->iteration }}"
                            rankString="{{ $this->addOrdinalSuffix($loop->iteration) }}"
                            count="{{ count($users) }}"
                            instructorId="{{ $user->instructor_id }}"
                            src="{{ $user->profile_photo_url }}"
                            fullname="{{ $user->firstname }} {{ $user->lastname }}"
                            email="{{ $user->email }}"
                            score="{{ $performance->score ?? '-' }}"
                            badge="{{ $user->profile_photo_url }}"
                        />
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No users</td>
                    </tr>
                @endif
            </tbody>
        </x-leaderboard-table>
    </form>
</div>

