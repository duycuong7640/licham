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

    $homeUrl = rtrim(route('page.home'), '/');

    $currentPage = max(
        1,
        (int) data_get($data, 'lists.current_page', request('page', 1))
    );

    $perPage = max(
        1,
        (int) data_get($data, 'lists.per_page', 12)
    );

    $itemListElements = [];

    foreach (data_get($data, 'lists.data', []) as $index => $post) {
        if (empty($post['slug']) || empty($post['title'])) {
            continue;
        }

        $itemListElements[] = [
            '@type' => 'ListItem',
            'position' => (($currentPage - 1) * $perPage) + $index + 1,
            'item' => [
                '@type' => 'WebPage',
                '@id' => route('page.post.show', [
                    'slug' => $post['slug'],
                ]),
                'url' => route('page.post.show', [
                    'slug' => $post['slug'],
                ]),
                'name' => strip_tags($post['title']),
            ],
        ];
    }

    $breadcrumbItems = [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Trang chủ',
            'item' => $homeUrl,
        ],
    ];

    $breadcrumbPosition = 2;

    if (!empty($data['categoryParent']['title'])
        && !empty($data['categoryParent']['slug'])) {
        $breadcrumbItems[] = [
            '@type' => 'ListItem',
            'position' => $breadcrumbPosition++,
            'name' => $data['categoryParent']['title'],
            'item' => route('page.cate.index', [
                'slug' => $data['categoryParent']['slug'],
            ]),
        ];
    }

    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => $breadcrumbPosition,
        'name' => $data['category']['title'],
        'item' => $pageUrl,
    ];

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'CollectionPage',
                '@id' => $pageUrl . '#webpage',
                'url' => $pageUrl,
                'name' => $pageTitle,
                'headline' => $data['category']['h1']
                    ?? $data['category']['title'],
                'description' => $pageDescription,
                'isPartOf' => [
                    '@id' => $homeUrl . '#website',
                ],
                'breadcrumb' => [
                    '@id' => $pageUrl . '#breadcrumb',
                ],
                'mainEntity' => [
                    '@type' => 'ItemList',
                    '@id' => $pageUrl . '#itemlist',
                    'name' => 'Bài viết ' . $data['category']['title'],
                    'itemListOrder' => 'https://schema.org/ItemListOrderDescending',
                    'itemListElement' => $itemListElements,
                ],
                'inLanguage' => 'vi-VN',
            ],
            [
                '@type' => 'BreadcrumbList',
                '@id' => $pageUrl . '#breadcrumb',
                'itemListElement' => $breadcrumbItems,
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
