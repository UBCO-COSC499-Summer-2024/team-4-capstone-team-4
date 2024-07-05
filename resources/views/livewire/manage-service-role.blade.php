@vite('resources/css/manage-service-role.css')
<div class="content"
    x-data="{ isEditing: @entangle('isEditing'), showInstructorModal: @entangle('showInstructorModal') }"
>
    <h1 class="nos content-title">
        <span class="content-title-text">
            @if ($serviceRole)
                {{ $serviceRole->name }}
            @else
                {{ __('No Service Role Selected') }}
            @endif
        </span>

        <div class="flex right">
            <button class="btn" x-on:click="isEditing = !isEditing" wire:loading.attr="disabled" x-show="!isEditing" x-cloak>
                <span class="material-symbols-outlined icon">
                    edit
                </span>
                <span>Edit</span>
            </button>
            <button class="btn" x-on:click="isEditing = false" wire:loading.attr="disabled" x-show="isEditing" x-cloak>
                <span class="material-symbols-outlined icon">close</span>
                <span>Cancel</span>
            </button>
            <button class="btn" x-on:click="$dispatch('confirm-manage-delete', { 'id': {{ $serviceRole->id }} })" wire:loading.attr="disabled">
                <span class="material-symbols-outlined icon">delete</span>
                <span>Delete</span>
            </button>
        </div>
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

    <div class="svcrole-item" :class="{ 'bg-default': !isEditing, 'bg-editing': isEditing }">
        <section id="about-role" class="svcr-item">
            <form id="service-role-update-form" class="form svcr-item-form">
                <div class="horizontal grouped">
                    <div class="form-group">
                        <div class="form-item">
                            <label class="form-item" for="name">Name</label>
                            <div class="grouped">
                                <input class="form-input" type="text" id="name" wire:model="name" x-bind:disabled="!isEditing" value="{{ $serviceRole->name }}">
                                @error('name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="grouped">
                            <label class="form-item" for="description">Description</label>
                            <div class="grouped">
                                <textarea class="form-input" id="description" wire:model="description" x-bind:disabled="!isEditing" >{{ $serviceRole->description }}</textarea>
                                @error('description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-item" for="year">Year</label>
                            <div class="grouped">
                                <input class="form-input" type="number" id="year" wire:model="year" x-bind:disabled="!isEditing" value="{{ $year }}">
                                @error('year') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-item" for="area_id">Area</label>
                            <div class="grouped">
                                <select class="form-select" id="area_id" wire:model="area_id" x-bind:disabled="!isEditing" >
                                    <option value="">Select Area</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" @if ($serviceRole->area_id == $area->id) selected @endif>{{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @error('area_id') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-item" x-show="isEditing" x-cloak>
                            <button class="btn form-input" wire:loading.attr="disabled" id="save-service-role">
                                <span class="material-symbols-outlined icon">save</span>
                                <span>Save</span>
                             </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class='p-0 calendar form-item'>
                            <div class="p-0 grouped">
                                <label class="form-item" for="monthHours">Monthly Hours</label>
                                <section class="calendar-header">
                                    <button type="button" wire:click="decrementYear" x-bind:disabled="!isEditing">
                                        <span class="material-symbols-outlined icon">arrow_back</span>
                                    </button>
                                    <span id="year">{{ $year }}</span>
                                    <button type="button" wire:click="incrementYear" x-bind:disabled="!isEditing">
                                        <span class="material-symbols-outlined icon">arrow_forward</span>
                                    </button>
                                </section>
                                <section class="calendar-grid">
                                    @foreach ($monthly_hours as $month => $hours)
                                        <div class="month glass monthlyHour">
                                            <div>{{ $month }}</div>
                                            <input type="number" id="monthly_hours_{{ $month }}" wire:model="monthly_hours.{{ $month }}" placeholder="Hrs" max="730" min="0" x-bind:disabled="!isEditing" value="{{ $hours }}">
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
            <div class="svcr-instructor-list">
                <h2 class="nos form-item content-title">
                    <span class="content-title-text">Instructors</span>
                    <div class="flex justify-end form-item">
                        <button class="btn form-input" x-on:click="showInstructorModal = true" wire:loading.attr="disabled">
                            <span class="material-symbols-outlined icon">add</span>
                            <span>Assign Instructor</span>
                        </button>
                    </div>
                </h2>
                <table class="table svcr-table" id="svcr-table">
                    <thead>
                        <tr class="svcr-list-header">
                            {{-- <th class="svcr-list-header-item">
                                <input type="checkbox" class="svcr-list-item-select" id="svcr-select-all" />
                            </th> --}}
                            <th class="svcr-list-header-item">Name</th>
                            <th class="svcr-list-header-item" style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody wire:model.live="instructors">
                        @php
                            // paginate
                            $sinstructors = $serviceRole->instructors()->paginate(5);
                        @endphp
                        @forelse ($sinstructors as $instructor)
                            <tr class="svcr-list-item">
                                {{-- <td class="svcr-list-item-cell">
                                    <input type="checkbox" class="svcr-list-item-select" id="svcr-select-{{ $instructor->id }}" />
                                </td> --}}
                                <td class="svcr-list-item-cell">{{ $instructor->getName() }}</td>
                                <td class="svcr-list-item-cell">
                                    <div class="flex justify-full j-end svcr-list-item-actions" style="justify-content: end;">
                                        <button class="btn" x-on:click="$dispatch('confirm-remove-instructor', { id: {{ $instructor->id }} })" wire:loading.attr="disabled">
                                            <span class="material-symbols-outlined icon">person_remove</span>
                                        </button>
                                    </div>
                                </td>
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
        </section>
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
                                        <option value="{{ $instructor->user->id }}">{{ $instructor->user->getName() }}</option>
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
                <button type="button" class="btn" wire:loading.attr="disabled" id="save-instructor">
                    <span class="material-symbols-outlined icon">save</span>
                    <span>Save</span>
                </button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', initInstructorForm);
    document.addEventListener('livewire:init', initInstructorForm);
    document.addEventListener('livewire:load', initInstructorForm);
    document.addEventListener('livewire:update', initInstructorForm);

    document.addEventListener('DOMContentLoaded', initServiceRoleForm);
    document.addEventListener('livewire:init', initServiceRoleForm);
    document.addEventListener('livewire:load', initServiceRoleForm);
    document.addEventListener('livewire:update', initServiceRoleForm);

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
        if (saveSRButton) {
            saveSRButton.addEventListener('click', function (e) {
                e.preventDefault();
                @this.dispatch('update-role');
            });
        }
    }
</script>
