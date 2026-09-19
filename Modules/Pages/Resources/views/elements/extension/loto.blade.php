@php
    $loto = \App\Helpers\Helpers::buildLoto($row['options']);
@endphp
<div class="panel-loto shadow">
    <div class="panel-h gray-h">
        LOTO {{ $cate_title }} {{ $row['thu'] }} {{ \App\Helpers\Helpers::formatDate($row['day']) }}
    </div>

    <div class="loto-container">
        <div class="loto-box">
            <div class="loto-header">
                <div>Đầu</div>
                <div>Loto</div>
            </div>
            @for($i = 0; $i <= 9; $i++)
                @php
                    $items = $loto['dau'][$i] ?? [];
                @endphp

                <div class="loto-row {{ in_array($loto['db'], $items) ? 'hl-row' : '' }}">
                    <div class="loto-col head">{{ $i }}</div>
                    <div class="loto-col values">
                        @if(!empty($items))
                            @foreach($items as $item)
                                <span class="{{ $item['is_db'] ? 'red-text' : '' }}">
                                            {{ $item['value'] }}
                                        </span>@if(!$loop->last), @endif
                            @endforeach
                        @else
                            <span>-</span>
                        @endif
                    </div>
                </div>
            @endfor
        </div>
        <div class="loto-box loto-box-duoi">
            <div class="loto-header">
                <div>Đuôi</div>
                <div>Loto</div>
            </div>
            @for($i = 0; $i <= 9; $i++)
                @php
                    $items = $loto['duoi'][$i] ?? [];
                @endphp
                <div class="loto-row {{ in_array($loto['db'], $items) ? 'hl-row' : '' }}">
                    <div class="loto-col head">{{ $i }}</div>
                    <div class="loto-col values">
                        @if(!empty($items))
                            @foreach($items as $item)
                                <span class="{{ $item['is_db'] ? 'red-text' : '' }}">
                                            {{ $item['value'] }}
                                        </span>@if(!$loop->last), @endif
                            @endforeach
                        @else
                            <span>-</span>
                        @endif
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>
