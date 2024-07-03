<div class="card svcr-card-item"
    id="svcrci-{{ $serviceRole->id }}"
    x-data="{ isEditing: false }">

    <div class="card-header">
        <h2 x-show="!isEditing" x-cloak>{{ $serviceRole->name }}</h2>
        <input x-show="isEditing" type="text" class="edit-title"
               {{-- wire:model="serviceRole.name" --}}
               x-cloak>

        {{-- checkbox --}}
        <input type="checkbox" class="svcr-list-item-select glass"
            id="svcr-select-{{ $serviceRole->id }}"
            value="{{ $serviceRole->id }}"
            {{-- wire:model.live="isSelected" --}}
        >
    </div>

    <div class="card-content">
        <div class="card-section">
            <section class="card-section-item">
                <span x-show="!isEditing" x-cloak>Area:</span>
                <span x-show="!isEditing" x-cloak>{{ $serviceRole->area->name }}</span>
                    <select x-show="isEditing" class="svcr-list-item-edit"
                            {{-- wire:model="serviceRole.area_id" --}}
                            x-cloak>
                        @foreach ($areas as $area)
                            <option value="{{ $area->area_id }}"
                                    @if ($serviceRole->area_id == $area->area_id) selected @endif
                                >{{ $area->name }}</option>
                        @endforeach
                    </select>
            </section>

            <section class="card-section-item">
                <p x-show="!isEditing" x-cloak>{{ $serviceRole->description }}</p>
                <textarea x-show="isEditing" class="edit-description"
                          {{-- wire:model="serviceRole.description" --}}
                          x-cloak>{{ $serviceRole->description }}</textarea>
            </section>

            <section class="card-section-item svcr-card-instructors">
                <h4>Instructors</h4>
                <ul>
                    @foreach ($serviceRole->instructors as $instructor)
                        <li>{{ $instructor->name }}</li>
                    @endforeach
                </ul>
            </section>
        </div>
    </div>

    <div class="card-footer">
        <button @click="isEditing = !isEditing"
                wire:click="editServiceRole()">
            <span class="material-symbols-outlined icon"
                  x-text="isEditing ? 'save' : 'edit'"></span>
            <span x-text="isEditing ? 'Save' : 'Manage'"></span>
        </button>

        <button wire:click="confirmDelete()">
            <span class="material-symbols-outlined icon">delete</span>
            <span>Delete</span>
        </button>
    </div>
</div>
