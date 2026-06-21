@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="pagination-item" style="opacity: 0.4; cursor: not-allowed;"><span>&laquo;</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-item">&laquo;</a></li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-item">&raquo;</a></li>
            @else
                <li class="pagination-item" style="opacity: 0.4; cursor: not-allowed;"><span>&raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
