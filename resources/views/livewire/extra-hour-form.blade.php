<div x-data="{ show: false }" @open-extra-hour-modal.window="show = true" @close-modal.window="show = false">
    <x-dialog-modal wire:model="show">
        <x-slot name="title">
            {{ __('Extra Hour Form') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="form-item">
                    <div class="form-item" for="name">Name</div>
                    <input class="form-input" type="text" id="name" wire:model="name">
                    @error('name') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-item">
                    <div class="form-item" for="description">Description</div>
                    <textarea id="description" wire:model="description"></textarea>
                    @error('description') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-item">
                    <div class="form-item" for="hours">Hours</div>
                    <input class="form-input" type="number" id="hours" wire:model="hours">
                    @error('hours') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-item">
                    <div class="form-item" for="year">Year</div>
                    <input class="form-input" type="number" id="year" wire:model="year">
                    @error('year') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-item">
                    <div class="form-item" for="month">Month</div>
                    <input class="form-input" type="number" id="month" wire:model="month" min="1" max="12">
                    @error('month') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-item">
                    <div class="form-item" for="assigner_id">Assigner</div>
                    <select id="assigner_id" wire:model="assigner">
                        <option value="">Select Assigner</option>
                        @foreach($user_roles as $role)
                            <option value="{{ $role->id }}">{{ $role->user->name }}</option>
                        @endforeach
                    </select>
                    @error('assigner') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-item">
                    <div class="form-item" for="instructor_id">Instructor</div>
                    <select id="instructor_id" wire:model="instructor_id">
                        <option value="">Select Instructor</option>
                        @foreach($user_roles as $role)
                            <option value="{{ $role->id }}">{{ $role->user->name }}</option>
                        @endforeach
                    </select>
                    @error('instructor_id') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-item">
                    <div class="form-item" for="area_id">Area</div>
                    <select id="area_id" wire:model="area">
                        <option value="">Select Area</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                    @error('area') <span class="error">{{ $message }}</span> @enderror
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$dispatch('closeModal')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ml-2" wire:click="save" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
