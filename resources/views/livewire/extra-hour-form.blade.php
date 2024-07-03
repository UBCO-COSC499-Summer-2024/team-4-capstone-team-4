<div>
    <x-dialog-modal id="extraHourFormModal">
        <x-slot name="title">
            {{ __('Add Extra Hour') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="horizontal grouped">
                    <div class="form-group nobs">
                        <div class="form-item">
                            <div class="form-item-text" for="name">Name</div>
                            <div class="grouped">
                                <input class="form-input" type="text" id="name" wire:model="name">
                                @error('name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grouped">
                            <div class="form-item" for="description">Description</div>
                            <div class="grouped">
                                <textarea class="form-input" id="description" wire:model="description"></textarea>
                                @error('description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-item">
                            <div class="form-item-text" for="hours">Hours</div>
                            <div class="grouped">
                                <input class="form-input" type="number" id="hours" wire:model="hours">
                                @error('hours') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-item">
                            <div class="form-item-text" for="year">Year</div>
                            <div class="grouped">
                                <input class="form-input" type="number" id="year" wire:model="year">
                                @error('year') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-item">
                            <div class="form-item-text" for="month">Month</div>
                            <div class="grouped">
                                <input class="form-input" type="number" id="month" wire:model="month" min="1" max="12">
                                @error('month') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-item">
                            <div class="form-item-text" for="assigner_id">Assigner</div>
                            <div class="grouped">
                                <select class="form-select" id="assigner_id" wire:model="assigner">
                                    <option value="">Select Assigner</option>
                                    @foreach($user_roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->user->name }}</option>
                                    @endforeach
                                </select>
                                @error('assigner') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-item">
                            <div class="form-item-text" for="instructor_id">Instructor</div>
                            <div class="grouped">
                                <select class="form-select" id="instructor_id" wire:model="instructor_id">
                                    <option value="">Select Instructor</option>
                                    @foreach($user_roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->user->name }}</option>
                                    @endforeach
                                </select>
                                @error('instructor_id') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-item">
                            <div class="form-item-text" for="area_id">Area</div>
                            <div class="grouped">
                                <select class="form-select" id="area_id" wire:model="area">
                                    <option value="">Select Area</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @error('area') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button
                wire:click="cancel"
                wire:loading.attr="disabled"
                wire:key="cancel-extra-hour-form{{ $serviceRoleId }}"
            >
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-button class="ml-2" wire:click="save" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
