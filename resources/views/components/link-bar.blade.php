<div {{ $attributes->merge(['class' => 'link-bar']) }}>
    @foreach ($links as $link)
        <x-link href="{{ $link['href'] }}" title="{{ $link['title'] }}" :active="$link['active']" />
</div>