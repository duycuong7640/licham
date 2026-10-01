<?php

namespace App\Console\Commands;

use App\Helpers\Helpers;
use App\Helpers\RequestHelpers;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    /**
     * Chạy:
     * php artisan sitemap:generate
     */
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate sitemap.xml and sitemap child files';

    public function handle(): int
    {
        $this->info('Đang tạo sitemap...');

        $xmlPath = public_path('/');

        File::ensureDirectoryExists($xmlPath, 0755, true);

        /*
        |--------------------------------------------------------------------------
        | Danh sách sitemap thành phần
        |--------------------------------------------------------------------------
        */
        $generatedFiles = [];

        /*
        |--------------------------------------------------------------------------
        | 1. Sitemap menu / static / month / year
        |--------------------------------------------------------------------------
        */
        $this->generateMenu();

        $generatedFiles[] = public_path('sitemap-menu.xml');

        $this->info('✓ sitemap-menu.xml');

        /*
        |--------------------------------------------------------------------------
        | 1. Sitemap day
        |--------------------------------------------------------------------------
        */
        $dayFiles = $this->generateDay();
        $generatedFiles = array_merge($generatedFiles, $dayFiles);
        foreach ($dayFiles as $file) {
            $this->info('✓ ' . basename($file));
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Sitemap bài viết
        |--------------------------------------------------------------------------
        */

        // Nếu muốn bật sitemap post thì bỏ comment đoạn này.
        //
        // $request = Request::create('/', 'GET');
        //
        // $postFiles = $this->generatePosts($request);
        // $generatedFiles = array_merge($generatedFiles, $postFiles);

        /*
        |--------------------------------------------------------------------------
        | 3. Sitemap index
        |--------------------------------------------------------------------------
        */
        $this->generateSitemapIndex($generatedFiles);

        $this->info('✓ sitemap.xml');
        $this->newLine();
        $this->info('Tạo sitemap thành công!');

        return self::SUCCESS;
    }

    /**
     * Sitemap chứa:
     * - Trang chủ
     * - Trang đổi ngày âm dương
     * - Trang bài viết
     * - Lịch tháng
     * - Lịch năm
     */
    private function generateMenu(): void
    {
        $urls = [];

        $now = now()->toAtomString();

        /*
        |--------------------------------------------------------------------------
        | Trang cố định
        |--------------------------------------------------------------------------
        */

        $urls[] = [
            'loc' => route('page.home'),
//            'lastmod' => $now,
        ];

        $urls[] = [
            'loc' => route('page.cate.index', [
                'slug' => 'doi-ngay-am-duong',
            ]),
//            'lastmod' => $now,
        ];

        $urls[] = [
            'loc' => route('page.cate.index', [
                'slug' => 'bai-viet',
            ]),
//            'lastmod' => $now,
        ];

        /*
        |--------------------------------------------------------------------------
        | Sitemap tháng trong năm hiện tại
        |--------------------------------------------------------------------------
        */

        $currentYear = (int) date('Y');
        $startYear = $currentYear - 10;
        for ($year = $startYear; $year <= 2050; $year++) {
            for ($month = 1; $month <= 12; $month++) {
                $urls[] = [
                    'loc' => route('page.cope.show.month', [
                        'month' => $month,
                        'year' => $currentYear,
                    ]),
//                    'lastmod' => $now,
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sitemap lịch năm
        |
        | Từ 15 năm trước đến 2050
        |--------------------------------------------------------------------------
        */

        $startYear = $currentYear - 10;

        for ($year = $startYear; $year <= 2050; $year++) {
            $urls[] = [
                'loc' => route('page.cope.show.year', [
                    'year' => $year,
                ]),
//                'lastmod' => $now,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Ghi sitemap-menu.xml
        |--------------------------------------------------------------------------
        */

        File::put(
            public_path('sitemap-menu.xml'),
            $this->renderUrlSet($urls)
        );
    }

    /**
     * Sitemap day
     *
     * Từ 01/01/2020 đến 31/12/2050
     * Mỗi file tối đa 2.000 URL
     */
    private function generateDay(): array
    {
        $generatedFiles = [];

        $startDate = Carbon::create(2026, 1, 1)->startOfDay();
        $endDate = Carbon::create(2050, 12, 31)->startOfDay();

        $limitPerFile = 2000;

        $urls = [];
        $fileIndex = 1;

        $now = now()->toAtomString();

        $date = $startDate->copy();

        while ($date->lte($endDate)) {

            $urls[] = [
                'loc' => route('page.cope.show.day', [
                    'day' => $date->day,
                    'month' => $date->month,
                    'year' => $date->year,
                ]),
//                'lastmod' => $now,
            ];

            /*
            |--------------------------------------------------------------------------
            | Đủ 2.000 URL → ghi file
            |--------------------------------------------------------------------------
            */

            if (count($urls) >= $limitPerFile) {

                $fileName = "sitemap-day-{$fileIndex}.xml";

                $filePath = public_path($fileName);

                File::put(
                    $filePath,
                    $this->renderUrlSet($urls)
                );

                $generatedFiles[] = $filePath;

                // Reset
                $urls = [];
                $fileIndex++;
            }

            $date->addDay();
        }

        /*
        |--------------------------------------------------------------------------
        | Ghi phần URL còn lại
        |--------------------------------------------------------------------------
        */

        if (!empty($urls)) {

            $fileName = "sitemap-day-{$fileIndex}.xml";

            $filePath = public_path($fileName);

            File::put(
                $filePath,
                $this->renderUrlSet($urls)
            );

            $generatedFiles[] = $filePath;
        }

        return $generatedFiles;
    }

    /**
     * Sinh sitemap bài viết.
     *
     * Page 1 luôn regenerate.
     * Page >= 2 chỉ generate nếu file chưa tồn tại.
     */
    private function generatePosts(Request $request): array
    {
        $postFiles = [];

        $firstPage = $this->getPosts($request, 1);

        $lastPage = $firstPage['last_page'] ?? 1;

        /*
        |--------------------------------------------------------------------------
        | Page 1 - các bài mới nhất
        |--------------------------------------------------------------------------
        */

        $latestUrls = [];

        if (!empty($firstPage['data'])) {
            foreach ($firstPage['data'] as $post) {
                $latestUrls[] = [
                    'loc' => route('page.post.show', [
                        'slug' => $post['slug'],
                    ]),
                    'lastmod' => $post['created_at'],
                ];
            }
        }

        $latestFile = public_path('sitemap-post-latest.v1.xml');

        File::put(
            $latestFile,
            $this->renderPostUrlSet($latestUrls)
        );

        $postFiles[] = $latestFile;

        $this->info('✓ sitemap-post-latest.v1.xml');

        /*
        |--------------------------------------------------------------------------
        | Page 2 trở đi
        |--------------------------------------------------------------------------
        */

        for ($page = 2; $page <= $lastPage; $page++) {
            $file = public_path("sitemap-post-{$page}.xml");

            $postFiles[] = $file;

            /*
             * File cũ đã có thì không regenerate
             */
            if (File::exists($file)) {
                continue;
            }

            $posts = $this->getPosts($request, $page);

            $urls = [];

            if (!empty($posts['data'])) {
                foreach ($posts['data'] as $post) {
                    $urls[] = [
                        'loc' => route('page.post.show', [
                            'slug' => $post['slug'],
                        ]),
                        'lastmod' => $post['created_at'],
                    ];
                }
            }

            File::put(
                $file,
                $this->renderPostUrlSet($urls)
            );

            $this->info("✓ sitemap-post-{$page}.xml");
        }

        return $postFiles;
    }

    /**
     * Generate sitemap index.
     */
    private function generateSitemapIndex(array $files): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;

        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
            . PHP_EOL;

        foreach ($files as $filePath) {
            if (!File::exists($filePath)) {
                continue;
            }

            $name = basename($filePath);

            $fileMtime = File::lastModified($filePath);

            $xml .= '    <sitemap>' . PHP_EOL;

            $xml .= '        <loc>'
                . htmlspecialchars(
                    url($name),
                    ENT_XML1,
                    'UTF-8'
                )
                . '</loc>'
                . PHP_EOL;

//            $xml .= '        <lastmod>'
//                . date('c', $fileMtime)
//                . '</lastmod>'
//                . PHP_EOL;

            $xml .= '    </sitemap>' . PHP_EOL;
        }

        $xml .= '</sitemapindex>';

        File::put(
            public_path('sitemap.xml'),
            $xml
        );
    }

    /**
     * Render URL sitemap.
     */
    private function renderUrlSet(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;

        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
            . PHP_EOL;

        foreach ($urls as $row) {
            if (empty($row['loc'])) {
                continue;
            }

            $xml .= '    <url>' . PHP_EOL;

            $xml .= '        <loc>'
                . htmlspecialchars(
                    $row['loc'],
                    ENT_XML1,
                    'UTF-8'
                )
                . '</loc>'
                . PHP_EOL;

//            if (!empty($row['lastmod'])) {
//                $timestamp = strtotime($row['lastmod']);
//
//                if ($timestamp) {
//                    $xml .= '        <lastmod>'
//                        . date('c', $timestamp)
//                        . '</lastmod>'
//                        . PHP_EOL;
//                }
//            }

            $xml .= '    </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Render post sitemap.
     */
    private function renderPostUrlSet(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;

        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
            . PHP_EOL;

        foreach ($urls as $row) {
            if (empty($row['loc'])) {
                continue;
            }

            $xml .= '    <url>' . PHP_EOL;

            $xml .= '        <loc>'
                . htmlspecialchars(
                    $row['loc'],
                    ENT_XML1,
                    'UTF-8'
                )
                . '</loc>'
                . PHP_EOL;

            if (!empty($row['lastmod'])) {
                $timestamp = is_numeric($row['lastmod'])
                    ? $row['lastmod']
                    : strtotime($row['lastmod']);

                if ($timestamp) {
                    $xml .= '        <lastmod>'
                        . date('c', $timestamp)
                        . '</lastmod>'
                        . PHP_EOL;
                }
            }

            $xml .= '    </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Lấy bài viết từ API.
     */
    private function getPosts(Request $request, int $page = 1): array
    {
        $slug = 'tin-xo-so';

        $category = Helpers::findBySlug($slug, '');

        return RequestHelpers::request(
            $request,
            \dataApiRoutes::POSTS,
            \dataApiRoutes::POSTS,
            [
                'isPage' => 'sitemap',
                'keySlug' => $slug,
                'limit' => 200,
                'paginate' => 200,
                'orderField' => 'created_at',
                'orderType' => 'DESC',
                'DOMAIN_RUN' => env('DOMAIN_RUN'),
                'type' => $category['type'] ?? '',
                'page' => $page,
            ],
            'post'
        );
    }
}
