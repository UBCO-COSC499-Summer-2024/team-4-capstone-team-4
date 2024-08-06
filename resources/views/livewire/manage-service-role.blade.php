@php
    $exports = [
        'CSV' => 'csv',
        'Excel' => 'xlsx',
        // 'PDF' => 'pdf',
        // 'Text' => 'text',
        // 'Print' => 'print'
    ];
    $user = Auth::user();
@endphp
@vite('resources/css/manage-service-role.css')
<div class="content"
    x-data="{ isEditing: @entangle('isEditing'), showInstructorModal: @entangle('showInstructorModal') }"
>
    <h1 class="nos content-title">
        <span class="content-title-text">
            @php

                $nextId = \App\Models\ServiceRole::where('id', '>', $serviceRole->id)->min('id') ?? \App\Models\ServiceRole::min('id');
                $prevId = \App\Models\ServiceRole::where('id', '<', $serviceRole->id)->max('id') ?? \App\Models\ServiceRole::max('id');

                $mlinks = [
                    ['href' => route('svcroles.manage.id', ['id' => $prevId]), 'title' => __('Previous Service Role'), 'icon' => 'chevron_left', 'active' => false],
                    ['href' => route('svcroles.manage.id', ['id' => $nextId]), 'title' => __('Next Service Role'), 'icon' => 'chevron_right', 'active' => false],
                ];
            @endphp
            @if ($serviceRole)
                <div class="flex items-center justify-between gap-2">
                    {{ $serviceRole->name }}
                    <div class="flex items-center justify-between arrow-dir">
                        <x-link href="{{ $mlinks[0]['href'] }}" icon="{{ $mlinks[0]['icon'] }}" />
                        <x-link href="{{ $mlinks[1]['href'] }}" icon="{{ $mlinks[1]['icon'] }}" />
                    </div>
                </div>
            @else
                {{ __('No Service Role Selected') }}
            @endif
        </span>

        @if(!$user->hasOnlyRole('instructor'))
            <div class="flex right content-title-btn-holder">
                {{-- preview --}}
                <button class="content-title-btn" x-on:click="window.location.href='{{ route('exports.pdf.preview', [ 'id' => $serviceRole->id ]) }}'" wire:loading.attr="disabled">
                    <span class="material-symbols-outlined icon">preview</span>
                    <span>Preview</span>
                </button>
                @if(!$serviceRole->archived)
                    <button class="content-title-btn" x-on:click="isEditing = !isEditing" wire:loading.attr="disabled" x-show="!isEditing" x-cloak>
                        <span class="material-symbols-outlined icon">
                            edit
                        </span>
                        <span>Edit</span>
                    </button>
                @endif
                <button class="content-title-btn" x-on:click="isEditing = false" wire:loading.attr="disabled" x-show="isEditing" x-cloak>
                    <span class="material-symbols-outlined icon">close</span>
                    <span>Cancel</span>
                </button>

                @if (auth()->user()->hasRoles(['admin']))
                    <button class="content-title-btn" x-on:click="$dispatch('confirm-manage-delete', { 'id': {{ $serviceRole->id }} })" wire:loading.attr="disabled">
                        <span class="material-symbols-outlined icon">delete</span>
                        <span>Delete</span>
                    </button>
                @endif
                <button class="content-title-btn" x-on:click="$dispatch('confirm-manage-archive', { 'id': {{ $serviceRole->id }} })" wire:loading.attr="disabled">
                        @if ($serviceRole->archived)
                            <span class="material-symbols-outlined icon">
                                unarchive
                            </span>
                            <span>
                                Unarchive
                            </span>
                        @else
                            <span class="material-symbols-outlined icon">
                                archive
                            </span>
                            <span>
                                Archive
                            </span>
                        @endif
                </button>
                {{-- export --}}
                {{-- <livewire:dropdown-element
                    title="Export"
                    id="exportDropdown"
                    pre-icon="file_download"
                    name="export"
                    :values="$exports"
                /> --}}
                    {{-- <select id="exportDropdown" title="Export" class="form-select">
                        <option value="">Export</option>
                        @foreach ($exports as $fname => $format)
                            <option value="{{ $format }}">{{ $fname }}</option>
                        @endforeach
                    </select> --}}
                <x-dropdown :align="'right'" :width='48'>
                    <x-slot name="trigger">
                        <button class="flex items-center content-title-btn">
                            <span class="material-symbols-outlined icon">file_download</span>
                            <span>Export</span>
                            <span class="material-symbols-outlined icon">arrow_drop_down</span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @foreach ($exports as $fname => $format)
                            <button class="flex items-center justify-start w-full px-4 py-2 hover:bg-gray-100 hover:text-gray-900"

                            {{-- x-on:click="$dispatch('export-role', {
                                'format': '{{$format}}'
                            })" --}}
                            x-on:click="window.location.href='{{ route('svcroles.export.id', ['eid' => $serviceRole->id, 'eformat' => $format]) }}'"
                            role="menuitem">
                                {{ $fname }}
                            </button>
                        @endforeach
                    </x-slot>
                </x-dropdown>
            </div>
        @endif
    </h1>

    {{-- <div class="horizontal groupd">
        <div class="form-group">
            <p>Service Role</p>
            <p>Name: {{ $serviceRole->name }}</p>
            <p>Description: {{ $serviceRole->description }}</p>
            <p>Monthly Hours: {{ $serviceRole->monthly_hours }}</p>
            <p>Year: {{ $year }}</p>
            <p>Area: {{ $serviceRole->area_id }}</p>
        </div>
    </div> --}}

    <div class="svcrole-item" :class="{ 'bg-default': !$isEditing, 'bg-editing': $isEditing }">
        <section id="about-role" class="svcr-item">
            <form id="service-role-update-form" class="form svcr-item-form" wire:key="service-role-update-form">
                <div class="horizontal grouped w-fit">
                    <div class="form-group svcrole-self">
                        <div class="form-item">
                            <label class="form-item" for="name">Name</label>
                            <div class="grouped">
                                <input class="form-input" type="text" id="name" wire:model="name"
                                    placeholder="ex. Student Advisor"
                                    x-bind:disabled="!isEditing" value="{{ $name }}">
                                @error('name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="grouped">
                            <label class="form-item" for="description">Description</label>
                            <div class="grouped">
                                <textarea class="form-input" id="description" wire:model="description"
                                    placeholder="Brief Description..." x-bind:disabled="!isEditing" >{{ $description }}</textarea>
                                @error('description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-item" for="room">Room</label>
                            <div class="grouped">
                                <input type="text" class="form-input text-end" id="room"
                                    placeholder="ex. LIB 123 B"
                                    x-show="!isEditing"
                                    x-cloak
                                    x-bind:disabled="true" value="{{ trim($roomB . ' ' . $roomN . ' ' . $roomS) ?? 'Not Assgined' }}">
                                <div x-show="isEditing" x-cloak class="flex items-center justify-start gap-2">
                                    <input type="text" class="form-input !min-w-8 !max-w-16"
                                        id="roomB"
                                        wire:model.live="roomB"
                                        placeholder="LIB"
                                        value="{{ $roomB }}"
                                        x-cloak>
                                    <input type="text" class="form-input !min-w-8 !max-w-16"
                                        id="roomN"
                                        wire:model.live="roomN"
                                        placeholder="123"
                                        value="{{ $roomN }}"
                                        x-cloak>
                                    <input type="text" class="form-input !min-w-8 !max-w-12"
                                        id="roomS"
                                        wire:model.live="roomS"
                                        placeholder="B"
                                        value="{{ $roomS }}"
                                        x-cloak>
                                </div>
                                @error('roomB') <span class="error">{{ $message }}</span> @enderror
                                @error('roomN') <span class="error">{{ $message }}</span> @enderror
                                @error('roomS') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-item" for="year">Year</label>
                            <div class="grouped">
                                <input class="form-input" type="number" id="year" wire:model="year"
                                    placeholder="ex. {{ date('Y') }}"
                                    x-bind:disabled="!isEditing" value="{{ $year }}">
                                @error('year') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-item" for="area_id">Area</label>
                            <div class="grouped">
                                <select class="form-select" id="area_id" wire:model="area_id" x-bind:disabled="!isEditing" >
                                    <option value="">Select Area</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" @if ($area_id == $area->id) selected @endif>{{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @error('area_id') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-item" x-show="isEditing" x-cloak>
                            <button type="submit" class="form-input" wire:loading.attr="disabled" id="save-service-role" value="Save">
                                <span class="material-symbols-outlined icon">save</span>
                                <span>Save</span>
                            </button>
                             {{-- <input type="submit" class="form-input" wire:loading.attr="disabled" id="save-service-role" value="Save"> --}}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class='p-0 calendar form-item'>
                            <div class="p-0 grouped">
                                <label class="form-item" for="monthHours">Monthly Hours</label>
                                <section class="calendar-header">
                                    <button type="button"
                                        x-on:click="$dispatch('dec-year')">
                                        <span class="material-symbols-outlined icon">arrow_back</span>
                                    </button>
                                    <span id="year">{{ $year }}</span>
                                    <button type="button"
                                        x-on:click="$dispatch('inc-year')">
                                        <span class="material-symbols-outlined icon">arrow_forward</span>
                                    </button>
                                </section>
                                <section class="calendar-grid">
                                    @foreach ($monthly_hours as $month => $hours)
                                        <div class="month glass monthlyHour">
                                            <div>{{ $month }}</div>
                                            <input type="number" id="monthly_hours_{{ $month }}" wire:model="monthly_hours.{{ $month }}" placeholder="Hrs" max="200" min="0" x-bind:disabled="!isEditing" value="{{ $hours }}">
                                        </div>
                                    @endforeach
                                </section>
                                @error('monthly_hours') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>

        <section id="instructors">
            <div class="grouped horizontal">
                <div class="svcr-instructor-list form-group">
                    <h2 class="nos form-item content-title">
                        <span class="content-title-text">{{ __('Instructors') }}</span>
                        @if (!$user->hasOnlyRole('instructor'))
                            <div class="right ustify-end rflex">
                                <button class="btn form-input" x-on:click="showInstructorModal = true" wire:loading.attr="disabled">
                                    <span class="material-symbols-outlined icon">person_add</span>
                                    <span>Assign Instructor</span>
                                </button>
                            </div>
                        @endif
                    </h2>
                    <table class="table svcr-table" id="svcr-table">
                        <thead>
                            <tr class="svcr-list-header">
                                <th class="svcr-list-header-item">
                                    @if(!$user->hasOnlyRole('instructor'))
                                        <input type="checkbox" class="svcr-list-item-select" id="svcr-select-all" />
                                    @else
                                        ID
                                    @endif
                                </th>
                                <th class="text-left svcr-list-header-item">Name</th>
                                @if (!$user->hasOnlyRole('instructor'))
                                    <th class="svcr-list-header-item">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody wire:model.live="instructors">
                            @php
                                // paginate
                                $sinstructors = $serviceRole->instructors()->paginate(5, ['*'], 'ins_pg')->appends(Arr::except(request()->query(), 'ins_pg'));
                            @endphp
                            @forelse ($sinstructors as $instructor)
                                <tr class="svcr-list-item">
                                    <td class="svcr-list-item-cell">
                                        @if (!$user->hasOnlyRole('instructor'))
                                            <input type="checkbox" class="svcr-list-item-select" id="svcr-select-{{ $instructor->id }}" />
                                        @else
                                            {{ $instructor->id }}
                                        @endif
                                    </td>
                                    <td class="svcr-list-item-cell">{{ $instructor->getName() }}</td>
                                    @if (!$user->hasOnlyRole('instructor'))
                                        <td class="svcr-list-item-cell">
                                            <div class="flex justify-full j-end svcr-list-item-actions" style="justify-content: end;">
                                                <button class="btn" x-on:click="$dispatch('confirm-remove-instructor', { id: {{ $instructor->id }} })" wire:loading.attr="disabled">
                                                    <span class="material-symbols-outlined icon">person_remove</span>
                                                </button>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr class="svcr-list-item nos">
                                    <td class="svcr-list-item-cell empty" colspan="5">No Instructors</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="svcr-list-footer">
                                <td class="svcr-list-footer-item" colspan="5">
                                    {{ $sinstructors->links() }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div class="bottom">
        {{-- extra hours list, pagination --}}
        <div class="justify-start m-4 w-100 grouped horizontal">
            <div class="form-group">
                <div class="svcr-extra-hours">
                    <h2 class="nos content-title form-item">
                        <span class="flex-1 w-fill content-title-text"style="width: fit-content;">{{ __('Extra Hours') }}</span>
                        {{-- <div class="flex justify-end">
                            <button class="btn form-input" x-on:click="$dispatch('open-modal', { component: 'extra-hour-form', arguments: {serviceRoleId: {{ $serviceRole->id }} }})" wire:loading.attr="disabled">
                                <span class="material-symbols-outlined icon">more_time</span>
                                <span>Add Extra Hours</span>
                            </button>
                        </div> --}}
                    </h2>
                    <table class="table svcr-table" id="svcr-table">
                        <thead>
                            <tr class="svcr-list-header">
                                <th class="svcr-list-header-item">Date</th>
                                <th class="svcr-list-header-item">Hours</th>
                                <th class="svcr-list-header-item">Description</th>
                                <th class="svcr-list-header-item">Awarded to</th>
                                @if(!$user->hasOnlyRole('instructor'))
                                    <th class="text-right svcr-list-header-item">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody wire:model.live="extraHours">
                            @php
                                // paginate
                                $sextraHours = $serviceRole->extraHours();
                                // add pagination after converting to relation instance
                                $sextraHours = $sextraHours->paginate(5, ['*'], 'extraHours');
                            @endphp
                            @forelse ($sextraHours as $extraHour)
                                <tr class="svcr-list-item">
                                    <td class="svcr-list-item-cell">{{
                                        date('F', mktime(0, 0, 0, $extraHour->month, 10))
                                    }}</td>
                                    <td class="svcr-list-item-cell">{{ $extraHour->hours }}</td>
                                    <td class="svcr-list-item-cell">{{ $extraHour->description }}</td>
                                    <td class="svcr-list-item-cell">
                                        @if ($extraHour->area_id)
                                            {{ $extraHour->area->name }}

                                            @if ($extraHour->instructor_id)
                                                / {{ $extraHour->instructor->user->getName() }}
                                            @endif
                                        @elseif ($extraHour->instructor_id)
                                            {{ $extraHour->instructor->user->getName() }}
                                        @endif
                                    </td>
                                    @if(!$user->hasOnlyRole('instructor'))
                                        <td class="svcr-list-item-cell">
                                            <div class="flex justify-full j-end svcr-list-item-actions" style="justify-content: end;">
                                                <button class="btn" x-on:click="$dispatch('confirm-extra-hour-delete', { id: {{ $extraHour->id }} })" wire:loading.attr="disabled">
                                                    <span class="material-symbols-outlined icon">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr class="svcr-list-item nos">
                                    <td class="svcr-list-item-cell empty" colspan="5">No Extra Hours</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="svcr-list-footer">
                                <td class="svcr-list-footer-item" colspan="5">
                                    {{ $sextraHours->links() }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-link-bar :links="$links" />
    <div class="svcr-instructor-modal" x-show="showInstructorModal" x-cloak>
        <x-dialog-modal wire:key="instructor-modal" wire:model.live="showInstructorModal">
            <x-slot name="title">
                <h2 class="nos content-title">
                    <span class="content-title-text">Assign Instructor</span>
                </h2>
            </x-slot>

            <x-slot name="content">
                <form id="instructor-form" class="form">
                    @csrf
                    <div class="horizontal grouped">
                        <div class="form-group nobs">
                            <div class="form-item">
                                <label class="form-item" for="instructor_id">Instructor</label>
                                <select class="form-select" id="instructor_id" wire:model="instructor_id" name="instructor_id">
                                    <option value="">Select Instructor</option>
                                    @foreach($allInstructors as $instructor)
                                        <option value="{{ $instructor->id }}">{{ $instructor->getName() }}</option>
                                    @endforeach
                                </select>
                                @error('instructor_id') <span class="error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-item">
                                <label class="form-item" for="role">Role</label>
                                <div class="grouped">
                                    <select class="form-select" id="roles_list" wire:model="role" name="role" required>
                                        <option value="">Select Role</option>
                                        @foreach ($allRoles as $srole)
                                            <option value="{{ $srole->id }}"
                                                @if ($role == $srole->id)
                                                    selected
                                                @endif
                                                >{{ $srole->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('role') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button type="button" class="btn"
                    x-on:click="showInstructorModal = false"
                    wire:loading.attr="disabled">
                    <span class="material-symbols-outlined icon">cancel</span>
                    <span>Cancel</span>
                </x-secondary-button>
                <x-button type="button" class="ml-2 btn" wire:loading.attr="disabled" id="save-instructor">
                    <span class="material-symbols-outlined icon">save</span>
                    <span>Save</span>
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', initInstructorForm);
        document.addEventListener('livewire:init', initInstructorForm);
        document.addEventListener('livewire:load', initInstructorForm);
        document.addEventListener('livewire:update', initInstructorForm);

        document.addEventListener('DOMContentLoaded', initServiceRoleForm);
        document.addEventListener('livewire:init', initServiceRoleForm);
        document.addEventListener('livewire:load', initServiceRoleForm);
        document.addEventListener('livewire:update', initServiceRoleForm);

        document.addEventListener('DOMContentLoaded', handleExport);
        document.addEventListener('livewire:init', handleExport);
        document.addEventListener('livewire:load', handleExport);
        document.addEventListener('livewire:update', handleExport);

        function handleExport() {
            if (document.querySelector('#exportDropdown.initialized')) return;
            const exportDropdown = document.getElementById('exportDropdown');
            if (!exportDropdown) return;
            exportDropdown.classList.add('initialized');
            // exportDropdown.addEventListener('dropdown-item-selected', function (e) {
            exportDropdown.addEventListener('change', function (e) {
                // const value = e.detail.value;
                const value = exportDropdown.value;
                @this.dispatch('export-role', { 'format': value });
            });
        }

        function initInstructorForm() {
            if (document.querySelector('.instructor-form-init')) return;
            const form = document.getElementById('instructor-form');
            if (!form) return;
            form.classList.add('instructor-form-init');
            const instructor_id = document.querySelector('#instructor_id');
            const role = document.querySelector('#roles_list');
            const saveButton = document.querySelector('#save-instructor');

            instructor_id.addEventListener('change', function () {
                @this.set('instructor_id', instructor_id.value);
            });
            role.addEventListener('change', function () {
                @this.set('role', role.value);
            });
            saveButton.addEventListener('click', function (e) {
                console.log(e);
                e.preventDefault();
                @this.dispatch('save-instructor');
            });
        }

        function initServiceRoleForm() {
            if (document.querySelector('.service-role-form-init')) return;
            const form = document.getElementById('service-role-update-form');
            if (!form) return;
            form.classList.add('service-role-form-init');
            const name = document.querySelector('#name');
            const description = document.querySelector('#description');
            const year = document.querySelector('#year');
            const area_id = document.querySelector('#area_id');
            const monthlyHours = document.querySelectorAll('.monthlyHour input');
            const saveSRButton = document.querySelector('#save-service-role');
            const roomB = document.querySelector('#roomB');
            const roomN = document.querySelector('#roomN');
            const roomS = document.querySelector('#roomS');


            if (name) {
                name.addEventListener('change', function () {
                    @this.set('name', name.value);
                });
            }
            if (description) {
                description.addEventListener('change', function () {
                    const value = description.value ?? description.textContent;
                    @this.set('description', value);
                });
            }
            if (year) {
                year.addEventListener('change', function () {
                    // this is a select
                    const value = year.options[year.selectedIndex].value;
                    @this.set('year', value);
                });
            }
            if (area_id) {
                area_id.addEventListener('change', function () {
                    // this is a select
                    const value = area_id.options[area_id.selectedIndex].value;
                    @this.set('area_id', value);
                });
            }
            if (monthlyHours) {
                monthlyHours.forEach(month => {
                    month.addEventListener('change', function () {
                        const monthId = month.id.split('_')[2];
                        @this.set(`monthly_hours.${monthId}`, month.value);
                    });
                });
            }
            if (roomB) {
                roomB.addEventListener('change', function () {
                    @this.set('roomB', roomB.value);
                });
            }
            if (roomN) {
                roomN.addEventListener('change', function () {
                    @this.set('roomN', roomN.value);
                });
            }
            if (roomS) {
                roomS.addEventListener('change', function () {
                    @this.set('roomS', roomS.value);
                });
            }
            if (saveSRButton) {
                saveSRButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    @this.dispatch('update-role');
                    @this.call('refresh');
                });
            }
        }
    </script>
</div>
