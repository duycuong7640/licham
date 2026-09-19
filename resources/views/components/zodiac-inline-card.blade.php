{{--
  Usage in a Laravel Blade page:

  @foreach ($article['signArticles'] as $item)
      @include('components.zodiac-inline-card', [
          'spec' => $item['image']['renderSpec'],
          'layout' => 'desktop',
      ])
  @endforeach

  The API still returns image.src as fallback/OG image, but the page UI should use this inline SVG
  to avoid 12 extra GET requests and repeated server-side SVG generation.
--}}

@php
    $props = $spec['props'] ?? [];
    $palette = $props['palette'] ?? [];
    $layoutData = ($spec['layout'][$layout ?? 'desktop'] ?? $spec['layout']['desktop']);
    $width = $layoutData['width'] ?? 1200;
    $height = $layoutData['height'] ?? 630;
    $viewBox = $layoutData['viewBox'] ?? '0 0 1200 630';
    $signKey = $props['signKey'] ?? 'song_ngu';
    $signName = $props['signName'] ?? '';
    $date = $props['date'] ?? '';
    $topic = $props['topic'] ?? 'TỬ VI 12 CUNG';
    $element = $props['element'] ?? '';
    $quality = $props['quality'] ?? '';
    $rulingPlanet = $props['rulingPlanet'] ?? '';
    $chips = $props['chips'] ?? ['Tổng quan', 'Công danh', 'Tình cảm', 'Tài chính'];
    $bg1 = $palette['bg1'] ?? '#071528';
    $bg2 = $palette['bg2'] ?? '#075985';
    $bg3 = $palette['bg3'] ?? '#7c3aed';
    $glow = $palette['glow'] ?? '#67e8f9';
    $line = $palette['line'] ?? '#cffafe';
    $ink = $palette['ink'] ?? '#164e63';
    $isMobile = ($layout ?? 'desktop') === 'mobile';
@endphp

<svg
    class="zodiac-inline-card zodiac-inline-card--{{ e($signKey) }}"
    xmlns="http://www.w3.org/2000/svg"
    width="{{ $width }}"
    height="{{ $height }}"
    viewBox="{{ $viewBox }}"
    role="img"
    aria-label="Tử vi {{ e($signName) }} ngày {{ e($date) }}"
    style="width:100%;height:auto;display:block;border-radius:8px;background:#0f172a"
