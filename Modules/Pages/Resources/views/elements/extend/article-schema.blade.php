@php
    $article = $data['detail'];

    $pageUrl = $data['seo']['canonical']
        ?? $data['common']['canonical']
        ?? request()->url();

    $headline = strip_tags($article['title'] ?? '');

    $description = $data['seo']['meta_des']
        ?? $data['common']['meta_des']
        ?? '';

    $homeUrl = rtrim(route('page.home'), '/');

    $imageUrl = !empty($article['thumbnail'])
        ? \App\Helpers\Helpers::renderThumb($article['thumbnail'])
        : ($data['seo']['logo_share'] ?? '');

    $publishedAt = $article['published_at']
        ?? $article['created_at']
        ?? null;

    $modifiedAt = $article['updated_at']
        ?? $publishedAt;

    $articleNode = [
        '@type' => 'Article',
        '@id' => $pageUrl . '#article',
        'url' => $pageUrl,
        'mainEntityOfPage' => [
            '@id' => $pageUrl . '#webpage',
        ],
        'headline' => $headline,
        'description' => $description,
        'articleSection' => $data['category']['title'] ?? '',
        'inLanguage' => 'vi-VN',
        'publisher' => [
            '@id' => $homeUrl . '#organization',
        ],
    ];

    if (!empty($imageUrl)) {
        $articleNode['image'] = [
            '@type' => 'ImageObject',
            'url' => $imageUrl,
        ];
    }

    if (!empty($publishedAt)) {
        $articleNode['datePublished'] =
            \Carbon\Carbon::parse($publishedAt)->toIso8601String();
    }

    if (!empty($modifiedAt)) {
        $articleNode['dateModified'] =
            \Carbon\Carbon::parse($modifiedAt)->toIso8601String();
    }

    /*
     * Chỉ dùng tên tác giả thật.
     * Nếu bài chưa có tác giả thì sử dụng tổ chức xuất bản.
     */
    if (!empty($article['author']['name'])) {
        $articleNode['author'] = [
            '@type' => 'Person',
            'name' => $article['author']['name'],
        ];
    } else {
        $articleNode['author'] = [
            '@id' => $homeUrl . '#organization',
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

    $position = 2;

    if (!empty($data['categoryParent']['title'])
        && !empty($data['categoryParent']['slug'])) {
        $breadcrumbItems[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => $data['categoryParent']['title'],
            'item' => route('page.cate.index', [
                'slug' => $data['categoryParent']['slug'],
            ]),
        ];
    }

    if (!empty($data['category']['title'])
        && !empty($data['category']['slug'])) {
        $breadcrumbItems[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => $data['category']['title'],
            'item' => route('page.cate.index', [
                'slug' => $data['category']['slug'],
            ]),
        ];
    }

    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => $position,
        'name' => $headline,
        'item' => $pageUrl,
    ];

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebPage',
                '@id' => $pageUrl . '#webpage',
                'url' => $pageUrl,
                'name' => $headline,
                'description' => $description,
                'isPartOf' => [
                    '@id' => $homeUrl . '#website',
                ],
                'breadcrumb' => [
                    '@id' => $pageUrl . '#breadcrumb',
                ],
                'mainEntity' => [
                    '@id' => $pageUrl . '#article',
                ],
                'inLanguage' => 'vi-VN',
            ],

            $articleNode,

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
