@if ($paginator->hasPages())
<div class="d-flex flex-stack flex-wrap pt-5">
    <div class="fs-7 fw-semibold text-gray-700">
        Menampilkan {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
    </div>
    <ul class="pagination">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <li class="page-item previous disabled">
                <span class="page-link"><i class="previous"></i></span>
            </li>
        @else
            <li class="page-item previous">
                <a href="{{ $paginator->previousPageUrl() }}" class="page-link"><i class="previous"></i></a>
            </li>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a href="{{ $url }}" class="page-link">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li class="page-item next">
                <a href="{{ $paginator->nextPageUrl() }}" class="page-link"><i class="next"></i></a>
            </li>
        @else
            <li class="page-item next disabled">
                <span class="page-link"><i class="next"></i></span>
            </li>
        @endif
    </ul>
</div>
@endif
