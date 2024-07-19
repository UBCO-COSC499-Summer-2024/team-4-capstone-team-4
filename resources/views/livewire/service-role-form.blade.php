<div>
    <form wire:submit.prevent="save" id="service-role" class="form">
        <div class="horizontal grouped">
            <div class="form-group">
                <div class="form-item">
                    <div class="form-item" for="name">Name</div>
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
                    <div class="form-item" for="year">Year</div>
                    <div class="grouped">
                        <input class="form-input" type="number" id="year" wire:model="year" value="{{ $year }}">
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
                <div class='calendar form-item'>
                    <div class="form-item" for="monthHours">Monthly Hours</div>
                    {{-- <section class="calendar-header"> --}}
                        {{-- <button wire:click="decrementYear">
                            <span class="material-symbols-outlined icon">arrow_back</span>
                        </button> --}}
                        {{-- <span id="year">{{ $year }}</span> --}}
                        {{-- <button wire:click="incrementYear">
                            <span class="material-symbols-outlined icon">arrow_forward</span>
                        </button> --}}
                    {{-- </section> --}}
                    <section class="calendar-grid">
                        @foreach ($monthly_hours as $month => $hours)
                            <div class="month glass">
                                <div>{{ $month }}</div>
                                <input type="number" wire:model="monthly_hours.{{ $month }}" placeholder="Hrs" max="730" min="0" value="{{ $hours }}">
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
                                if (value < 0) {
                                    input.value = 0;
                                }
                                if (value > 730) {
                                    input.value = 730;
                                }
                            }
                        });
                    });
                </script>
                @endpush
            </div>
        </div>
        <div class="form-item">
            <button class="form-input" type="submit">Save</button>
        </div>
    </form>
</div>
