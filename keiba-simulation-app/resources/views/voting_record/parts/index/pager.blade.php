@if ($totalPages > 1)
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            {{-- 前へリンク --}}
            @if ($currentPage > 1)
                <li class="page-item">
                    <a class="page-link" 
                        href="?page={{ $currentPage - 1 }}&page_size={{ request('page_size', $pageSize) }}&race_date={{ $raceDate }}&racecourse={{ $racecourse }}&race_num={{ $raceNum }}"
                        aria-label="Previous"
                    >
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">&laquo;</span>
                </li>
            @endif

            {{-- ページ番号リンク --}}
            @for ($i = 1; $i <= $totalPages; $i++)
                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                    <a class="page-link" 
                        href="?page={{ $i }}&page_size={{ request('page_size', $pageSize) }}&race_date={{ $raceDate }}&racecourse={{ $racecourse }}&race_num={{ $raceNum }}"
                        >{{ $i }}
                    </a>
                </li>
            @endfor

            {{-- 次へリンク --}}
            @if ($currentPage < $totalPages)
                <li class="page-item">
                    <a class="page-link"
                        href="?page={{ $currentPage + 1 }}&page_size={{ request('page_size', $pageSize) }}&race_date={{ $raceDate }}&racecourse={{ $racecourse }}&race_num={{ $raceNum }}" 
                        aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif