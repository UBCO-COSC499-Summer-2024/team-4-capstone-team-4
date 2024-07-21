<div class="coming-soon">
    <form wire:submit.prevent="save" id="service-role" class="form">
        <div class="horizontal grouped w-fit">
            <div class="form-group">
                <div class="form-item">
                    <div class="form-item" for="name">Name</div>
                    <div class="grouped">
                        <input class="form-input" type="text" id="name" wire:model="name"
                        placeholder="ex. Student Advisor"
                        >
                        @error('name') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="grouped">
                    <div class="form-item" for="description">Description</div>
                    <div class="grouped">
                        <textarea class="form-input" id="description" wire:model="description"
                        placeholder="Brief description..."
                        ></textarea>
                        @error('description') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-item">
                    <div class="form-item" for="year">Year</div>
                    <div class="grouped">
                        <input class="form-input !min-w-fit" type="number" id="year" wire:model="year" value="{{ $year }}"
                        placeholder="ex. {{ date('Y') }}"
                        >
                        @error('year') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-item">
                    <div class="form-item" for="area_id">Area</div>
                    <div class="grouped">
                        <select class="form-select" id="area_id" wire:model="area_id">
                            <option value="">Select Area</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                        @error('area_id') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class='p-0 calendar form-item'>
                <div class="grouped">
                    <div class="form-item" for="monthHours">Monthly Hours</div>
                        <section class="calendar-header">
                            <button wire:click="decrementYear" type="button">
                                <span class="material-symbols-outlined icon">arrow_back</span>
                            </button>
                            <span id="year">{{ $year }}</span>
                            <button wire:click="incrementYear" type="button">
                                <span class="material-symbols-outlined icon">arrow_forward</span>
                            </button>
                        </section>
                        <section class="calendar-grid">
                            @foreach ($monthly_hours as $month => $hours)
                                <div class="month glass">
                                    <div>{{ $month }}</div>
                                    <input type="number" wire:model="monthly_hours.{{ $month }}" placeholder="Hrs" max="200" min="0" value="{{ $hours }}">
                                </div>
                            @endforeach
                        </section>
                        @error('monthly_hours') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    @push('scripts')
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            Livewire.on('validateInput', (inputId) => {
                                const input = document.getElementById(inputId);
                                if (input) {
                                    const value = parseInt(input.value, 10);
                                    if (value < input.min) {
                                        input.value = input.min;
                                    }
                                    if (value > input.max) {
                                        input.value = input.max;
                                    }
                                }
                            });
                        });
                    </script>
                    @endpush
                </div>
            </div>
        </div>
        <div class="form-item">
            <button class="form-input" type="submit">Save</button>
        </div>
    </form>
</div>
