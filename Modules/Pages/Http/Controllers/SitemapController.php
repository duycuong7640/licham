<?php

namespace Modules\Pages\Http\Controllers;

use App\Helpers\Helpers;
use App\Helpers\RequestHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SitemapController extends Controller
{
    public function generate(Request $request)
    {
        $xmlPath = public_path('xml');
        File::ensureDirectoryExists($xmlPath, 0755, true);

        // Mảng lưu trữ tất cả các file sitemap thành phần được tạo ra thực tế
        $generatedFiles = [];

        // 1. Tạo sitemap menu
        $this->generateMenu();
        $generatedFiles[] = public_path('xml/sitemap-menu.xml');

        // 2. Tạo sitemap posts và lấy danh sách file post đã tạo
//        $postFiles = $this->generatePosts($request);
//        $generatedFiles = array_merge($generatedFiles, $postFiles);

        // 3. Tạo file sitemap index tổng từ danh sách file thực tế
        $this->generateSitemapIndex($generatedFiles);

        return response()->json([
            'success' => true
        ]);
    }

    private function generateMenu()
    {
        $menu = \dataMenu::menus();
        $urls = [];

        foreach ($menu as $k => $row) {
            if ($row['level'] != 1) {
                continue;
            }

            if (empty($row['slug'])) {
                $urls[] = [
                    'loc' => route('page.home'),
                    'lastmod' => now()->toAtomString(),
                ];
            } else {
                $slug = $row['slug'];

                $urls[] = [
                    'loc' => route('page.cate.index', ['slug' => $slug]),
                    'lastmod' => now()->toAtomString(),
                ];
            }

            foreach ($menu as $child) {
                if ($child['level'] == 2 && $child['parent'] == $k) {
                    $urls[] = [
                        'loc' => route('page.cate.index', [
                            'slug' => $child['slug'],
                        ]),
                        'lastmod' => now()->toAtomString(),
                    ];
                }
            }
        }

        File::put(
            public_path('xml/sitemap-menu.xml'),
            $this->renderUrlSet($urls)
        );
    }

    private function generatePosts(Request $request)
    {
        $postFiles = [];
        $firstPage = $this->getPosts($request, 1);
        $lastPage = $firstPage['last_page'] ?? 1;

        /*
         |--------------------------------------------------------------------------
         | Page 1 luôn tạo mới (Sitemap bài viết mới nhất)
         |--------------------------------------------------------------------------
         */
        $latestUrls = [];
        if (!empty($firstPage['data'])) {
            foreach ($firstPage['data'] as $post) {
                $latestUrls[] = [
                    'loc' => route('page.post.show', ['slug' => $post['slug']]),
                    'lastmod' => $post['created_at']
                ];
            }
        }

        $latestFile = public_path('xml/sitemap-post-latest.v1.xml');
        File::put($latestFile, $this->renderPostUrlSet($latestUrls));
        $postFiles[] = $latestFile;

        /*
         |--------------------------------------------------------------------------
         | Các Page từ 2 trở đi (Chỉ tạo nếu chưa có file để giảm tải cho Server)
         |--------------------------------------------------------------------------
         */
        for ($page = 2; $page <= $lastPage; $page++) {
            $file = public_path("xml/sitemap-post-{$page}.xml");
            $postFiles[] = $file;

            if (file_exists($file)) {
                continue;
            }

            $posts = $this->getPosts($request, $page);
            $urls = [];

            if (!empty($posts['data'])) {
                foreach ($posts['data'] as $post) {
                    $urls[] = [
                        'loc' => route('page.post.show', ['slug' => $post['slug']]),
                        'lastmod' => $post['created_at']
                    ];
                }
            }

            File::put($file, $this->renderPostUrlSet($urls));
        }

        return $postFiles;
    }

    private function generateSitemapIndex(array $files)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($files as $filePath) {
            if (!file_exists($filePath)) {
                continue;
            }

            $name = basename($filePath);
            $fileMtime = filemtime($filePath);

            $xml .= '    <sitemap>' . PHP_EOL;
            $xml .= '        <loc>' . htmlspecialchars(url('xml/' . $name), ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
            $xml .= '        <lastmod>' . date('c', $fileMtime) . '</lastmod>' . PHP_EOL;
            $xml .= '    </sitemap>' . PHP_EOL;
        }

        $xml .= '</sitemapindex>';

        File::put(public_path('xml/sitemap.xml'), $xml);
    }

    private function renderUrlSet(array $urls)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $row) {
            if (empty($row['loc']) || empty($row['lastmod'])) {
                continue;
            }

            $xml .= '    <url>' . PHP_EOL;
            $xml .= '        <loc>' . htmlspecialchars($row['loc'], ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
            $xml .= '        <lastmod>' . date('c', strtotime($row['lastmod'])) . '</lastmod>' . PHP_EOL;
            $xml .= '    </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }

    private function renderPostUrlSet(array $urls)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $row) {
            if (empty($row['loc']) || empty($row['lastmod'])) {
                continue;
            }

            $timestamp = is_numeric($row['lastmod']) ? $row['lastmod'] : strtotime($row['lastmod']);
            $lastmod = $timestamp ? date('c', $timestamp) : date('c');

            $xml .= '    <url>' . PHP_EOL;
            $xml .= '        <loc>' . htmlspecialchars($row['loc'], ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
            $xml .= '        <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
            $xml .= '    </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }

    private function getPosts(Request $request, $page = 1)
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
                'page' => $page
            ],
            'post'
        );
    }
}
