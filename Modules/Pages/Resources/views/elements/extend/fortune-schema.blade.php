@php
    $pageUrl = $data['seo']['canonical']
        ?? $data['common']['canonical']
        ?? request()->url();

    $pageTitle = $data['seo']['title_seo']
        ?? $data['common']['title_seo']
        ?? $data['category']['title'];

    $pageDescription = $data['seo']['meta_des']
        ?? $data['common']['meta_des']
        ?? '';

    $pageName = $data['category']['h1']
        ?? $data['category']['title']
        ?? $pageTitle;

    $homeUrl = rtrim(route('page.home'), '/');
    $fortuneUrl = url('/boi-vui');

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebPage',
                '@id' => $pageUrl . '#webpage',
                'url' => $pageUrl,
                'name' => $pageTitle,
                'headline' => $pageName,
                'description' => $pageDescription,
                'isPartOf' => [
                    '@id' => $homeUrl . '#website',
                ],
                'about' => [
                    '@type' => 'Thing',
                    'name' => $data['category']['title'],
                ],
                'breadcrumb' => [
                    '@id' => $pageUrl . '#breadcrumb',
                ],
                'inLanguage' => 'vi-VN',
            ],
            [
                '@type' => 'BreadcrumbList',
                '@id' => $pageUrl . '#breadcrumb',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Trang chủ',
                        'item' => $homeUrl,
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Bói vui',
                        'item' => $fortuneUrl,
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $data['category']['title'],
                        'item' => $pageUrl,
                    ],
                ],
            ],
        ],
    ];
@endphp

<script type="application/ld+json">
    {!! json_encode(
        $schema,
        JSON_UNESCAPED_UNICODE
        | JSON_UNESCAPED_SLASHES
        | JSON_HEX_TAG
        | JSON_HEX_AMP
        | JSON_HEX_APOS
        | JSON_HEX_QUOT
    ) !!}
</script>
