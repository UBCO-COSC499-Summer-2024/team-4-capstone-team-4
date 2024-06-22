<div class="content">
    <h1 class="nos flex">
        <span class="content-title-text">Add Service Role</span>
        {{-- <section class="mini-toolbar">
            <div class="toolbar-section-item">
            </div>
        </section> --}}
        <button>
            <span class="button-title">See All</span>
            <span class="material-symbols-outlined">visibility</span>
        </button>
    </h1>
    <section id="import-data" class="active glass">
        <form id="account-form" class="form">
            <div class="horizontal grouped">
                <div class="form-group">
                    <div class="grouped">
                        <div class="form-item">
                            <label class="form-label">Role:</label>
                            <input class="form-input" type="text" name="role">
                        </div>
                        <div class="form-item">
                            <label class="form-label">Area:
                            </label>
                            {{-- use the dropdown --}}
                            @php
                                $areas = App\Models\Area::all();
                                $areaValues = [];
                                foreach ($areas as $area) {
                                    $areaValues[$area->name] = $area->name;
                                }
                            @endphp
                            <x-dropdown-element 
                                id="areaDropdown" 
                                class="toolbar-dropdown" 
                                title="Area"
                                :values="$areaValues"
                                preIcon="category"
                                searchable="true" />
                        </div>
                        <div class="form-item">
                            <label class="input-label">Description:</label>
                            <textarea class="form-input form-textarea" id="description" name="description" rows="4" cols="30"></textarea>
                        </div>
                        <div class="form-item">
                            <label class="input-label">Add Extra Hours:</label>
                            <button type="button" class="button" wire:click="$emit('openModal', 'openModal')">
                                <span class="button-title">Add</span>
                                <span class="material-symbols-outlined">add</span>
                            </button>
                        </div>
                        <div class="form-item bottom">
                            <input class="form-input" type="submit" name="submit" value="Cancel" />
                            <input class="form-input" type="submit" name="submit" value="Add" />
                        </div>
                    </div>
                </div>
                {{-- <x-dialog-modal id="{{ __('addExtraHrs') }}">
                    <x-slot name="title">
                        {{ __('Add Extra Hours') }}
                    </x-slot>
                    <x-slot name="content">
                        <x-calendar />
                    </x-slot>
                    <x-slot name="footer">
                        <x-secondary-button >{{ __('Cancel') }}</x-secondary-button>
                        <x-danger-button wire.model>{{ __('Save') }}</x-danger-button>
                    </x-slot>
                </x-dialog-modal> --}}
            </div>
        </form>
    </section>
    <x-link-bar :links="$links" />
</div>
@livewire('add-extra-hours-modal')
@livewireScripts

{{-- add extra hours button toggles the calendar --}}
<script>
    document.querySelector('.form-item button').addEventListener('click', function(e) {
        e.preventDefault();
        const ehCal = document.querySelector('div.calendar');
        if (ehCal.style.display === 'none') {
            ehCal.style.display = 'block';
        } else {
            ehCal.style.display = 'none';
        }
    });
</script>
