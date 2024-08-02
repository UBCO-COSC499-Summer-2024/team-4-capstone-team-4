@props(['for'])

@error($for)
    @if($message !== "These credentials do not match our records." && $message !== "This account is disabled.")
        <p {{ $attributes->merge(['class' => 'font-normal text-base text-red-600']) }}>{{ $message }}</p>
    @else
        @if($for == 'password')
            <p {{ $attributes->merge(['class' => 'font-normal text-base text-red-600']) }}>{{ $message }}</p>
        @endif
    @endif
@enderror
