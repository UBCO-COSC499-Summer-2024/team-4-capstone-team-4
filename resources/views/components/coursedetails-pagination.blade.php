@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="pagination-btn pagination-disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span aria-hidden="true">&laquo;</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn" rel="prev" aria-label="{{ __('pagination.previous') }}">
                    &laquo;
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" rel="next" aria-label="{{ __('pagination.next') }}">
                    &raquo;
                </a>
            @else
                <span class="pagination-btn pagination-disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span aria-hidden="true">&raquo;</span>
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-l leading-5 text-gray-700">
                    <span class="font-bold">{{ __('Showing') }}</span>
                    <span class="font-bold">{{ $paginator->firstItem() }}</span>
                    <span class="font-bold">{{ __('to') }}</span>
                    <span class="font-bold">{{ $paginator->lastItem() }}</span>
                    <span class="font-bold">{{ __('of') }}</span>
                    <span class="font-bold">{{ $paginator->total() }}</span>
                    <span class="font-bold">{{ __('results') }}</span>
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="pagination-btn pagination-disabled" aria-hidden="true">&lsaquo;</span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-btn" aria-label="{{ __('pagination.previous') }}">&lsaquo;</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="pagination-btn pagination-disabled">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="pagination-btn pagination-active">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="pagination-btn" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-btn" aria-label="{{ __('pagination.next') }}">&rsaquo;</a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="pagination-btn pagination-disabled" aria-hidden="true">&rsaquo;</span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
