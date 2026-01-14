@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="pagination-nav">
        <ul class="pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="pagination-item disabled">
                    <span class="pagination-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </span>
                </li>
            @else
                <li class="pagination-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link" rel="prev">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="pagination-item disabled">
                        <span class="pagination-link dots">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pagination-item active">
                                <span class="pagination-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="pagination-item">
                                <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="pagination-item">
                    <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link" rel="next">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                </li>
            @else
                <li class="pagination-item disabled">
                    <span class="pagination-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    <style>
    .pagination-nav {
        display: flex;
        justify-content: center;
        padding: 24px 0;
    }

    .pagination-list {
        display: flex;
        align-items: center;
        gap: 6px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .pagination-item {
        margin: 0;
    }

    .pagination-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 12px;
        border-radius: 10px;
        border: 1.5px solid var(--border, #e2e8f0);
        background: #fff;
        color: var(--text, #1e293b);
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .pagination-link:hover {
        background: var(--primary-bg, #f0f9ff);
        border-color: var(--primary, #0369a1);
        color: var(--primary, #0369a1);
    }

    .pagination-item.active .pagination-link {
        background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
        border-color: #0369a1;
        color: #fff;
        box-shadow: 0 2px 8px rgba(3, 105, 161, 0.3);
    }

    .pagination-item.disabled .pagination-link {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }

    .pagination-link.dots {
        border: none;
        background: transparent;
        min-width: 30px;
        padding: 0;
        color: var(--muted, #64748b);
    }

    .pagination-link.dots:hover {
        background: transparent;
        color: var(--muted, #64748b);
    }

    .pagination-link svg {
        flex-shrink: 0;
    }
    </style>
@endif
