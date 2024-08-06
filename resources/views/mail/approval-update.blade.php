<x-mail::message>
# {{ config('app.name') }} - Approval Update

@if ($approval->approvalType->name == 'registration')
    {{-- Template for registration approval type --}}
    ## {{ __('Welcome to :app!', ['app' => config('app.name')]) }}

    @if ($approval->status->name == 'approved')
        {{ __('You can now login using the button below:') }}
        <x-mail::button :url="route('login')">
            {{ __('Login') }}
        </x-mail::button>
    @else
        {{ __('Unfortunately, your registration has been :status. Please contact support for more information.', ['status' => $approval->status->name]) }}

        <x-mail::panel>
            {{ __('Email: :email', ['email' => env('SUPPORT_EMAIL')]) }}<br>
            {{ __('Phone: :phone', ['phone' => env('SUPPORT_PHONE')]) }}
        </x-mail::panel>
    @endif
@else
    {{ __('Approval Type: :type', ['type' => $approval->approvalType->name]) }}<br>
    {{ __('Status: :status', ['status' => $approval->status->name]) }}<br>
    {{ __('Details: :details', ['details' => $approval->details]) }}
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
