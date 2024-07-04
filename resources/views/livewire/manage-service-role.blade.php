@vite('resources/css/manage-service-role.css')
<div class="content"
    x-data="{ isEditing: @entangle('isEditing'), showInstructorModal: @entangle('showInstructorModal') }"
    wire:model.live="serviceRole"
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
            <button class="btn" wire:click="confirmDelete" wire:loading.attr="disabled">
                <span class="material-symbols-outlined icon">delete</span>
                <span>Delete</span>
            </button>
        </div>
    </h1>

    <div class="svcrole-item" :class="{ 'bg-default': !isEditing, 'bg-editing': isEditing }">
        <section id="about-role" class="svcr-item">
            <form wire:submit.prevent="saveServiceRole" id="service-role" class="form svcr-item-form" wire:loading.class="loading" wire:key="service-role-form" enctype="multipart/form-data">
                <div class="horizontal grouped">
                    <div class="form-group">
                        <div class="form-item">
                            <label class="form-item" for="name">Name</label>
                            <div class="grouped">
                                <input class="form-input" type="text" id="name" wire:model="serviceRole.name" x-bind:disabled="!isEditing" name="serviceRole.name">
                                @error('serviceRole.name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="grouped">
                            <label class="form-item" for="description">Description</label>
                            <div class="grouped">
                                <textarea class="form-input" id="description" wire:model="serviceRole.description" x-bind:disabled="!isEditing" name="serviceRole.description"></textarea>
                                @error('serviceRole.description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-item" for="year">Year</label>
                            <div class="grouped">
                                <input class="form-input" type="number" id="year" wire:model="serviceRole.year" x-bind:disabled="!isEditing" name="serviceRole.year">
                                @error('serviceRole.year') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-item" for="area_id">Area</label>
                            <div class="grouped">
                                <select class="form-select" id="area_id" wire:model="serviceRole.area_id" x-bind:disabled="!isEditing" name="serviceRole.area_id">
                                    <option value="">Select Area</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" @if ($serviceRole->area_id == $area->id) selected @endif>{{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @error('serviceRole.area_id') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-item" x-show="isEditing" x-cloak>
                            <button class="btn" x-on:click.prevent="isEditing = !isEditing; $dispatch('updateServiceRole')" wire:loading.attr="disabled" type="submit" x-show="isEditing" x-cloak>
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
                                        <div class="month glass">
                                            <div>{{ $month }}</div>
                                            <input type="number" wire:model="monthly_hours.{{ $month }}" placeholder="Hrs" max="730" min="0" x-bind:disabled="!isEditing" name="monthly_hours.{{ $month }}">
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
                        <button class="btn" x-on:click="showInstructorModal = true" wire:loading.attr="disabled">
                            <span class="material-symbols-outlined icon">add</span>
                            <span>Assign Instructor</span>
                        </button>
                    </div>
                </h2>
                <table class="table svcr-table" id="svcr-table">
                    <thead>
                        <tr class="svcr-list-header">
                            <th class="svcr-list-header-item">
                                <input type="checkbox" class="svcr-list-item-select" id="svcr-select-all" />
                            </th>
                            <th class="svcr-list-header-item">Name</th>
                            <th class="svcr-list-header-item">Email</th>
                            <th class="svcr-list-header-item">Role</th>
                            <th class="svcr-list-header-item">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($serviceRole->instructors as $instructor)
                            <tr class="svcr-list-item">
                                <td class="svcr-list-item-cell">
                                    <input type="checkbox" class="svcr-list-item-select" id="svcr-select-{{ $instructor->id }}" />
                                </td>
                                <td class="svcr-list-item-cell">{{ $instructor->name }}</td>
                                <td class="svcr-list-item-cell">{{ $instructor->email }}</td>
                                <td class="svcr-list-item-cell">{{ $instructor->pivot->role }}</td>
                                <td class="svcr-list-item-cell">
                                    <button class="btn">
                                        <span class="material-symbols-outlined icon">edit</span>
                                        <span>Edit</span>
                                    </button>
                                    <button class="btn">
                                        <span class="material-symbols-outlined icon">delete</span>
                                        <span>Delete</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr class="svcr-list-item nos">
                                <td class="svcr-list-item-cell empty" colspan="5">No Instructors</td>
                            </tr>
                        @endforelse
                    </tbody>
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
                <form wire:submit.prevent="saveInstructor">
                    <div class="form-group">
                        <label class="form-item" for="instructor_id">Instructor</label>
                        <select class="form-select" id="instructor_id" wire:model="instructor_id" name="instructor_id">
                            <option value="">Select Instructor</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                            @endforeach
                        </select>
                        @error('instructor_id') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-item" for="role">Role</label>
                        <input type="text" class="form-input" id="role" wire:model="role" name="role">
                        @error('role') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </form>
            </x-slot>

            <x-slot name="footer">
                <button type="button" class="btn" wire:click="$toggle('showInstructorModal')" wire:loading.attr="disabled">
                    <span class="material-symbols-outlined icon">cancel</span>
                    <span>Cancel</span>
                </button>
                <button type="button" class="btn" wire:click="saveInstructor" wire:loading.attr="disabled">
                    <span class="material-symbols-outlined icon">save</span>
                    <span>Save</span>
                </button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>
