@php
    use App\Helpers\HtmlHelpers;
@endphp

<section class="help-page">
    {!! HtmlHelpers::convertToJsonToHtml($data, $topic['title'] ?? 'Import Data') !!}
</section>