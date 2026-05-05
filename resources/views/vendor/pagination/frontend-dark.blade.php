@if ($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $sidePages = 2;
        $startPage = max(1, $currentPage - $sidePages);
        $endPage = min($lastPage, $currentPage + $sidePages);

        if ($currentPage <= $sidePages + 1) {
            $endPage = min($lastPage, ($sidePages * 2) + 1);
        }

        if ($currentPage >= $lastPage - $sidePages) {
            $startPage = max(1, $lastPage - ($sidePages * 2));
        }
    @endphp

    <nav role="navigation" aria-label="Pagination Navigation" class="mt-2 border-t border-[#252B31] pt-6 text-white">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="text-sm text-gray-400">
                Showing
                <span class="font-medium text-white">{{ $paginator->firstItem() }}</span>
                to
                <span class="font-medium text-white">{{ $paginator->lastItem() }}</span>
                of
                <span class="font-medium text-white">{{ $paginator->total() }}</span>
                results
            </div>

            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                @if ($paginator->onFirstPage())
                    <span class="inline-flex h-10 min-w-[40px] cursor-not-allowed items-center justify-center rounded-xl border border-[#252B31] bg-[#161D23] px-3 py-2 text-sm font-medium text-gray-600 sm:min-w-[42px] sm:px-4">
                        Prev
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex h-10 min-w-[40px] items-center justify-center rounded-xl border border-[#2D3742] bg-[#1C2228] px-3 py-2 text-sm font-medium text-gray-300 transition duration-200 hover:border-[#2A7CFF] hover:bg-[#252C33] hover:text-white sm:min-w-[42px] sm:px-4">
                        Prev
                    </a>
                @endif

                @if ($startPage > 1)
                    <a href="{{ $paginator->url(1) }}" class="inline-flex h-10 min-w-[40px] items-center justify-center rounded-xl border border-[#2D3742] bg-[#1C2228] px-3 py-2 text-sm font-medium text-gray-300 transition duration-200 hover:border-[#2A7CFF] hover:bg-[#252C33] hover:text-white sm:min-w-[42px] sm:px-4" aria-label="Go to page 1">
                        1
                    </a>

                    @if ($startPage > 2)
                        <span class="inline-flex h-10 min-w-[28px] items-center justify-center px-1 text-sm text-gray-500 sm:min-w-[36px] sm:px-2">
                            ...
                        </span>
                    @endif
                @endif

                @for ($page = $startPage; $page <= $endPage; $page++)
                    @if ($page == $currentPage)
                        <span aria-current="page" class="inline-flex h-10 min-w-[40px] items-center justify-center rounded-xl border border-[#2A7CFF] bg-[linear-gradient(52deg,_#0844ff_11.5%,_#64b8fb_129.52%)] px-3 py-2 text-sm font-semibold text-white shadow-[0_0_0_1px_rgba(42,124,255,0.15)] sm:min-w-[42px] sm:px-4">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $paginator->url($page) }}" class="inline-flex h-10 min-w-[40px] items-center justify-center rounded-xl border border-[#2D3742] bg-[#1C2228] px-3 py-2 text-sm font-medium text-gray-300 transition duration-200 hover:border-[#2A7CFF] hover:bg-[#252C33] hover:text-white sm:min-w-[42px] sm:px-4" aria-label="Go to page {{ $page }}">
                            {{ $page }}
                        </a>
                    @endif
                @endfor

                @if ($endPage < $lastPage)
                    @if ($endPage < $lastPage - 1)
                        <span class="inline-flex h-10 min-w-[28px] items-center justify-center px-1 text-sm text-gray-500 sm:min-w-[36px] sm:px-2">
                            ...
                        </span>
                    @endif

                    <a href="{{ $paginator->url($lastPage) }}" class="inline-flex h-10 min-w-[40px] items-center justify-center rounded-xl border border-[#2D3742] bg-[#1C2228] px-3 py-2 text-sm font-medium text-gray-300 transition duration-200 hover:border-[#2A7CFF] hover:bg-[#252C33] hover:text-white sm:min-w-[42px] sm:px-4" aria-label="Go to page {{ $lastPage }}">
                        {{ $lastPage }}
                    </a>
                @endif

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex h-10 min-w-[40px] items-center justify-center rounded-xl border border-[#2D3742] bg-[#1C2228] px-3 py-2 text-sm font-medium text-gray-300 transition duration-200 hover:border-[#2A7CFF] hover:bg-[#252C33] hover:text-white sm:min-w-[42px] sm:px-4">
                        Next
                    </a>
                @else
                    <span class="inline-flex h-10 min-w-[40px] cursor-not-allowed items-center justify-center rounded-xl border border-[#252B31] bg-[#161D23] px-3 py-2 text-sm font-medium text-gray-600 sm:min-w-[42px] sm:px-4">
                        Next
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
