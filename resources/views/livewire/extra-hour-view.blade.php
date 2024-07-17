<div>
    <x-dialog-modal id="extraHourViewModal">
        <x-slot name="title">
            {{ __('View Extra Hours') }}
        </x-slot>
        <x-slot name="content">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Hours</th>
                        <th>Year</th>
                        <th>Month</th>
                        <th>Assigner</th>
                        <th>Instructor</th>
                        <th>Area</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($extraHours as $extraHour)
                        <tr x-data="{ editing: @if ($editing === $extraHour->id) true @else false @endif }">
                            <td x-show="!editing">{{ $extraHour->name }}</td>
                            <td x-show="!editing">{{ $extraHour->description }}</td>
                            <td x-show="!editing">{{ $extraHour->hours }}</td>
                            <td x-show="!editing">{{ $extraHour->year }}</td>
                            <td x-show="!editing">{{ $extraHour->month }}</td>
                            <td x-show="!editing">{{ $extraHour->assigner->user->firstname }} {{ $extraHour->assigner->user->lastname }}</td>
                            <td x-show="!editing">{{ $extraHour->instructor->user->firstname }} {{ $extraHour->instructor->user->lastname }}</td>
                            <td x-show="!editing">{{ $extraHour->area->name }}</td>
                            <td x-show="!editing">
                                <button wire:click="edit({{ $extraHour->id }})" class="btn btn-sm btn-primary">Edit</button>
                                <button wire:click="delete({{ $extraHour->id }})" class="btn btn-sm btn-danger" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">Delete</button>
                            </td>

                            {{-- Edit Form Columns (when editing) --}}
                            <td x-show="editing">
                                <input type="text" wire:model="item.name" class="form-control">
                                @error('item.name') <span class="text-danger">{{ $message }}</span> @enderror
                            </td>
                            <td x-show="editing">
                                <input type="text" wire:model="item.description" class="form-control">
                                @error('item.description') <span class="text-danger">{{ $message }}</span> @enderror
                            </td>
                            {{-- ... similar for other fields ... --}}
                            <td x-show="editing">
                                <button wire:click="update({{ $extraHour->id }})" class="btn btn-sm btn-success">Save</button>
                                <button wire:click="cancelEdit({{ $extraHour->id }})" class="btn btn-sm btn-secondary">Cancel</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">No extra hours found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if(!empty($extraHours))
                {{ $extraHours->links() }}
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-button wire:click="closeExtraHourViewModal">
                {{ __('Close') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
