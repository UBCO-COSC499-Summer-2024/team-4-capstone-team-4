@component('mail::message')
{{ config('app.name') . __(' - Approval Update') }}

@if ($approval->approvalType->name == 'registration')
    {{-- Template for registration approval type --}}
    {{-- ## Welcome to {{ config('app.name') }}!
    Your registration has been {{ $approval->status->name }}. --}}
    {{ __('Welcome to :app!', ['app' => config('app.name')]) }}

    @if ($approval->status->name == 'approved')
        {{ __('You can now login using the button below:') }}
        @component('mail::button', ['url' => route('login')])
        {{ __('Login') }}
        @endcomponent
    @else
        {{-- Unfortunately, your registration has been {{ $approval->status->name }}. Please contact support for more information. --}}
        {{ __('Unfortunately, your registration has been :status. Please contact support for more information.', ['status' => $approval->status->name]) }}

        {{-- support email and number from env --}}
        @component('mail::panel')
        {{ __('Email: :email', ['email' => env('SUPPORT_EMAIL')]) }}
        {{ __('Phone: :phone', ['phone' => env('SUPPORT_PHONE')]) }}
        @endcomponent
    @endif
@else
    {{ __('Approval Type: :type', ['type' => $approval->approvalType->name]) }}
    {{ __('Status: :status', ['status' => $approval->status->name]) }}

    {{-- Details: {{ $approval->details }} --}}
    {{ __('Details: :details', ['details' => $approval->details]) }}

    {{-- @component('mail::button', ['url' => route('approvals.show', $approval->id)])
    View Approval
    @endcomponent --}}
@endif
{{ __('Thanks, :app', ['app' => config('app.name')]) }}
@endcomponent
