@php
    $pageUrl = $data['seo']['canonical']
        ?? $data['common']['canonical']
        ?? request()->url();

    $pageTitle = $data['seo']['title_seo']
        ?? $data['common']['title_seo']
        ?? '';

    $pageDescription = $data['seo']['meta_des']
        ?? $data['common']['meta_des']
        ?? '';

    $homeUrl = rtrim(route('page.home'), '/');

    $pageSchema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => $schemaType ?? 'WebPage',
                '@id' => $pageUrl . '#webpage',
                'url' => $pageUrl,
                'name' => $pageTitle,
                'description' => $pageDescription,
                'isPartOf' => [
                    '@id' => $homeUrl . '#website',
                ],
                'about' => [
                    '@type' => 'Thing',
                    'name' => $aboutName ?? $currentName,
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
                        'name' => $sectionName,
                        'item' => $sectionUrl,
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $currentName,
                        'item' => $pageUrl,
                    ],
                ],
            ],
        ],
    ];
@endphp

<script type="application/ld+json">
    {!! json_encode(
        $pageSchema,
        JSON_UNESCAPED_UNICODE
        | JSON_UNESCAPED_SLASHES
        | JSON_HEX_TAG
        | JSON_HEX_AMP
        | JSON_HEX_APOS
        | JSON_HEX_QUOT
    ) !!}
</script>
