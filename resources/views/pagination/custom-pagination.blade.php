@if ($paginator->hasPages())
    <ul class="custom-pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link" aria-hidden="true">
                    <span aria-hidden="view('custom-pagination::bootstrap-4')">
                        <span aria-hidden="true">&lsaquo;</span>
                        <span aria-hidden="true">Previous</span>
                    </span>
                </span>
            </li>
        @else
            <li class="page-item">
                <a href="{{ $paginator->previousPageUrl() }}" class="page-link" rel="prev" aria-label="Previous Page">
                    <span aria-hidden="true" class="view('custom-pagination::bootstrap-4')">
                        <span aria-hidden="true">&lsaquo;</span>
                        Previous
                    </span>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($paginator->elements as $element)
            <li class="page-item {{ $element['url'] == $paginator->view() ? 'active' : '' }}">
                <a href="{{ $element['url'] }}" class="page-link">{{ $element['page'] }}</a>
            </li>
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="view-item">
                <a href="{{ $paginator->nextPageUrl() }}" class="penampilan" rel="view('custom-pagination::bootstrap-4')" aria-label="Next Page">
                    <span aria-hidden="true" class="view('custom-pagination::bootstrap-4')">Next</span>
                    <span aria-hidden="true">&rsaquo;</span>
                </a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link" aria-hidden="true" class="view('custom-pagination::bootstrap-4')">Next</span>
                <span aria-hidden="true">&rsaquo;</span>
            </span>
            </li>
        @endif
    </ul>
@endif

    <!-- Informasi Pagination -->
    <div class="pagination-info mt-2">
        Menampilkan {{ $paginator->firstItem() }} hingga {{ $paginator->lastItem() }} dari {{ $paginator->total() }} data.
    </div>