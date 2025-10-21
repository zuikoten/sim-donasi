<!-- FILE PAGINATION KUSTOM INI YANG DIGUNAKAN -->
@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            {{-- First Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">&laquo; First</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}" rel="prev" aria-label="Previous">&laquo; First</a>
                </li>
            @endif

            {{-- Previous Page Link --}}
            @if ($paginator->currentPage() > 1)
                <li class="page-item">
                    <!-- FILE PAGINATION KUSTOM INI YANG DIGUNAKAN -->
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">&lsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">&lsaquo;</span>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                <li class="page-item {{ $element['page'] == $paginator->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $element['url'] }}">{{ $element['page'] }}</a>
                </li>
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <!-- FILE PAGINATION KUSTOM INI YANG DIGUNAKAN -->
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">&rsaquo;</span>
                </li>
            @endif

            {{-- Last Page Link --}}
            @if ($paginator->hasPages() && !$paginator->onLastPage())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" rel="next" aria-label="Next">Last &raquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">Last &raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif