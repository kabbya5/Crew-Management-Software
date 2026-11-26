
@props([
    'data' => null,
])

@if ($data && $data->hasPages())

    <div class="custom-pagination">

        {{-- Pagination Information --}}
        <div class="pagination-info">
            Showing
            <strong>{{ $data->firstItem() ?? 0 }}</strong>
            to
            <strong>{{ $data->lastItem() ?? 0 }}</strong>
            of
            <strong>{{ $data->total() }}</strong>
            records
        </div>

        {{-- Pagination Buttons --}}
        <div class="pagination-buttons">

            {{-- Previous --}}
            @if ($data->onFirstPage())
                <span class="page-btn disabled">
                    &laquo; Previous
                </span>
            @else
                <a href="{{ $data->previousPageUrl() }}" class="page-btn">
                    &laquo; Previous
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($data->getUrlRange(
                max(1, $data->currentPage() - 2),
                min($data->lastPage(), $data->currentPage() + 2)
            ) as $page => $url)

                @if ($page == $data->currentPage())
                    <span class="page-btn active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">
                        {{ $page }}
                    </a>
                @endif

            @endforeach

            {{-- Next --}}
            @if ($data->hasMorePages())
                <a href="{{ $data->nextPageUrl() }}" class="page-btn">
                    Next &raquo;
                </a>
            @else
                <span class="page-btn disabled">
                    Next &raquo;
                </span>
            @endif

        </div>
    </div>

@endif
