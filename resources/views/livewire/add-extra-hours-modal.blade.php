<div x-data="{show: false}">
    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Add Extra Hours') }}
        </x-slot>
        <x-slot name="content">
            <x-calendar />
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showModal', false)">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-danger-button wire:click="save">
                {{ __('Save') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>