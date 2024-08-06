@component('mail::message')
<h1>{{ config('app.name') . __(' - Approval Update') }}</h1>
@if ($approval->approvalType->name == 'registration')

<h2>{{ __('Your registration has been :status.', ['status' => $approval->status->name]) }}</h2>
@if ($approval->status->name == 'approved')
<p>{{ __('Details: :details', ['details' => $approval->details]) }}</p>

@component('mail::table')
| {{ __('Requested') }} | {{ __('Approved') }} | {{ __('Approved By') }} |
|:-------------------:|:-----------------:|:------------------:|
| {{ $approval->created_at->format('Y-m-d H:i:s') }} | {{ $approval->updated_at->format('Y-m-d H:i:s') }} | {{ $approval->approver->getName() }} |
@endcomponent

<p style="text-align:center;">{{ __('You can now login using the button below:') }}</p>
@component('mail::button', ['url' => route('login')])
{{ __('Login') }}
@endcomponent
@else
{{-- Unfortunately, your registration has been {{ $approval->status->name }}. Please contact support for more information. --}}
{{ __('Unfortunately, your registration has been :status. Please contact support for more information.', ['status' => $approval->status->name]) }}

{{-- support email and number from env --}}
{{-- @component('mail::panel') --}}
{{ __('Email: :email', ['email' => env('SUPPORT_EMAIL')]) }}
{{ __('Phone: :phone', ['phone' => env('SUPPORT_PHONE')]) }}
{{-- @endcomponent --}}
@endif
@else
{{ __('Approval Type: :type', ['type' => $approval->approvalType->name]) }}
{{ __('Status: :status', ['status' => $approval->status->name]) }}

{{-- Details: {{ $approval->details }} --}}
{{ __('Details: :details', ['details' => $approval->details]) }}
@endif

<p style="text-align: center; font-weight:bold; margin: 1rem 0;">{{ __('Thanks, :app', ['app' => config('app.name')]) }}</p>

@endcomponent
