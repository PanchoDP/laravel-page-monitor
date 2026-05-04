@if ($paginator->hasPages())
    <nav class="pagination">
        @if ($paginator->onFirstPage())
            <span class="page-btn disabled">← Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-btn">← Prev</a>
        @endif

        <span class="page-info">{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn">Next →</a>
        @else
            <span class="page-btn disabled">Next →</span>
        @endif
    </nav>
@endif