<div x-data="{ showAssignInstructorModal: @entangle('showAssignInstructorModal'), show: @entangle('showAssignInstructorModal') }">
    <x-dialog-modal id="assignInstructorModal" wire:model="showAssignInstructorModal">
        <x-slot name="title">
            Assign Instructor
        </x-slot>

        <x-slot name="content">
            {{-- all instructors dropdown with search as well as service role dropdown with search --}}
            <div class="flex flex-col gap-4 min-h-64">
                <div class="flex justify-between w-full gap-4">
                    <div class="assign-ins-dropdown">
                        <x-dropdown align="left" width="64">
                            <x-slot name="trigger">
                                <button class="content-title-btn">
                                    <span class="btn-title">
                                        Instructors
                                    </span>
                                    <span class="material-symbols-outlined icon">
                                        people
                                    </span>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <input type="text" class="dropdown-search" placeholder="Search..." wire:model.live="instructorSearchQuery" @click.stop />
                                @foreach ($instructors as $instructor)
                                    <x-dropdown-link
                                        wire:click="selectInstructor({{ $instructor->id }}, '{{ $instructor->getName() }}')"
                                        class="{{ in_array($instructor->id, $selectedInstructors) ? 'active' : '' }}">
                                        {{ $instructor->getName() }}
                                    </x-dropdown-link>
                                @endforeach
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <div class="assign-ins-dropdown">
                        <x-dropdown align="right" width="64">
                            <x-slot name="trigger">
                                <button class="content-title-btn">
                                    <span class="btn-title">
                                        Service Roles
                                    </span>
                                    <span class="material-symbols-outlined icon">
                                        work
                                    </span>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <input type="text" class="dropdown-search" placeholder="Search..." wire:model.live="serviceRoleSearchQuery" @click.stop />
                                @foreach ($allServiceRoles as $serviceRole)
                                    <x-dropdown-link
                                        wire:click="selectServiceRole({{ $serviceRole->id }}, '{{ $serviceRole->name }}')"
                                        class="{{ in_array($serviceRole->id, $selectedServiceRoles) ? 'active' : '' }}">
                                        {{ $serviceRole->name }}
                                    </x-dropdown-link>
                                @endforeach
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
                <div class="flex justify-between w-full gap-4">
                    <div class="flex flex-col gap-4">
                        <span class="font-bold">Selected Instructors</span>
                        @foreach ($selectedInstructors as $instructorId => $instructorName)
                            <div class="flex gap-4">
                                <span>{{ $instructorName }}</span>
                                <button class="content-title-btn" wire:click="removeInstructor({{ $instructorId }})">
                                    <span class="material-symbols-outlined icon">close</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex flex-col gap-4">
                        <span class="font-bold">Selected Service Roles</span>
                        @foreach ($selectedServiceRoles as $serviceRoleId => $serviceRoleName)
                            <div class="flex gap-4">
                                <span>{{ $serviceRoleName }}</span>
                                <button class="content-title-btn" wire:click="removeServiceRole({{ $serviceRoleId }})">
                                    <span class="material-symbols-outlined icon">close</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('showAssignInstructorModal')" class="mr-2" wire:loading.attr="disabled" wire:key="cancel-assign-instructor">
                {{ __('Close') }}
            </x-secondary-button>
            <x-button wire:click="assign" wire:loading.attr="disabled" wire:key="assign-instructor-save">
                {{ __('Assign') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
