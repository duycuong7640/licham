<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ !empty($config['setting']['title']) ? $config['setting']['title'] : 'Tên website của tôi' }}</title>
        <link>{{ route('page.home') }}</link>
        <description>{{ !empty($config['setting']['metaDes']) ? $config['setting']['metaDes'] : 'Mô tả ngắn về website' }}</description>
        <language>vi</language>
        <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
        <atom:link href="{{ route('rss.feed') }}" rel="self" type="application/rss+xml" />

        @foreach($posts as $row)
            @php
                $title = \App\Helpers\Helpers::renderTitle($row);
                $url = route('page.post.show', ['slug' => $row['slug']]);
                $thumb = \App\Helpers\Helpers::renderThumb($row['thumbnail']);
                $des = $row['description'];
            @endphp
            <item>
                <title>{{ $title }}</title>
                <link>{{ $url }}</link>
                <description><![CDATA[{!! $des !!}]]></description>
                <pubDate>{{ \Carbon\Carbon::parse($row['created_at'])->toRssString() }}</pubDate>
                <guid isPermaLink="false">{{ $row['id'] }}</guid>
            </item>
        @endforeach
    </channel>
</rss>
