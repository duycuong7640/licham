@if(!empty($data['lists']['data']))
    @php
        $currentPage = $data['lists']['current_page'];
        $totalPages = $data['lists']['last_page'];
    @endphp
    @if($totalPages > 1)
        <div class="pagination-wrap mt-25">
            <nav aria-label="Page navigation example">
                <ul class="pagination list-wrap">

                    {{-- Prev --}}
                    @if($currentPage > 1)
                        <li class="page-item">
                            <a class="page-link"
                               href="{{ ($currentPage - 1) == 1
                                ? request()->url()
                                : request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}">
                                «
                            </a>
                            @push('paginate')
                                <link rel="prev" href="{{ ($currentPage - 1) == 1
                                ? request()->url()
                                : request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}"/>
                            @endpush
                        </li>
                    @endif

                    <?php
                    $range = 2;
                    $showDots = false;
                    ?>

                    @for($i = 1; $i <= $totalPages; $i++)
                        @if(
                            $i == 1 || $i == $totalPages ||
                            ($i >= $currentPage - $range && $i <= $currentPage + $range)
                        )
                            <?php $showDots = false; ?>
                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                <a class="page-link"
                                   href="{{ $i == 1
                                    ? request()->url()
                                    : request()->fullUrlWithQuery(['page' => $i]) }}">
                                    {{ $i }}
                                </a>
                            </li>
                        @else
                            @if(!$showDots)
                                <?php $showDots = true; ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                        @endif
                    @endfor

                    {{-- Next --}}
                    @if($currentPage < $totalPages)
                        <li class="page-item">
                            <a class="page-link"
                               href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}">
                                »
                            </a>
                            @push('paginate')
                                <link rel="next"
                                      href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}"/>
                            @endpush
                        </li>
                    @endif

                </ul>
            </nav>
        </div>
    @endif
@endif
