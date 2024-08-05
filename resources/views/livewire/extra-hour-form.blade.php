<div x-data="{ showExtraHourForm: @entangle('showExtraHourForm'), show: @entangle('showExtraHourForm')}">
    <x-dialog-modal id="extraHourFormModal" wire:model="showExtraHourForm">
        <x-slot name="title">
            {{ __('Add Service Time') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="horizontal grouped">
                    <div class="form-group nobs">
                        <div class="form-item">
                            <div class="form-item-text" for="name">Name</div>
                            <div class="grouped">
                                <input class="form-input" type="text" id="name" wire:model.live="name">
                                @error('name') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grouped">
                            <div class="form-item" for="description">Description</div>
                            <div class="grouped">
                                <textarea class="form-input" id="description" wire:model.live="description"></textarea>
                                @error('description') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-item">
                            <div class="form-item-text" for="hours">Hours</div>
                            <div class="grouped">
                                <input class="form-input" type="number" id="hours" wire:model.live="hours">
                                @error('hours') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-item">
                            <label class="form-item" for="room">Room</label>
                            <div class="grouped">
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
                            <div class="form-item-text" for="year">Year</div>
                            <div class="grouped">
                                <input class="form-input" type="number" id="year" wire:model.live="year">
                                @error('year') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-item">
                            <div class="form-item-text" for="month">Month</div>
                            <div class="grouped">
                                <input class="form-input" type="number" id="month" wire:model.live="month" min="1" max="12">
                                @error('month') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-item">
                            <div class="form-item-text" for="assigner_id">Assigner</div>
                            <div class="grouped">
                                <div class="form-item">
                                    <input class="form-input text-end" disabled id="assigner" value="{{ auth()->user()->getName() }}">
                                    <input class="min-w-0 form-input text-end w-fit"  type="number" id="assigner_id" wire:model.live="assigner_id" disabled value="{{ $assigner_id }}" style="min-width: 0 !important;">
                                </div>
                                @error('assigner_id') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-item">
                            <div class="form-item-text" for="instructor_id">Instructor</div>
                            <div class="grouped">
                                <select class="form-select" id="instructor_id" wire:model.live="instructor_id">
                                    <option value="">Select Instructor</option>
                                    @foreach($user_roles as $role)
                                        <option value="{{ $role->id }}"
                                                @if ($role->id == $instructor_id) selected @endif
                                            >{{ $role->user->getName() }}</option>
                                    @endforeach
                                </select>
                                @error('instructor_id') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-item">
                            <div class="form-item-text" for="area_id">Area</div>
                            <div class="grouped">
                                <select class="form-select" id="area_id" wire:model.live="area_id">
                                    <option>Select Area</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}"
                                                @if ($area->id == $area_id) selected @endif
                                            >{{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @error('area_id') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button
                wire:click="$toggle('showExtraHourForm')"
                wire:loading.attr="disabled"
                wire:key="cancel-extra-hour-form"
            >
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-button class="ml-2" wire:click="save" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
