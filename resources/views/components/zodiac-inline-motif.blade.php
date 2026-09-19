@php
    $stroke = '#ffffff';
    $soft = $line ?? '#cffafe';
@endphp

@switch($signKey)
    @case('bach_duong')
        <path d="M120 205c-34-34-48-77-25-105 26-31 74-9 69 35-4 34-31 55-64 68m140 2c34-34 48-77 25-105-26-31-74-9-69 35 4 34 31 55 64 68" fill="none" stroke="{{ $stroke }}" stroke-width="16" stroke-linecap="round"/>
        <path d="M180 142c-34 26-55 57-55 93 0 48 25 78 55 78s55-30 55-78c0-36-21-67-55-93z" fill="{{ e($soft) }}" opacity="0.92"/>
        @break

    @case('kim_nguu')
        <path d="M84 139C34 94 39 43 75 31c45-15 86 42 102 104M276 139c50-45 45-96 9-108-45-15-86 42-102 104" fill="none" stroke="{{ $stroke }}" stroke-width="18" stroke-linecap="round"/>
        <path d="M103 186c10-56 42-92 77-92s67 36 77 92c22 16 36 42 36 73 0 61-49 101-113 101S67 320 67 259c0-31 14-57 36-73z" fill="{{ e($soft) }}" opacity="0.94"/>
        <circle cx="141" cy="229" r="9" fill="#fff"/><circle cx="219" cy="229" r="9" fill="#fff"/>
        <ellipse cx="180" cy="284" rx="53" ry="34" fill="#fff" opacity="0.72"/>
        @break

    @case('song_tu')
        <rect x="103" y="88" width="154" height="224" rx="34" fill="{{ e($soft) }}" opacity="0.88"/>
        <path d="M130 111c31 16 69 16 100 0M130 289c31-16 69-16 100 0M145 125v150M215 125v150" stroke="#fff" stroke-width="17" stroke-linecap="round"/>
        @break

    @case('cu_giai')
        <ellipse cx="176" cy="207" rx="98" ry="76" fill="{{ e($soft) }}" opacity="0.92"/>
        <path d="M93 177c-39-27-53-57-35-77 24-27 70 10 53 55M267 177c39-27 53-57 35-77-24-27-70 10-53 55" fill="none" stroke="#fff" stroke-width="14" stroke-linecap="round"/>
        <circle cx="140" cy="178" r="12" fill="#fff"/><circle cx="212" cy="178" r="12" fill="#fff"/>
        @break

    @case('su_tu')
        <circle cx="180" cy="186" r="111" fill="{{ e($soft) }}" opacity="0.9"/>
        <path d="M87 184c24-68 59-105 93-105s69 37 93 105c-29-25-58-37-93-37s-64 12-93 37z" fill="#fff" opacity="0.2"/>
        <circle cx="143" cy="178" r="10" fill="#fff"/><circle cx="217" cy="178" r="10" fill="#fff"/>
        @break

    @case('xu_nu')
        <path d="M181 68c39 38 73 78 73 137 0 64-32 108-73 126-41-18-73-62-73-126 0-59 34-99 73-137z" fill="{{ e($soft) }}" opacity="0.9"/>
        <path d="M180 94v204M132 160c24 14 72 14 96 0M132 218c24 14 72 14 96 0" stroke="#fff" stroke-width="13" stroke-linecap="round"/>
        @break

    @case('thien_binh')
        <path d="M82 236h196M112 278h136M180 81v183" stroke="#fff" stroke-width="15" stroke-linecap="round"/>
        <path d="M103 140h154" stroke="{{ e($soft) }}" stroke-width="17" stroke-linecap="round"/>
        <path d="M130 140l-42 83h84l-42-83zM230 140l-42 83h84l-42-83z" fill="{{ e($soft) }}" opacity="0.86"/>
        @break

    @case('ho_cap')
        <path d="M91 247c34-95 86-139 148-129 46 8 74 47 66 88-10 55-79 64-106 21-19-31 9-61 38-48" fill="none" stroke="{{ e($soft) }}" stroke-width="30" stroke-linecap="round"/>
        <path d="M91 247c34-95 86-139 148-129 46 8 74 47 66 88-10 55-79 64-106 21-19-31 9-61 38-48" fill="none" stroke="#fff" stroke-width="13" stroke-linecap="round"/>
        <path d="M81 255l-38-10 21 33z" fill="#fff"/>
        @break

    @case('nhan_ma')
        <path d="M91 280L267 104" stroke="{{ e($soft) }}" stroke-width="32" stroke-linecap="round"/>
        <path d="M91 280L267 104" stroke="#fff" stroke-width="13" stroke-linecap="round"/>
        <path d="M213 96h65v65M160 155l66 66" stroke="#fff" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
        @break

    @case('ma_ket')
        <path d="M99 202c23-58 55-91 96-91 49 0 82 42 82 91 0 66-52 102-112 102-42 0-72-19-72-50 0-25 24-42 49-32 24 10 27 41 6 55" fill="none" stroke="{{ e($soft) }}" stroke-width="30" stroke-linecap="round"/>
        <path d="M99 202c23-58 55-91 96-91 49 0 82 42 82 91 0 66-52 102-112 102-42 0-72-19-72-50 0-25 24-42 49-32 24 10 27 41 6 55" fill="none" stroke="#fff" stroke-width="13" stroke-linecap="round"/>
        @break

    @case('bao_binh')
        <path d="M74 151c26-27 52-27 78 0s52 27 78 0 52-27 78 0M74 215c26-27 52-27 78 0s52 27 78 0 52-27 78 0M74 279c26-27 52-27 78 0s52 27 78 0 52-27 78 0" fill="none" stroke="#fff" stroke-width="16" stroke-linecap="round"/>
        <circle cx="180" cy="215" r="66" fill="{{ e($soft) }}" opacity="0.17"/>
        @break

    @case('song_ngu')
        <g transform="translate(95 118)"><path d="M34 64c39-42 104-42 151 0-47 42-112 42-151 0z" fill="{{ e($soft) }}" opacity="0.94"/><path d="M34 64L0 34v60zM185 64l45-31v62z" fill="#fff" opacity="0.86"/><circle cx="76" cy="55" r="8" fill="#fff"/></g>
        <g transform="translate(266 249) rotate(180)"><g transform="translate(10 -70)"><path d="M34 64c39-42 104-42 151 0-47 42-112 42-151 0z" fill="{{ e($soft) }}" opacity="0.84"/><path d="M34 64L0 34v60zM185 64l45-31v62z" fill="#fff" opacity="0.74"/><circle cx="76" cy="55" r="8" fill="#fff"/></g></g>
        <path d="M118 265c46-68 106-100 181-116" stroke="#fff" stroke-width="12" stroke-linecap="round" opacity="0.72"/>
        @break
@endswitch