>
    <defs>
        <linearGradient id="zodiac-bg-{{ e($signKey) }}-{{ e($props['cacheKey'] ?? 'v1') }}" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="{{ e($bg1) }}" />
            <stop offset="50%" stop-color="{{ e($bg2) }}" />
            <stop offset="100%" stop-color="{{ e($bg3) }}" />
        </linearGradient>
        <radialGradient id="zodiac-glow-{{ e($signKey) }}-{{ e($props['cacheKey'] ?? 'v1') }}" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stop-color="{{ e($glow) }}" stop-opacity="0.9" />
            <stop offset="70%" stop-color="{{ e($glow) }}" stop-opacity="0.2" />
            <stop offset="100%" stop-color="{{ e($glow) }}" stop-opacity="0" />
        </radialGradient>
        <filter id="zodiac-shadow-{{ e($signKey) }}-{{ e($props['cacheKey'] ?? 'v1') }}" x="-20%" y="-20%" width="140%" height="140%">
            <feDropShadow dx="0" dy="18" stdDeviation="22" flood-color="#020617" flood-opacity="0.34" />
        </filter>
    </defs>

    <rect width="{{ $width }}" height="{{ $height }}" fill="url(#zodiac-bg-{{ e($signKey) }}-{{ e($props['cacheKey'] ?? 'v1') }})" />
    <rect width="{{ $width }}" height="{{ $height }}" fill="#020617" opacity="0.12" />

    @if ($isMobile)
        <path d="M0 860C165 810 310 895 470 840C630 785 760 795 900 865V1200H0Z" fill="{{ e($ink) }}" opacity="0.22" />
        <path d="M0 535C210 490 355 540 515 595C650 640 760 625 900 568" fill="none" stroke="{{ e($line) }}" stroke-width="5" opacity="0.18" />
        <circle cx="450" cy="585" r="245" fill="#ffffff" opacity="0.12" />
        <circle cx="450" cy="585" r="190" fill="#020617" opacity="0.18" />
        <g transform="translate(270 405)" filter="url(#zodiac-shadow-{{ e($signKey) }}-{{ e($props['cacheKey'] ?? 'v1') }})">
            @include('components.zodiac-inline-motif', ['signKey' => $signKey, 'line' => $line])
        </g>
        <g transform="translate(70 96)">
            <rect x="0" y="0" width="246" height="54" rx="27" fill="#ffffff" opacity="0.12" />
            <text x="28" y="35" font-family="Arial, Helvetica, sans-serif" font-size="22" font-weight="800" fill="{{ e($line) }}" letter-spacing="2.4">{{ e($topic) }}</text>
            <text x="0" y="178" font-family="Arial, Helvetica, sans-serif" font-size="92" font-weight="900" fill="#fff">{{ e($signName) }}</text>
            <text x="0" y="246" font-family="Arial, Helvetica, sans-serif" font-size="40" font-weight="800" fill="#e5e7eb">Ngày {{ e($date) }}</text>
            <rect x="0" y="294" width="560" height="1" fill="#fff" opacity="0.34" />
            <text x="0" y="356" font-family="Arial, Helvetica, sans-serif" font-size="32" font-weight="800" fill="#fff">{{ e($element) }} • {{ e($quality) }}</text>
            <text x="0" y="406" font-family="Arial, Helvetica, sans-serif" font-size="27" font-weight="700" fill="#dbeafe">{{ e($rulingPlanet) }} quản chiếu</text>
        </g>
        <g transform="translate(212 1018)">
            @foreach ($chips as $index => $chip)
                <g transform="translate({{ ($index % 2) * 230 }} {{ intdiv($index, 2) * 70 }})">
                    <rect x="0" y="0" width="200" height="52" rx="26" fill="#ffffff" opacity="0.14" />
                    <text x="100" y="33" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="25" font-weight="800" fill="#fff">{{ e($chip) }}</text>
                </g>
            @endforeach
        </g>
    @else
        <path d="M0 486L142 390L260 466L388 342L548 490L701 385L900 507V630H0Z" fill="{{ e($ink) }}" opacity="0.2" />
        <path d="M0 548C168 500 320 588 475 538C635 487 753 496 900 556" fill="none" stroke="{{ e($line) }}" stroke-width="5" opacity="0.16" />
        <circle cx="910" cy="410" r="260" fill="url(#zodiac-glow-{{ e($signKey) }}-{{ e($props['cacheKey'] ?? 'v1') }})" opacity="0.62" />
        <path d="M716 78C852 34 1037 66 1114 196c85 143 7 325-122 382-132 58-292 6-350-119-64-137-24-320 74-381z" fill="#ffffff" opacity="0.08" />
        <g filter="url(#zodiac-shadow-{{ e($signKey) }}-{{ e($props['cacheKey'] ?? 'v1') }})" transform="translate(746 104)">
            <circle cx="196" cy="196" r="190" fill="#ffffff" opacity="0.12" />
            <circle cx="196" cy="196" r="150" fill="#020617" opacity="0.18" />
            <g transform="translate(16 16)">
                @include('components.zodiac-inline-motif', ['signKey' => $signKey, 'line' => $line])
            </g>
        </g>
        <g transform="translate(82 92)">
            <rect x="-24" y="-42" width="214" height="46" rx="23" fill="#ffffff" opacity="0.12" />
            <text x="0" y="-12" font-family="Arial, Helvetica, sans-serif" font-size="20" font-weight="800" fill="{{ e($line) }}" letter-spacing="2.5">{{ e($topic) }}</text>
            <text x="0" y="84" font-family="Arial, Helvetica, sans-serif" font-size="80" font-weight="900" fill="#fff">{{ e($signName) }}</text>
            <text x="0" y="140" font-family="Arial, Helvetica, sans-serif" font-size="32" font-weight="800" fill="#e5e7eb">Ngày {{ e($date) }}</text>
            <rect x="0" y="178" width="496" height="1" fill="#fff" opacity="0.34" />
            <text x="0" y="228" font-family="Arial, Helvetica, sans-serif" font-size="27" font-weight="800" fill="#fff">{{ e($element) }} • {{ e($quality) }}</text>
            <text x="0" y="268" font-family="Arial, Helvetica, sans-serif" font-size="23" font-weight="700" fill="#dbeafe">{{ e($rulingPlanet) }} quản chiếu</text>
        </g>
        <g transform="translate(82 520)">
            @foreach ($chips as $index => $chip)
                <g transform="translate({{ $index * 132 }} 0)">
                    <rect x="0" y="0" width="118" height="42" rx="21" fill="#ffffff" opacity="0.14" />
                    <text x="59" y="27" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="19" font-weight="800" fill="#fff">{{ e($chip) }}</text>
                </g>
            @endforeach
        </g>
    @endif
</svg>
