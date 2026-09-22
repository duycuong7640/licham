<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Helpers
{
    public static function pre($data = array())
    {
        echo '<pre>';
        print_r($data);
        die;
    }

    public static function shortDesc($str, $len, $charset = 'UTF-8')
    {
        $str = strip_tags($str);
        $str = html_entity_decode($str, ENT_QUOTES, $charset);
        if (mb_strlen($str, $charset) > $len) {
            $arr = explode(' ', $str);
            $str = mb_substr($str, 0, $len, $charset);
            $arrRes = explode(' ', $str);
            $last = $arr[count($arrRes) - 1];
            unset($arr);
            if (strcasecmp($arrRes[count($arrRes) - 1], $last)) {
                unset($arrRes[count($arrRes) - 1]);
            }
            return implode(' ', $arrRes) . "...";
        }
        return $str;
    }

    public static function shortDescSEO($html, $len = 160, $charset = 'UTF-8')
    {
        if (empty($html)) return '';

        libxml_use_internal_errors(true);

        $doc = new \DOMDocument();
        $doc->loadHTML('<?xml encoding="' . $charset . '">' . $html);

        $xpath = new \DOMXPath($doc);
        $pTags = $xpath->query('//p');

        $text = '';

        foreach ($pTags as $p) {
            // clone để xử lý
            $pClone = $p->cloneNode(true);

            // xoá các tag không mong muốn trong p
            foreach (['ul', 'ol', 'table', 'script', 'style'] as $tag) {
                while (true) {
                    $nodes = $pClone->getElementsByTagName($tag);
                    if ($nodes->length == 0) break;
                    $nodes->item(0)->parentNode->removeChild($nodes->item(0));
                }
            }

            $pText = trim($pClone->textContent);

            if ($pText) {
                $text .= ' ' . $pText;
            }

            // đủ length thì dừng
            if (mb_strlen($text, $charset) >= $len) {
                break;
            }
        }

        // fallback nếu không có p
        if (empty(trim($text))) {
            $text = strip_tags($html);
        }

        $text = html_entity_decode($text, ENT_QUOTES, $charset);
        $text = trim(preg_replace('/\s+/', ' ', $text));

        // cắt đúng word
        if (mb_strlen($text, $charset) > $len) {
            $text = mb_substr($text, 0, $len, $charset);
            $text = preg_replace('/\s+\S*$/u', '', $text);
            return $text . '...';
        }

        return $text;
    }

    public static function titleAction($data)
    {
        return array(
            'title' => !empty($data[0]) ? $data[0] : '',
            'flag' => !empty($data[1]) ? $data[1] : '',
        );
    }

    public static function metaHead($data)
    {
        return array(
            'title_seo' => !empty($data["title_seo"]) ? $data["title_seo"] : (!empty($data["name"]) ? $data["name"] : (!empty($data["title"]) ? $data["title"] : '')),
            'meta_key' => !empty($data["meta_key"]) ? $data["meta_key"] : '',
            'meta_des' => !empty($data["meta_des"]) ? $data["meta_des"] : ''
        );
    }

    public static function renderSTT($key, $data)
    {
        $start = ($data['current_page'] - 1) * $data['per_page'];
        return $start + $key;
    }

    public static function renderStatus($status = 1)
    {
        $arr = ['Non-Active', 'Active'];
        return !empty($arr[$status]) ? $arr[$status] : 'Empty';
    }

    public static function paymentFrom($type = ''): string
    {
        return $type == \dataAction::PAYMENT_STRIPE ? 'STRIPE' : "OTHER";
    }

    public static function paymentStatus($status = ''): string
    {
        return $status == \dataAction::PAYMENT_STATUS ? 'SUCCESS' : "FAIL";
    }

    public static function pointAction($action = ''): string
    {
        return $action == \dataAction::ADDITION ? '+' : "-";
    }

    public static function convertPoint($point = 0, $x = 1)
    {
        $point = $point * $x;
        return $point > 999 ? number_format($point) . ' PT' : $point . ' PT';
    }

    public static function convertAmount($amount = 0)
    {
        return $amount > 999 ? '¥ ' . number_format($amount) : '¥ ' . $amount;
    }

    public static function numberFormat($amount)
    {
        return $amount > 999 ? number_format($amount) : $amount;
    }

    public static function sanitizeInput($input)
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    public static function getCookie($request, $name): array|string|null
    {
        return $request->cookie($name);
    }

    public static function checkCookie($request, $name): bool
    {
        if ($request->hasCookie($name)) {
            return true;
        } else {
            return false;
        }
    }

    public static function formatNumberViews($number, $type = '')
    {
        $txt = !$type ? '' : '';
        if ($number < 1000) {
            return $number . ' ';
        } elseif ($number < 1000000) {
            return ceil($number / 1000) . 'k ' . $txt;
        } elseif ($number < 1000000000) {
            return ceil($number / 1000000) . 'm ' . $txt;
        } else {
            return ceil($number / 1000000000) . 'b ' . $txt;
        }
    }

    public static function formatChapters($number, $type = '')
    {
        $txt = !$type ? 'ch' : '';
        if ($number < 1000) {
            return $number . ' ' . $txt;
        } elseif ($number < 1000000) {
            return ceil($number / 1000) . 'k ' . $txt;
        } elseif ($number < 1000000000) {
            return ceil($number / 1000000) . 'm ' . $txt;
        } else {
            return ceil($number / 1000000000) . 'b ' . $txt;
        }
    }

    public static function formatNumbers($number, $type = '')
    {
        if ($number < 1000) {
            return $number . ' ';
        } elseif ($number < 1000000) {
            return ceil($number / 1000) . 'k ';
        } elseif ($number < 1000000000) {
            return ceil($number / 1000000) . 'm ';
        } else {
            return ceil($number / 1000000000) . 'b ';
        }
    }

    public static function countText($total_chap)
    {
        return self::formatNumbers($total_chap * 5000);
    }

    public static function renderStar($value = 0)
    {
        $value = $value / 2;
        $star = $value;
        // Đảm bảo giá trị nằm trong khoảng từ 0 đến 5
        $value = max(0, min(5, $value));

        // Xác định số sao đầy đủ, sao một nửa, và sao trống
        $full_stars = floor($value);
        $half_star = ($value - $full_stars) >= 0.5 ? 1 : 0;
        $empty_stars = 5 - $full_stars - $half_star;

        // Tạo HTML cho các sao
        $output = '';

        // Thêm sao đầy đủ
        for ($i = 0; $i < $full_stars; $i++) {
            $output .= '<i class="fas fa-star"></i>';
        }

        // Thêm sao một nửa nếu có
        if ($half_star) {
            $output .= '<i class="fas fa-star-half-alt"></i>';
        }

        // Thêm sao trống
        for ($i = 0; $i < $empty_stars; $i++) {
            $output .= '<i class="far fa-star"></i>';
        }

        $output .= ' ' . $star;

        return $output;
    }

    public static function renderCode($settings, $key)
    {
        if (empty($settings['setting']['options'])) return '';
        foreach ($settings['setting']['options'] as $row) {
            if ($row['key'] == $key) {
                return $row['value'];
            }
        }

        return '';
    }

    public static function renderThumb($url)
    {
        $url = str_replace('/public/images/', '', $url);
        $url = str_replace('public/images/', '', $url);
        return env('IMG_URL') . $url;
    }

    public static function renderTitle($row)
    {
        return !empty($row['titleRewrite']) ? $row['titleRewrite'] : $row['title'];
    }

    public static function renderDes($row)
    {
        return !empty($row['descriptionRewrite']) ? $row['descriptionRewrite'] : $row['description'];
    }

    public static function renderContent($row)
    {
        $content = !empty($row['contentRewrite']) ? $row['contentRewrite'] : $row['content'];
        $content = str_replace('{{DOMAIN}}/public/images/', env('IMG_URL'), $content);
        return $content;
    }

    public static function renderContentText($text)
    {
        return str_replace('{{DOMAIN}}/public/images/', env('IMG_URL'), $text);
    }

    public static function renderEditor($row)
    {
        return 'by Spider';
    }

    public static function convertCreatedAt($created_at)
    {
        $date = new \DateTime($created_at);
        return $date->format('d F, Y');
    }

    public static function mergerTypes(): array
    {
        $data = [];
        foreach (\dataType::TYPES_TEXT as $k => $type) {
            foreach (\dataRegion::REGION_TEXT as $key => $region) {
                $data[$k . '_' . $key] = ["title" => $type['title'] . ' ' . $region['title'], "slug" => $type['slug'] . '-' . $region['slug']];
            }
        }

        return $data;
    }

    public static function renderCate($type)
    {
        $cates = self::mergerTypes();
        if (!empty($cates[$type]['slug'])) {
            $cates[$type]['slug'] = route('page.cate.index', ['slug' => $cates[$type]['slug']]);
        }

        return $cates[$type] ?? ['title' => '', 'slug' => route('page.home')];
    }

    public static function renderTypeCate($slug)
    {
        $cates = self::mergerTypes();
        $data = [];
        foreach ($cates as $k => $item) {
            if ($item['slug'] == $slug) {
                $data['title'] = $item['title'];
                $data['slug'] = $item['slug'];
                $data['title_seo'] = $item['title'] . " - Tin tức, trải nghiệm & đánh giá chi tiết mới nhất";
                $data['meta_des'] = "Chuyên mục tổng hợp các tin tức mới nhất về " . $item['title'] . ". Chia sẻ kinh nghiệm đi " . $item['title'] . " tự túc, đánh giá thực tế về điểm đến, khách sạn và ẩm thực giúp bạn có hành trình trọn vẹn nhất.";
                $data['meta_key'] = "kinh nghiệm " . $item['title'] . ", đánh giá " . $item['title'] . ", trải nghiệm " . $item['title'] . " tự túc, tin tức du lịch " . $item['title'] . ", cẩm nang " . $item['title'];
                $data['type'] = $k;

                return $data;
            }
        }

        return $data;
    }

    public static function findBySlug($slug, $type = '')
    {
        if (!empty($type)) {
            foreach (\dataMenu::menus() as $key => $menu) {
                if ($menu['slug'] === $slug && $menu['type'] === $type) {
                    return $menu;
                }
            }
        } else {
            foreach (\dataMenu::menus() as $key => $menu) {
                if ($menu['slug'] === $slug) {
                    return $menu;
                }
            }
        }

        return [];
    }

    public static function findTypesByTagSlug($slug)
    {
        $types['info'] = [];
        $types['keys'] = [];
        foreach (\dataMenu::menus() as $key => $menu) {
            $tags = array_values(array_filter(array_map(
                function ($item) {
                    $title = trim($item);

                    if (!$title) return null;

                    return [
                        'title' => $title,
                        'slug' => self::renderSlug($title),
                    ];
                },
                explode(',', $menu[4])
            )));

            foreach ($tags as $tag) {
                if ($tag['slug'] == $slug && $menu[5]) {
                    $types['keys'][] = $menu[5];
                    if (empty($types['info'])) $types['info'] = $tag;
                }
            }
        }

        return $types;
    }

    public static function findByType($type)
    {
        foreach (\dataMenu::menus() as $key => $menu) {
            if ($menu['level'] == 2 && in_array($type, explode(',', $menu['type']))) {
                return $menu;
            }
        }

        return [];
    }

    public static function findByParentKey($parentKey)
    {
        foreach (\dataMenu::menus() as $key => $menu) {
            if ($key == $parentKey) return $menu;
        }

        return [];
    }

    public static function findByListMenuTypesByKey($parentKey, $prefix = '')
    {
        $arr[] = $prefix . $parentKey;
        foreach (\dataMenu::menus() as $key => $menu) {
            if (!empty($menu['parent']) && !empty($menu['isPost']) && $menu['isPost'] == 'news' && $menu['parent'] == $parentKey) {
                $arr[] = $prefix . $menu['type'];
            }
        }

        return $arr;
    }

    public static function arrListMenuTypesByKey($parentKey)
    {
        $arr = [];
        foreach (\dataMenu::menus() as $key => $menu) {
            if (!empty($menu['parent']) && $menu['parent'] == $parentKey) {
                $arr[] = $menu;
            }
        }

        return $arr;
    }

    public static function generateDetailTitleSeo($title, $category)
    {
        return $title . $category['key_seo'];
    }

    public static function generateDetailDesSeo($row)
    {
        $des = $row['descriptionRewrite'] ? $row['descriptionRewrite'] : $row['description'];
        if (!$des || str_word_count($des) < 50) {
            $des = $row['contentRewrite'] ? $row['contentRewrite'] : $row['content'];
            $des = self::shortDescSEO($des, 280);
        }

        return $des;
    }

    public static function buildTocContent(string $html, $dataMerge, array $prefix = []): string
    {
        if (empty($html)) return $html;

        $htmlRV = '';
        if (!empty($dataMerge['showFoods'])) {
            $htmlRV = '<div class="box-related">';
            $htmlRV .= '<div class="title" style="font-size: 18px; font-weight: 600;">🔥🔥🔥 Hành trình Trải nghiệm & Ẩm thực</div>';
            $htmlRV .= '<ul>';
            foreach ($dataMerge['showFoods'] as $row) {
                $title = self::renderTitle($row);
                $url = route('page.product.show', ['slug' => $row['slug']]);
                $htmlRV .= "<li><a href=\"{$url}\" title=\"{$title}\">{$title}</a></li>";
            }
            $htmlRV .= '</ul>';
            $htmlRV .= '</div>';
        }

        $htmlRC = '';
        if (!empty($dataMerge['showReviews'])) {
            $htmlRC = '<div class="box-related">';
            $htmlRC .= '<div class="title" style="font-size: 18px; font-weight: 600;">🔥🔥🔥 Review chi tiết & Trải nghiệm thực tế</div>';
            $htmlRC .= '<ul>';
            foreach ($dataMerge['showReviews'] as $row) {
                $title = self::renderTitle($row);
                $url = route('page.product.show', ['slug' => $row['slug']]);
                $htmlRC .= "<li><a href=\"{$url}\" title=\"{$title}\">{$title}</a></li>";
            }
            $htmlRC .= '</ul>';
            $htmlRC .= '</div>';
        }

        $html = self::removeInvalidImages($html);
        $html = self::removeOldToc($html);
        $html = self::insertHtmlMiddleHeading($html, $htmlRV, $htmlRC);

        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);

        $body = $dom->getElementsByTagName('body')->item(0);
        if (!$body) return $html;

        $toc = [];
        $index = 1;

        foreach ($dom->getElementsByTagName('*') as $node) {
            if (in_array($node->nodeName, ['h2', 'h3'])) {

                $text = trim($node->textContent);
                if (!$text) continue;

                $slug = self::renderSlug($text);
                $id = $slug . '-' . $index++;

                // update text
                if ($node->nodeName == 'h2') {
                    if (!empty($prefix['title']) && !empty($prefix['key_seo'])) {
                        $node->nodeValue = self::formatTextHeading(preg_replace('/^[^\p{L}]+/u', '', $text));
                    } else if (!empty($prefix['title'])) {
                        $node->nodeValue = self::formatTextHeading($prefix['title'] . ' - ' . preg_replace('/^[^\p{L}]+/u', '', $text));
                    } else {
                        $node->nodeValue = self::formatTextHeading(preg_replace('/^[^\p{L}]+/u', '', $text));
                    }
                }

                if ($node->nodeName == 'h3') {
                    $node->nodeValue = self::formatTextHeading(preg_replace('/^[^\p{L}]+/u', '', $text));
                }

                // add id + fix offset
                $node->setAttribute('id', $id);

                $oldStyle = $node->getAttribute('style');
                $node->setAttribute('style', $oldStyle . ';scroll-margin-top:110px');

                $toc[] = [
                    'tag' => $node->nodeName,
                    'text' => $text,
                    'id' => $id
                ];
            }
        }

        if (empty($toc)) return $html;

        $tocHtml = '<nav class="article-toc">';
        $tocHtml .= '<strong>Nội dung chính</strong>';

        $count_p = 1;
        $count_c = 1;
        foreach ($toc as $item) {
            $class = $item['tag'] === 'h3' ? 'toc-child' : 'toc-parent';
            $text = $item['tag'] === 'h3' ? $item['text'] : self::formatTextHeading($item['text']);

            if($item['tag'] !== 'h3') {
                $tocHtml .= "<a class='{$class}' href='#{$item['id']}'>{$count_p}: {$text}</a>";
                $count_c = 1;
            }else{
                $stt = $count_p - 1;
                $tocHtml .= "<a class='{$class}' href='#{$item['id']}'>{$stt}.{$count_c}: {$text}</a>";
            }

            if($item['tag'] !== 'h3') $count_p ++;
            if($item['tag'] === 'h3') $count_c ++;
        }

        $tocHtml .= '</nav>';

        $fragment = $dom->createDocumentFragment();
        $fragment->appendXML($tocHtml);
        $body->insertBefore($fragment, $body->firstChild);

        $newHtml = '';
        foreach ($body->childNodes as $child) {
            $newHtml .= $dom->saveHTML($child);
        }

        $newHtml = preg_replace('/<br\s*\/?>/i', '', $newHtml);
        $newHtml = self::processImages($newHtml);
        $newHtml = str_replace('<?xml encoding="utf-8" ?>', '', $newHtml);

        return $newHtml;
    }

    public static function processImages($html)
    {
        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);

        $xpath = new \DOMXPath($dom);

        $images = $xpath->query('//img');

        foreach ($images as $img) {

            $next = $img->nextSibling;

            // tìm caption ngay sau img
            if ($next && $next->nodeName === 'p') {

                $captionText = trim($next->textContent);

                // bỏ "Ảnh: ..."
                $captionText = preg_replace('/Ảnh:.*$/iu', '', $captionText);
                $captionText = trim($captionText);

                // tạo figure
                $figure = $dom->createElement('figure');

                // clone img
                $newImg = $img->cloneNode(true);
                $figure->appendChild($newImg);

                // tạo figcaption nếu có nội dung
                if ($captionText) {
                    $figcaption = $dom->createElement('figcaption', $captionText);
                    $figure->appendChild($figcaption);
                }

                // replace img bằng figure
                $img->parentNode->replaceChild($figure, $img);

                // xoá p caption cũ
                $next->parentNode->removeChild($next);
            }
        }

        return $dom->saveHTML();
    }

    public static function formatTextHeading($str)
    {
        return preg_replace('/^(\d+)(\S)/u', '$1: $2', $str);
    }

    public static function removeOldToc(string $html): string
    {
        if (empty($html)) return $html;

        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . html_entity_decode($html));

        $xpath = new \DOMXPath($dom);

        // tìm h2 chứa "Mục lục"
        $h2Nodes = $xpath->query('//h2[contains(translate(text(),"ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz"), "mục lục")]');

        foreach ($h2Nodes as $h2) {

            // tìm ul ngay sau h2
            $ul = $xpath->query('following-sibling::ul[1]', $h2)->item(0);

            if ($ul) {
                $ul->parentNode->removeChild($ul);
            }

            // xoá h2
            $h2->parentNode->removeChild($h2);
        }

        // render lại html
        $body = $dom->getElementsByTagName('body')->item(0);

        $newHtml = '';
        foreach ($body->childNodes as $child) {
            $newHtml .= $dom->saveHTML($child);
        }

        return $newHtml;
    }

    public static function removeInvalidImages(string $html): string
    {
        if (empty($html)) return $html;

        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . html_entity_decode($html));

        $images = $dom->getElementsByTagName('img');

        // loop ngược để tránh lỗi khi remove
        for ($i = $images->length - 1; $i >= 0; $i--) {
            $img = $images->item($i);

            if (!$img) continue;

            $src = trim($img->getAttribute('src'));

            // ❌ điều kiện xoá
            if (
                empty($src) ||
                $src === '#' ||
                str_starts_with($src, 'javascript') ||
                str_starts_with($src, 'data:image') // base64
            ) {
                $img->parentNode->removeChild($img);
                continue;
            }

            // ❌ src không phải url/path hợp lệ
            if (!filter_var($src, FILTER_VALIDATE_URL) && !str_starts_with($src, '/')) {
                $img->parentNode->removeChild($img);
            }
        }

        // render lại
        $body = $dom->getElementsByTagName('body')->item(0);

        $newHtml = '';
        foreach ($body->childNodes as $child) {
            $newHtml .= $dom->saveHTML($child);
        }

        return $newHtml;
    }

    public static function insertHtmlMiddleHeading(string $html, string $htmlRV, string $htmlRC): string
    {
        if (empty($html)) return $html;

        // tìm tất cả h2, h3
        preg_match_all('/<(h2|h3)([^>]*)>(.*?)<\/\1>/is', $html, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches[0])) {
            return $html . $htmlRC;
        }

        $total = count($matches[0]);

        // chen 1
        if ($total > 4) {
            $middleIndex = ceil($total / 4);
            $middleHeading = $matches[0][$middleIndex][0];
            $insertHtml = $htmlRV . $middleHeading;
            $html = preg_replace(
                '/' . preg_quote($middleHeading, '/') . '/',
                $insertHtml,
                $html,
                1
            );
        }

        // chen 2
        $middleHeading = $matches[0][$total - 1][0];
        $insertHtml = $htmlRC . $middleHeading;
        $html = preg_replace(
            '/' . preg_quote($middleHeading, '/') . '/',
            $insertHtml,
            $html,
            1
        );

        return $html;
    }

    public static function convertTime($time)
    {
        $created_at = Carbon::parse($time);
        return $created_at->format('M d, Y \a\t H:i');
    }

    public static function renderSlug($title)
    {
        return Str::slug($title, '-');
    }

    public static function renderShareMXH($type = '', $params = [], $config = [])
    {
        $siteName = !empty($params['name']) ? $params['name'] : env('SITE_NAME');
        $phone = !empty($config) ? self::renderCode($config, \settingKey::PHONE) : '';
        $facebook = !empty($config) ? self::renderCode($config, \settingKey::FACEBOOK) : '';
        $email = !empty($config) ? self::renderCode($config, \settingKey::EMAIL) : '';
        $telegram = !empty($config) ? self::renderCode($config, \settingKey::TELEGRAM) : '';
        $html = '';
        $telegramUrl = '';
        if (!empty($telegram)) {
            $telegramUrl = str_starts_with($telegram, 'http')
                ? $telegram
                : 'https://t.me/' . ltrim($telegram, '@');
        }
        $url = !empty($params['canonical']) ? $params['canonical'] : request()->url();
        $title = !empty($params['title_seo']) ? $params['title_seo'] : '';
        $description = !empty($params['meta_des']) ? $params['meta_des'] : '';
        $logo = !empty($params['logo']) ? $params['logo'] : asset('static/web/images/logo.svg');
        $image = !empty($params['logo_share']) ? $params['logo_share'] : asset('static/web/images/share.jpg');
        $baseUrl = route('page.home');
        $domainName = request()->getHost();

        if ($type === 'home') {
            $schemas = [
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebSite',
                    '@id' => $baseUrl . '#website',
                    'name' => $siteName,
                    'alternateName' => $domainName,
                    'url' => $baseUrl,
                    'publisher' => ['@id' => $baseUrl . '#organization'],
                    'inLanguage' => 'vi-VN',
                ],
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    '@id' => $baseUrl . '#webpage',
                    'url' => $url,
                    'name' => $title,
                    'description' => $description,
                    'isPartOf' => ['@id' => $baseUrl . '#website'],
                    'about' => ['@id' => $baseUrl . '#organization'],
                    'inLanguage' => 'vi-VN',
                ],
            ];

            foreach ($schemas as $schema) {
                $html .= '<script type="application/ld+json">'
                    . json_encode(
                        $schema,
                        JSON_UNESCAPED_UNICODE
                        | JSON_UNESCAPED_SLASHES
                        | JSON_HEX_TAG
                        | JSON_HEX_AMP
                        | JSON_HEX_APOS
                        | JSON_HEX_QUOT
                    )
                    . '</script>';
            }
        } else if ($type === 'cate') {
            $itemListElements = [];
            $position = 1;

            foreach (\dataMenu::menus() as $k => $row) {
                if (isset($params['news']) && !empty($row['parent']) && $row['parent'] == 'NEW_XS') {
                    $cate = \App\Helpers\Helpers::getSlugByKeyType($row['parent']);
                    $itemUrl = route('page.cate.index', ['slug' => $cate['slug'] . '/' . $row['slug']]);

                    $itemListElements[] = [
                        "@type" => "ListItem",
                        "position" => $position,
                        "name" => $row['title'],
                        "url" => $itemUrl
                    ];

                    $position++;
                } else if (!empty($row['type']) && !empty($row['level']) && $row['level'] == 2 && in_array($row['type'], [$params['category']['type']])) {
                    $cate = \App\Helpers\Helpers::getSlugByFieldType($row['type']);
                    if (empty($cate)) $cate = \App\Helpers\Helpers::getSlugByKeyType($row['parent']);
                    $itemUrl = route('page.cate.index', ['slug' => $cate['slug'] . '/' . $row['slug']]);

                    $itemListElements[] = [
                        "@type" => "ListItem",
                        "position" => $position,
                        "name" => $row['title'],
                        "url" => $itemUrl
                    ];

                    $position++;
                } else if (!empty($params['category']['title']) && $params['category']['title'] == 'VIETLOTT' && !empty($row['level']) && !empty($row['parent']) && $row['level'] == 2 && in_array($row['parent'], ['VIETLOTT'])) {
                    $itemUrl = route('page.cate.index', ['slug' => $params['category']['slug'] . '/' . $row['slug']]);

                    $itemListElements[] = [
                        "@type" => "ListItem",
                        "position" => $position,
                        "name" => $row['title'],
                        "url" => $itemUrl
                    ];

                    $position++;
                }
            };

            $jsonMenuItems = !empty($itemListElements)
                ? json_encode($itemListElements, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
                : '[]';
            $jsonMenuItemsFormatted = str_replace("\n", "\n    ", $jsonMenuItems);
            $currentUrl = request()->url();

            if (count($itemListElements) > 0) {
                $html .= '
                <script type="application/ld+json">
                {
                  "@context": "https://schema.org",
                  "@type": "CollectionPage",
                  "@id": "' . $currentUrl . '#webpage",
                  "url": "' . $currentUrl . '",
                  "name": "' . addslashes($title) . '",
                  "isPartOf": {
                    "@id": "' . $baseUrl . '#website"
                  },
                  "about": {
                    "@id": "' . $baseUrl . '#organization"
                  },
                  "description": "' . addslashes($description) . '",
                  "inLanguage": "vi-VN",
                  "mainEntity": {
                    "@type": "ItemList",
                    "name": "Tiện ích và danh mục ' . addslashes($title) . '",
                    "itemListElement": ' . $jsonMenuItemsFormatted . '
                  }
                }
                </script>';
            }

            $breadcrumbElements = [];
            if ($params['category']['level'] == 1 && !isset($params['categoryChild'])) {
                $breadcrumbElements = [
                    [
                        "@type" => "ListItem",
                        "position" => 1,
                        "name" => "Trang chủ",
                        "item" => rtrim(route('page.home'), '/')
                    ],
                    [
                        "@type" => "ListItem",
                        "position" => 2,
                        "name" => $title,
                        "item" => $currentUrl
                    ]
                ];
            } else if (isset($params['categoryChild']['title'])) {
                $breadcrumbElements = [
                    [
                        "@type" => "ListItem",
                        "position" => 1,
                        "name" => "Trang chủ",
                        "item" => rtrim(route('page.home'), '/')
                    ],
                    [
                        "@type" => "ListItem",
                        "position" => 2,
                        "name" => !empty($params['category']['title']) ? $params['category']['title'] : '',
                        "item" => route('page.cate.index', ['slug' => $params['category']['slug']])
                    ],
                    [
                        "@type" => "ListItem",
                        "position" => 3,
                        "name" => !empty($params['categoryChild']['title']) ? $params['categoryChild']['title'] : '',
                        "item" => route('page.cate.index', ['slug' => $params['category']['slug'] . '/' . $params['categoryChild']['slug']])
                    ]
                ];
            } else if ($params['category']['level'] == 2) {
                $cate = \App\Helpers\Helpers::getSlugByFieldType($params['category']['type']);
                if (empty($cate)) $cate = \App\Helpers\Helpers::getSlugByKeyType($params['category']['parent']);
                $breadcrumbElements = [
                    [
                        "@type" => "ListItem",
                        "position" => 1,
                        "name" => "Trang chủ",
                        "item" => rtrim(route('page.home'), '/')
                    ],
                    [
                        "@type" => "ListItem",
                        "position" => 2,
                        "name" => !empty($cate['title']) ? $cate['title'] : '',
                        "item" => route('page.cate.index', ['slug' => $cate['slug']])
                    ],
                    [
                        "@type" => "ListItem",
                        "position" => 3,
                        "name" => !empty($params['category']['title']) ? $params['category']['title'] : '',
                        "item" => route('page.cate.index', ['slug' => $cate['slug'] . '/' . $params['category']['slug']])
                    ]
                ];
            };

            $jsonBreadcrumbs = json_encode($breadcrumbElements, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            $jsonBreadcrumbsFormatted = str_replace("\n", "\n    ", $jsonBreadcrumbs);
            $html .= '
            <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@type": "BreadcrumbList",
              "description": "Đường dẫn liên kết điều hướng của trang ' . addslashes($title) . '",
              "itemListElement": ' . $jsonBreadcrumbsFormatted . '
            }
            </script>';
        }

        $organization = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => $baseUrl . '#organization',
            'name' => $siteName,
            'url' => $baseUrl,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $logo,
                'caption' => $siteName . ' Logo',
            ],
        ];

        $sameAs = array_values(array_filter([$facebook, $telegramUrl]));
        if (!empty($sameAs)) {
            $organization['sameAs'] = $sameAs;
        }

        if (!empty($email)) {
            $organization['email'] = $email;
        }

        if (!empty($phone)) {
            $organization['telephone'] = $phone;
        }

        if (!empty($phone) || !empty($email) || !empty($telegramUrl)) {
            $contactPoint = [
                '@type' => 'ContactPoint',
                'contactType' => 'customer support',
                'availableLanguage' => ['Vietnamese'],
            ];

            if (!empty($phone)) {
                $contactPoint['telephone'] = $phone;
            }
            if (!empty($email)) {
                $contactPoint['email'] = $email;
            }
            if (!empty($telegramUrl)) {
                $contactPoint['url'] = $telegramUrl;
            }

            $organization['contactPoint'] = [$contactPoint];
        }

        $html .= '<script type="application/ld+json">'
            . json_encode(
                $organization,
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
                | JSON_HEX_TAG
                | JSON_HEX_AMP
                | JSON_HEX_APOS
                | JSON_HEX_QUOT
            )
            . '</script>';

        $html .= '<meta property="og:type" content="website">';
        $html .= '<meta property="og:url" content="' . e($url) . '">';
        $html .= '<meta property="og:site_name" content="' . e($siteName) . '">';
        $html .= '<meta property="og:title" content="' . e($title) . '">';
        $html .= '<meta property="og:description" content="' . e($description) . '">';
        $html .= '<meta property="og:image" content="' . e($image) . '">';
        $html .= '<meta property="og:image:alt" content="' . e($title) . '">';
        $html .= '<meta property="og:locale" content="vi_VN">';
        $html .= '<meta name="twitter:card" content="summary_large_image">';
        $html .= '<meta name="twitter:title" content="' . e($title) . '">';
        $html .= '<meta name="twitter:description" content="' . e($description) . '">';
        $html .= '<meta name="twitter:url" content="' . e($url) . '">';
        $html .= '<meta name="twitter:image" content="' . e($image) . '">';
        $html .= '<meta name="twitter:image:alt" content="' . e($title) . '">';

        return $html;
    }

    public static function renderShareMXHPage($type = '', $params = [], $config = [])
    {
        $site_name = env('SITE_NAME');
        $telegram = !empty($config) ? \App\Helpers\Helpers::renderCode($config, \settingKey::TELEGRAM) : '';
        $html = '';
        $email = !empty($params['email']) ? $params['email'] : '';
        $telegramUrl = !empty($telegram) ? 'https://t.me/' . $telegram : 'https://t.me/duycuong7640';
        $url = asset('');
        $url = str_replace('http', 'https', $url);
        $url = str_replace('httpss', 'https', $url);
        $title = !empty($params['title_seo']) ? $params['title_seo'] : '';
        $description = !empty($params['meta_des']) ? $params['meta_des'] : '';
        $image = !empty($params['logo']) ? $params['logo_share'] : '';
        $baseUrl = route('page.home');
        $searchUrl = route('page.cate.search');
        $domainName = $_SERVER['HTTP_HOST'];

        if ($type === 'home') {
            $html .= '
            <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@type": "WebSite",
              "@id": "' . $baseUrl . '#website",
              "name": "' . addslashes($title) . '",
              "alternateName": "' . addslashes($domainName) . '",
              "url": "' . $baseUrl . '",
              "potentialAction": {
                "@type": "SearchAction",
                "target": {
                  "@type": "EntryPoint",
                  "urlTemplate": "' . $searchUrl . '?keyword={search_term_string}"
                },
                "query-input": "required name=search_term_string"
              }
            }
            </script>';
            $html .= '
            <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "WebPage",
                "@id": "' . $baseUrl . '#webpage",
                "url": "' . $baseUrl . '",
                "name": "' . addslashes($title) . '",
                "isPartOf": { "@id": "' . $baseUrl . '#website" },
                "description": "' . addslashes($description) . '",
                "inLanguage": "vi-VN"
            }
            </script>';
        }

        $html .= '
            <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@type": "Organization",
              "@id": "' . $baseUrl . '#organization",
              "name": "' . addslashes($domainName) . '",
              "url": "' . $baseUrl . '",
              "logo": "' . $image . '",
              "logo": {
                "@type": "ImageObject",
                "url": "' . $image . '",
                "caption": "' . env("SITE_NAME") . ' Logo"
              },
              "email": "' . $email . '",
              "sameAs": [
                "' . $telegramUrl . '"
              ],
              "contactPoint": [
                {
                  "@type": "ContactPoint",
                  "email": "' . $email . '",
                  "contactType": "customer service"
                },
                {
                  "@type": "ContactPoint",
                  "url": "' . $telegramUrl . '",
                  "contactType": "technical support"
                }
              ]
            }
            </script>';

        $html .= '<meta property="og:type" content="website">';
        $html .= '<meta property="og:url" content="' . $url . '">';
        $html .= '<meta property="og:site_name" content="' . $site_name . '">';
        $html .= '<meta property="og:title" content="' . $title . '">';
        $html .= '<meta property="og:description" content="' . $description . '">';
        $html .= '<meta property="og:image" content="' . $image . '">';
        $html .= '<meta property="og:locale" content="vi_VN">';
        $html .= '<meta name="twitter:site" content="' . $site_name . '">';
        $html .= '<meta name="twitter:creator" content="' . $site_name . '">';
        $html .= '<meta name="twitter:card" content="summary_large_image">';
        $html .= '<meta name="twitter:title" content="' . $title . '">';
        $html .= '<meta name="twitter:description" content="' . $description . '">';
        $html .= '<meta name="twitter:url" content="' . $url . '">';
        $html .= '<meta name="twitter:image" content="' . $image . '">';

        return $html;
    }

    public static function logError($data = [])
    {
        Log::debug(@json_encode($data));
    }

    public static function genCsrfToken($response, $type = '')
    {
        switch ($type) {
            case '1':
                return str_replace('TOKEN_ADD', '<meta name="csrf-token" content="' . csrf_token() . '">', $response);
            default:
                $response = str_replace('TOKEN_ADD', '<meta name="csrf-token" content="' . csrf_token() . '">', $response);
                return preg_replace(
                    '/<meta\s+name="csrf-token"\s+content="[^"]*"\s*\/?>/i',
                    '<meta name="csrf-token" content="' . csrf_token() . '">',
                    $response
                );
        }

    }

    public static function optimize_html($response)
    {
        if (!str_contains($response->headers->get('Content-Type') ?? '', 'text/html')) {
            return $response;
        }

        $html = $response->getContent();

        // ===== 1. Remove HTML comments (giữ lại IE condition)
        $html = preg_replace('/<!--(?!\[if).*?-->/', '', $html);

        // ===== 2. Protect <pre>, <textarea> (KHÔNG protect script)
        $protected = [];
        $html = preg_replace_callback(
            '#<(pre|textarea)(.*?)>(.*?)</\1>#is',
            function ($m) use (&$protected) {
                $key = '###PROTECTED_' . count($protected) . '###';
                $protected[$key] = $m[0];
                return $key;
            },
            $html
        );

        // ===== 3. Collapse whitespace (safe)
        $html = preg_replace('/\s+/', ' ', $html);
        $html = str_replace('> <', '><', $html);

        // ===== 4. Remove unnecessary attributes
        $html = str_replace([
            ' type="text/javascript"',
            ' type="text/css"',
        ], '', $html);

        // ===== 5. Defer JS (chỉ external script)
        $html = preg_replace_callback(
            '/<script\b([^>]*)src=(["\'])(.*?)\2([^>]*)>/i',
            function ($m) {
                $tag = $m[0];
                $src = $m[3];

                // ❌ Bỏ qua script quan trọng
                if (
                    str_contains($tag, 'defer') ||
                    str_contains($tag, 'async') ||
                    str_contains($tag, 'data-no-defer') ||

                    // tracking
                    str_contains($src, 'googletagmanager') ||
                    str_contains($src, 'google-analytics') ||
                    str_contains($src, 'facebook') ||
                    str_contains($src, 'analytics') ||

                    // nếu cần giữ script nào đó
                    str_contains($src, 'app.js')
                ) {
                    return $tag;
                }

                return '<script defer ' . trim($m[1] . ' src="' . $src . '" ' . $m[4]) . '>';
            },
            $html
        );

        // ===== 6. Lazy load images (không đụng nếu đã có)
        $html = preg_replace_callback(
            '/<img\b([^>]*)>/i',
            function ($m) {
                if (str_contains($m[0], 'loading=')) {
                    return $m[0];
                }
                return '<img loading="lazy" decoding="async" ' . $m[1] . '>';
            },
            $html
        );

        // ===== 7. Remove query string (?ver=123)
        $html = preg_replace('/\?ver=\d+/', '', $html);

        // ===== 8. DNS Prefetch + Preconnect
        $domains = [
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com',
        ];

        $headInject = '';
        foreach ($domains as $d) {
            $headInject .= '<link rel="dns-prefetch" href="' . $d . '">' . PHP_EOL;
            $headInject .= '<link rel="preconnect" href="' . $d . '" crossorigin>' . PHP_EOL;
        }

        $html = str_replace('</head>', $headInject . '</head>', $html);

        // ===== 9. Restore protected
        if (!empty($protected)) {
            $html = str_replace(array_keys($protected), array_values($protected), $html);
        }

        $response->setContent($html);

        return $response;
    }

    public static function mergeTypesBK($types): array
    {
        if (empty($types) || count($types) == 0) return [];
        $data = [];

        foreach ($types as $type) {
            foreach (\dataRegion::REGION as $region) {
                $data[] = $type . '_' . $region;
            }
        }

        return $data;
    }

    // Func này có tác dụng lọc ra du lịch trong, ngoài nước
    public static function mergeTypes(array $types, string $mode = 'all'): array
    {
        if (empty($types)) return [];
        $data = [];

        foreach ($types as $type) {
            foreach (\dataRegion::REGION as $region) {
                if ($mode === 'domestic' && in_array($region, \dataRegion::REGION_INTERNATIONAL)) {
                    continue;
                }
                if ($mode === 'international' && !in_array($region, \dataRegion::REGION_INTERNATIONAL)) {
                    continue;
                }
                $data[] = $type . '_' . $region;
            }
        }

        return $data;
    }

    // Func này có tác dụng lọc ra các loại type phù hợp (Travel, Travel_food...)
    public static function mergeRootTypes(array $types): array
    {
        if (empty($types)) return [];
        $data = [];

        foreach ($types as $type) {
            foreach (\dataRegion::REGION as $region) {
                $data[] = $type . '_' . $region;
            }
        }

        return $data;
    }

    // Func này có tác dụng chỉ lấy ra type, region phù hợp
    public static function mergeRootRegionTypes(array $types, array $regions): array
    {
        if (empty($types)) return [];
        $data = [];

        foreach ($types as $type) {
            foreach ($regions as $region) {
                $data[] = $type . '_' . $region;
            }
        }

        return $data;
    }

    public static function formatVietnameseDate($datetime)
    {
        $date = new \DateTime($datetime);

        // Map thứ
        $days = [
            'Sunday' => 'Chủ Nhật',
            'Monday' => 'Thứ Hai',
            'Tuesday' => 'Thứ Ba',
            'Wednesday' => 'Thứ Tư',
            'Thursday' => 'Thứ Năm',
            'Friday' => 'Thứ Sáu',
            'Saturday' => 'Thứ Bảy',
        ];

        $dayOfWeek = $days[$date->format('l')];

        return $dayOfWeek . ', ngày ' . $date->format('d/m/Y');
    }

    public static function formatVietnameseDateNumber($datetime)
    {
        $date = new \DateTime($datetime);

        // Map thứ
        $days = [
            'Sunday' => 'Chủ Nhật',
            'Monday' => 'Thứ 2',
            'Tuesday' => 'Thứ 3',
            'Wednesday' => 'Thứ 4',
            'Thursday' => 'Thứ 5',
            'Friday' => 'Thứ 6',
            'Saturday' => 'Thứ 7',
        ];

        return $days[$date->format('l')];
    }

    public static function formatVietnameseDateText($datetime)
    {
        $date = new \DateTime($datetime);

        // Map thứ
        $days = [
            'Sunday' => 'Chủ Nhật',
            'Monday' => 'Thứ Hai',
            'Tuesday' => 'Thứ Ba',
            'Wednesday' => 'Thứ Tư',
            'Thursday' => 'Thứ Năm',
            'Friday' => 'Thứ Sáu',
            'Saturday' => 'Thứ Bảy',
        ];

        return $days[$date->format('l')];
    }

    public static function formatDate($datetime)
    {
        $date = new \DateTime($datetime);
        return $date->format('d/m/Y');
    }

    public static function formatDateHI($datetime)
    {
        $date = new \DateTime($datetime);
        return $date->format('d/m/Y H:i');
    }

    public static function formatTicket($label_ticket)
    {
        $arr = @json_decode($label_ticket, true);

        if (!is_array($arr)) return '';

        $result = array_map(function ($item) {
            return str_replace('YL', 'YE', $item);
        }, $arr);

        return '<b>' . implode(' - ', $result) . '</b>';
    }

    public static function getSlugByType($type)
    {
        foreach (\dataMenu::menus() as $k => $row) {
            if ($k == $type) return $row;
        }
    }

    public static function getSlugByKeyType($type)
    {
        foreach (\dataMenu::menus() as $k => $row) {
            if ($k == $type && $row['level'] == 1) return $row;
        }
    }

    public static function getSlugByFieldType($type)
    {
        foreach (\dataMenu::menus() as $k => $row) {
            if (!empty($row['type']) && $row['type'] == $type && $row['level'] == 1) return $row;
        }
    }

    public static function getSlugByFieldChildType($type)
    {
        foreach (\dataMenu::menus() as $k => $row) {
            if (!empty($row['type']) && $row['type'] == $type && $row['level'] == 2) return $row;
        }
    }

    public static function getMenuByFieldType($type)
    {
        foreach (\dataMenu::menus() as $k => $row) {
            if (!empty($row['type']) && $row['type'] == $type) return $row;
        }
    }

    public static function getSubMenuByType($type)
    {
        $data = [];
        foreach (\dataMenu::menus() as $k => $row) {
            if (!empty($row['type']) && $row['type'] == $type && $row['level'] == 2) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public static function buildLoto($results)
    {
        $dau = [];
        $duoi = [];
        $dbNumber = null;

        // init đủ 0-9
        for ($i = 0; $i <= 9; $i++) {
            $dau[$i] = [];
            $duoi[$i] = [];
        }

        foreach ($results as $item) {
            $codes = json_decode($item['code'], true);

            if (!is_array($codes)) continue;

            foreach ($codes as $code) {
                $code = trim((string)$code);

                if (!is_numeric($code)) continue;

                $last2 = substr(str_pad($code, 2, '0', STR_PAD_LEFT), -2);

                // ✅ check ĐB
                $isDb = ($item['level'] === 'ĐB');

                if ($isDb && $dbNumber === null) {
                    $dbNumber = $last2;
                }

                $head = (int)$last2[0];
                $tail = (int)$last2[1];

                $dau[$head][] = [
                    'value' => $last2,
                    'is_db' => $isDb
                ];

                $duoi[$tail][] = [
                    'value' => $last2,
                    'is_db' => $isDb
                ];
            }
        }

        // sort theo value
        foreach ($dau as &$arr) {
            usort($arr, fn($a, $b) => strcmp($a['value'], $b['value']));
        }

        foreach ($duoi as &$arr) {
            usort($arr, fn($a, $b) => strcmp($a['value'], $b['value']));
        }

        return [
            'dau' => $dau,
            'duoi' => $duoi,
            'db' => $dbNumber
        ];
    }

    public static function parseMax3D($data)
    {
        $result = [];
        $currentIndex = -1;

        foreach ($data as $item) {
            if (is_string($item)) {
                $result[] = [
                    'head' => $item,
                    'items' => []
                ];
                $currentIndex++;
                continue;
            }

            if (is_array($item) && $currentIndex >= 0) {
                $result[$currentIndex]['items'][] = $item;
            }
        }

        return $result;
    }

    public static function analyzeNumbers($numbers)
    {
        $result = [
            'chan' => 0,
            'le' => 0,
            'lon' => 0,
            'nho' => 0,
        ];

        foreach ($numbers as $num) {
            $num = (int)$num;

            // chẵn / lẻ
            if ($num % 2 === 0) {
                $result['chan']++;
            } else {
                $result['le']++;
            }

            // lớn / nhỏ (chuẩn Keno: <=40 nhỏ, >40 lớn)
            if ($num > 40) {
                $result['lon']++;
            } else {
                $result['nho']++;
            }
        }

        return $result;
    }

    public static function addMinutesToTime($datetime, $minutes = 8)
    {
        return Carbon::parse($datetime)
            ->addMinutes($minutes)
            ->format('H:i');
    }

    public static function splitLoto($number)
    {
        $str = str_pad($number, 2, '0', STR_PAD_LEFT);

        return [
            'dau' => (int)$str[0],
            'duoi' => (int)$str[1],
        ];
    }

    public static function getAllLotteryCalendars(array $fullData)
    {
        $allMonths = [];

        // Foreach 1: Duyệt qua từng tháng trong JSON (2026-04, 2026-05...)
        foreach ($fullData as $monthKey => $monthData) {
            $resultsByDate = [];

            // Phẳng hóa dữ liệu của tháng đó
            foreach ($monthData as $items) {
                foreach ($items as $item) {
                    $dateKey = date('Y-m-d', strtotime($item['day']));
                    $resultsByDate[$dateKey] = $item;
                }
            }

            $startOfMonth = \Carbon\Carbon::parse($monthKey . '-01');
            $endOfMonth = $startOfMonth->copy()->endOfMonth();
            $firstDayOfWeek = $startOfMonth->dayOfWeekIso;

            $days = [];
            // Chèn ô trống
            for ($i = 1; $i < $firstDayOfWeek; $i++) {
                $days[] = ['type' => 'empty'];
            }

            // Chèn ngày thực
            for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
                $currentDate = $date->format('Y-m-d');
                $days[] = [
                    'type' => 'day',
                    'label' => $date->format('d/m/Y'),
                    'data' => $resultsByDate[$currentDate] ?? null
                ];
            }

            $allMonths[] = [
                'title' => "Tháng " . $startOfMonth->format('m - Y'),
                'days' => $days
            ];
        }

        return $allMonths;
    }

    public static function getTodayLotteryData()
    {
        $today = Carbon::now('Asia/Ho_Chi_Minh')->dayOfWeek;
        return \dataMenu::LOTTERY_SCHEDULE[$today] ?? [];
    }

    public static function genReContent($slug, $config, $title, $tag)
    {
        if ($slug == 'ket-qua-xo-so-mb') {
            switch ($tag) {
                case 'h1':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_MB_H1);
                    $result = $content ? $content : $title;
                    return $result;
                case 'title_seo':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_MB_TITLE_SEO);
                    $result = $content ? $content : $title;
                    return $result;
                case 'meta_des':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_MB_DES_SEO);
                    $result = $content ? $content : $title;
                    return $result;
                case 'meta_key':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_MB_KEY_SEO);
                    $result = $content ? $content : $title;
                    return $result;
            }
        }

        if ($slug == 'ket-qua-xo-so-mt') {
            switch ($tag) {
                case 'h1':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_MT_H1);
                    $result = $content ? $content : $title;
                    return $result;
                case 'title_seo':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_MT_TITLE_SEO);
                    $result = $content ? $content : $title;
                    return $result;
                case 'meta_des':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_MT_DES_SEO);
                    $result = $content ? $content : $title;
                    return $result;
                case 'meta_key':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_MT_KEY_SEO);
                    $result = $content ? $content : $title;
                    return $result;
            }
        }

        if ($slug == 'ket-qua-xo-so-mn') {
            switch ($tag) {
                case 'h1':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_MN_H1);
                    $result = $content ? $content : $title;
                    return $result;
                case 'title_seo':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_MN_TITLE_SEO);
                    $result = $content ? $content : $title;
                    return $result;
                case 'meta_des':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_MN_DES_SEO);
                    $result = $content ? $content : $title;
                    return $result;
                case 'meta_key':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_MN_KEY_SEO);
                    $result = $content ? $content : $title;
                    return $result;
            }
        }

        if ($slug == 'ket-qua-xo-so-vietlott') {
            switch ($tag) {
                case 'h1':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_VIETLOTT_H1);
                    $result = $content ? $content : $title;
                    return $result;
                case 'title_seo':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_VIETLOTT_TITLE_SEO);
                    $result = $content ? $content : $title;
                    return $result;
                case 'meta_des':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_VIETLOTT_DES_SEO);
                    $result = $content ? $content : $title;
                    return $result;
                case 'meta_key':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::TRY_SPIN_VIETLOTT_KEY_SEO);
                    $result = $content ? $content : $title;
                    return $result;
            }
        }

        if ($slug == 'so-mo-giai-mong') {
            switch ($tag) {
                case 'h1':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::XOMO_H1);
                    $result = $content ? $content : $title;
                    return $result;
                case 'title_seo':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::XOMO_TITLE_SEO);
                    $result = $content ? $content : $title;
                    return $result;
                case 'meta_des':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::XOMO_DES_SEO);
                    $result = $content ? $content : $title;
                    return $result;
                case 'meta_key':
                    $content = \App\Helpers\Helpers::renderCode($config, \settingKey::XOMO_KEY_SEO);
                    $result = $content ? $content : $title;
                    return $result;
            }
        }

        return $title;
    }

    public static function genKeyCacheDevice($request, $mobileText)
    {
        if ($request->has('reset')) {
            if ($request->get('reset') == 2) {
                $mobileText = '_mobile';
            }
        }

        return $mobileText;
    }

    public static function getCurrentGioCanChi(): string
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        $hour = (int)$now->format('H');

        // 12 chi giờ
        $chiIndex = match (true) {
            $hour >= 23 || $hour < 1 => 0, // Tý
            $hour >= 1 && $hour < 3 => 1,  // Sửu
            $hour >= 3 && $hour < 5 => 2,  // Dần
            $hour >= 5 && $hour < 7 => 3,  // Mão
            $hour >= 7 && $hour < 9 => 4,  // Thìn
            $hour >= 9 && $hour < 11 => 5, // Tỵ
            $hour >= 11 && $hour < 13 => 6,// Ngọ
            $hour >= 13 && $hour < 15 => 7,// Mùi
            $hour >= 15 && $hour < 17 => 8,// Thân
            $hour >= 17 && $hour < 19 => 9,// Dậu
            $hour >= 19 && $hour < 21 => 10,// Tuất
            default => 11, // Hợi
        };

        $chi = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];

        // Can ngày tính theo Julian day
        $jd = gregoriantojd($now->month, $now->day, $now->year);
        $dayCanIndex = ($jd + 9) % 10;

        // Can giờ Tý phụ thuộc Can ngày
        $startCanIndex = match ($dayCanIndex) {
            0, 5 => 0, // Giáp/Kỷ -> Giáp Tý
            1, 6 => 2, // Ất/Canh -> Bính Tý
            2, 7 => 4, // Bính/Tân -> Mậu Tý
            3, 8 => 6, // Đinh/Nhâm -> Canh Tý
            4, 9 => 8, // Mậu/Quý -> Nhâm Tý
        };

        $canIndex = ($startCanIndex + $chiIndex) % 10;

        $can = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];

        return 'Giờ ' . $can[$canIndex] . ' ' . $chi[$chiIndex];
    }

    public static function getCopeValueByOption($_data, $key = '', $regex = true)
    {
        $value = '';
        foreach ($_data as $row) {
            if ($row['key'] == $key) {
                $value = $row['value'];
            }
        }

        if (in_array($key, ['SOLAR_SEASONS', 'DAY_OFFICER']) && $regex) {
            $value = preg_replace('/\s*\([^)]*\)/', '', $value);
        }

        return $value;
    }

    public static function getCopeValueByOption2($_data, $key = '', $regex = true)
    {
        $value = '';
        foreach ($_data as $row) {
            if ($row['key'] == $key) {
                $value = $row['value'];
            }
        }

        return $value;
    }

    public static function matchHour($text)
    {
        preg_match('/^(.*?)\s*\((.*?)\)/', $text, $matches);

        return [
            'title' => trim($matches[1] ?? ''),
            'hour' => trim($matches[2] ?? ''),
        ];
    }

    public static function monthToText($text)
    {
        $months = [
            1 => 'Một',
            2 => 'Hai',
            3 => 'Ba',
            4 => 'Tư',
            5 => 'Năm',
            6 => 'Sáu',
            7 => 'Bảy',
            8 => 'Tám',
            9 => 'Chín',
            10 => 'Mười',
            11 => 'Mười Một',
            12 => 'Mười Hai',
        ];

        $month = (int)$text;

        return 'Tháng ' . ($months[$month] ?? $month);
    }

    public static function isDateFormatDMY(string $value): bool
    {
        try {
            $date = Carbon::createFromFormat('d-m-Y', $value);

            return $date && $date->format('d-m-Y') === $value;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getZodiacOrder(string $canChi): string
    {
        $canChi = mb_strtolower($canChi, 'UTF-8');

        $chiList = [
            'tý',
            'sửu',
            'dần',
            'mão',
            'thìn',
            'tỵ',
            'ngọ',
            'mùi',
            'thân',
            'dậu',
            'tuất',
            'hợi',
        ];

        foreach ($chiList as $index => $chi) {
            if (mb_strpos($canChi, $chi) !== false) {
                return asset('static/web/images/' . (($index + 1) . '.png'));
            }
        }

        return asset('static/web/images/1.png');
    }

    public static function execTagPContent($content)
    {
        return preg_replace('/(<br\s*\/?>\s*){2,}/i', '</p><p>', $content);
    }

    public static function removeEmptyParagraphs(
        ?string $html,
        int $isBr = 0
    ): string
    {
        if (empty($html)) {
            return '';
        }

        // Xóa các thẻ <p> không có chữ hoặc số
        $html = preg_replace_callback(
            '/<p\b[^>]*>(.*?)<\/p>/isu',
            function ($matches) {
                $content = $matches[1];

                // Bỏ toàn bộ HTML bên trong
                $text = strip_tags($content);

                // Decode &nbsp;, &amp;...
                $text = html_entity_decode(
                    $text,
                    ENT_QUOTES | ENT_HTML5,
                    'UTF-8'
                );

                // Bỏ whitespace, NBSP...
                $text = preg_replace('/[\s\x{00A0}]+/u', '', $text);

                // Không có chữ hoặc số => xóa cả <p>
                if (
                    $text === '' ||
                    !preg_match('/[\p{L}\p{N}]/u', $text)
                ) {
                    return '';
                }

                return $matches[0];
            },
            $html
        );

        // Nếu bật isBr thì thêm <br/> trước <strong> và <span>
        if ($isBr === 1) {
            $html = preg_replace_callback(
                '/(?:(<br\s*\/?>)\s*)?(<(strong|span)\b[^>]*>)(.*?)(<\/\3>)/isu',
                function ($matches) {
                    $beforeBr = $matches[1] ?? '';
                    $openTag = $matches[2];
                    $content = $matches[4];
                    $closeTag = $matches[5];

                    // Ngay trước thẻ đã có <br> => không thêm
                    if ($beforeBr !== '') {
                        return $matches[0];
                    }

                    // Bên trong strong/span đã có <br> => không thêm
                    if (preg_match('/<br\s*\/?>/iu', $content)) {
                        return $openTag . $content . $closeTag;
                    }

                    // Chưa có br => thêm trước thẻ
                    return '<br/>' . $openTag . $content . $closeTag;
                },
                $html
            );
        }

        return $html;
    }

    public static function splitThaiThanContent(string $html): array
    {
        $parts = preg_split('/<br\s*\/?>/i', $html, 3);

        return [
            'title' => str_replace('  Vị', 'Vị', trim(($parts[0] ?? '') . ' <br /> ' . ($parts[1] ?? ''))),
            'content' => trim($parts[2] ?? ''),
        ];
    }

    public static function getWeekDates(?int $year = null, ?int $week = null): array
    {
        $now = Carbon::now();

        $year = $year ?: (int)$now->format('o'); // ISO week-year
        $week = $week ?: (int)$now->isoWeek();

        $startOfWeek = Carbon::now()
            ->setISODate($year, $week)
            ->startOfWeek(Carbon::MONDAY);

        $dates = [];

        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfWeek->copy()->addDays($i)->format('d-m-Y');
        }

        $prevWeek = $startOfWeek->copy()->subWeek();
        $nextWeek = $startOfWeek->copy()->addWeek();

        // List toàn bộ tuần trong năm
        $totalWeeks = Carbon::create($year, 12, 28)->isoWeek();

        $weeks = [];

        for ($w = 1; $w <= $totalWeeks; $w++) {
            $weekStart = Carbon::now()
                ->setISODate($year, $w)
                ->startOfWeek(Carbon::MONDAY);

            $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

            $weeks[] = [
                'year' => $year,
                'week' => $w,
                'start' => $weekStart->format('d-m-Y'),
                'end' => $weekEnd->format('d-m-Y'),
            ];
        }

        return [
            'year' => $year,
            'week' => $week,
            'dates' => $dates,

            'prev' => [
                'year' => (int)$prevWeek->format('o'),
                'week' => (int)$prevWeek->isoWeek(),
            ],

            'next' => [
                'year' => (int)$nextWeek->format('o'),
                'week' => (int)$nextWeek->isoWeek(),
            ],

            'weeks' => $weeks,
        ];
    }

    public static function parseWeekSlug(string $slug): ?array
    {
        if (!preg_match('/^(\d{1,2})-nam-(\d{4})$/', $slug, $matches)) {
            return null;
        }

        $week = (int)$matches[1];
        $year = (int)$matches[2];

        if ($week < 1 || $week > 53) {
            return null;
        }

        return [
            'week' => $week,
            'year' => $year,
        ];
    }

    public static function parseMonthSlug(string $slug): ?array
    {
        if (!preg_match('/^(\d{1,2})-nam-(\d{4})$/', $slug, $matches)) {
            return null;
        }

        $month = (int)$matches[1];
        $year = (int)$matches[2];

        if ($month < 1 || $month > 12) {
            return null;
        }

        return [
            'month' => $month,
            'year' => $year,
        ];
    }

    public static function getMonthDates(?int $year = null, ?int $month = null): array
    {
        $now = Carbon::now();

        $year = $year ?: (int)$now->year;
        $month = $month ?: (int)$now->month;

        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $dates = [];

        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            $dates[] = $date->format('d-m-Y');
        }

        $prevMonth = $startOfMonth->copy()->subMonth();
        $nextMonth = $startOfMonth->copy()->addMonth();

        return [
            'year' => (int)$startOfMonth->year,
            'month' => (int)$startOfMonth->month,
            'dates' => $dates,

            'prev' => [
                'year' => (int)$prevMonth->year,
                'month' => (int)$prevMonth->month,
            ],

            'next' => [
                'year' => (int)$nextMonth->year,
                'month' => (int)$nextMonth->month,
            ],
        ];
    }

    public static function generateYearCalendar(int $year, array $lists = []): array
    {
        $calendar = [];
        $mapDays = [];

        foreach ($lists as $item) {
            if (empty($item['day'])) {
                continue;
            }

            $mapDays[$item['day']] = $item;
        }

        for ($month = 1; $month <= 12; $month++) {
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $firstDayOfWeek = (int)date('N', strtotime("$year-$month-01"));

            $monthDays = [];

            for ($i = 1; $i < $firstDayOfWeek; $i++) {
                $monthDays[] = [
                    'type' => 'empty',
                    'solar_day' => '',
                    'lunar_day' => '',
                    'lunar_month' => '',
                    'lunar_year' => '',
                ];
            }

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $dayOfWeek = (int)date('N', strtotime($currentDate));

                $item = $mapDays[$currentDate] ?? null;

                $lunarDay = '';
                $lunarMonth = '';
                $lunarYear = '';

                if (!empty($item['lunarDay'])) {
                    [$lunarYearValue, $lunarMonthValue, $lunarDayValue] = explode('-', $item['lunarDay']);

                    $lunarDay = (int)$lunarDayValue;
                    $lunarMonth = (int)$lunarMonthValue;
                    $lunarYear = (int)$lunarYearValue;

                    if ($lunarDay === 1) {
                        $lunarDay = $lunarDay . '/' . $lunarMonth;
                    }
                }

                $monthDays[] = [
                    'type' => 'day',
                    'id' => $item['id'] ?? null,
                    'date' => date('d-m-Y', strtotime($currentDate)),

                    'solar_day' => self::checkNumber($day),
                    'solar_month' => self::checkNumber($month),
                    'solar_year' => $year,

                    'lunar_day' => self::checkNumber($lunarDay),
                    'lunar_month' => self::checkNumber($lunarMonth),
                    'lunar_year' => $lunarYear,

                    'strDay' => $item['strDay'] ?? null,
                    'strMonth' => $item['strMonth'] ?? null,
                    'strYear' => $item['strYear'] ?? null,

                    'options' => $item['options'] ?? [],

                    'is_sunday' => $dayOfWeek === 7,
                    'is_good' => !empty($item) && (int)$item['isDay'] === 1,
                ];
            }

            while (count($monthDays) % 7 !== 0) {
                $monthDays[] = [
                    'type' => 'empty',
                    'solar_day' => '',
                    'lunar_day' => '',
                    'lunar_month' => '',
                    'lunar_year' => '',
                ];
            }

            $calendar[$month] = [
                'month' => $month,
                'year' => $year,
                'month_label' => "LỊCH ÂM DƯƠNG $month/$year",
                'days' => $monthDays,
            ];
        }

        return $calendar;
    }

    public static function normalizeDate(string $date): string
    {
        return Carbon::createFromFormat('j-n-Y', $date)->format('d-m-Y');
    }

    public static function parseDayMonthYear(string $slug): ?array
    {
        if (!$slug) {
            return null;
        }

        if (!preg_match('/(\d{1,2})-(\d{1,2})-(\d{4})$/', $slug, $matches)) {
            return null;
        }

        $date = Carbon::createFromFormat(
            'd-m-Y',
            "{$matches[1]}-{$matches[2]}-{$matches[3]}"
        );

        return [
            'day' => $date->format('d'),
            'month' => $date->format('m'),
            'year' => $date->format('Y'),
        ];
    }

    public static function getCurrentCanChiHour(): string
    {
        $date = Carbon::now();

        $thienCan = [
            'Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu',
            'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý',
        ];

        $diaChi = [
            'Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ',
            'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi',
        ];

        // Tính Can ngày
        $days = $date->diffInDays(Carbon::create(1900, 1, 1));
        $dayCanIndex = ($days + 0) % 10;

        // Tính Chi giờ
        $hour = (int)$date->format('H');

        if ($hour == 23 || $hour == 0) {
            $chiIndex = 0;
        } else {
            $chiIndex = intdiv($hour + 1, 2);
        }

        $startCanIndex = match ($dayCanIndex) {
            0, 5 => 0, // Giáp, Kỷ => Giáp Tý
            1, 6 => 2, // Ất, Canh => Bính Tý
            2, 7 => 4, // Bính, Tân => Mậu Tý
            3, 8 => 6, // Đinh, Nhâm => Canh Tý
            4, 9 => 8, // Mậu, Quý => Nhâm Tý
        };

        $hourCanIndex = ($startCanIndex + $chiIndex) % 10;

        return $date->format('H\hi')
            . ', Giờ '
            . $thienCan[$hourCanIndex]
            . ' '
            . $diaChi[$chiIndex];
    }

    public static function getNapAm(string $canChi)
    {
        $map = [
            'Giáp Tý' => ['name' => 'Hải Trung Kim', 'element' => 'Kim'],
            'Ất Sửu' => ['name' => 'Hải Trung Kim', 'element' => 'Kim'],

            'Bính Dần' => ['name' => 'Lư Trung Hỏa', 'element' => 'Hỏa'],
            'Đinh Mão' => ['name' => 'Lư Trung Hỏa', 'element' => 'Hỏa'],

            'Mậu Thìn' => ['name' => 'Đại Lâm Mộc', 'element' => 'Mộc'],
            'Kỷ Tỵ' => ['name' => 'Đại Lâm Mộc', 'element' => 'Mộc'],

            'Canh Ngọ' => ['name' => 'Lộ Bàng Thổ', 'element' => 'Thổ'],
            'Tân Mùi' => ['name' => 'Lộ Bàng Thổ', 'element' => 'Thổ'],

            'Nhâm Thân' => ['name' => 'Kiếm Phong Kim', 'element' => 'Kim'],
            'Quý Dậu' => ['name' => 'Kiếm Phong Kim', 'element' => 'Kim'],

            'Giáp Tuất' => ['name' => 'Sơn Đầu Hỏa', 'element' => 'Hỏa'],
            'Ất Hợi' => ['name' => 'Sơn Đầu Hỏa', 'element' => 'Hỏa'],

            'Bính Tý' => ['name' => 'Giản Hạ Thủy', 'element' => 'Thủy'],
            'Đinh Sửu' => ['name' => 'Giản Hạ Thủy', 'element' => 'Thủy'],

            'Mậu Dần' => ['name' => 'Thành Đầu Thổ', 'element' => 'Thổ'],
            'Kỷ Mão' => ['name' => 'Thành Đầu Thổ', 'element' => 'Thổ'],

            'Canh Thìn' => ['name' => 'Bạch Lạp Kim', 'element' => 'Kim'],
            'Tân Tỵ' => ['name' => 'Bạch Lạp Kim', 'element' => 'Kim'],

            'Nhâm Ngọ' => ['name' => 'Dương Liễu Mộc', 'element' => 'Mộc'],
            'Quý Mùi' => ['name' => 'Dương Liễu Mộc', 'element' => 'Mộc'],

            'Giáp Thân' => ['name' => 'Tuyền Trung Thủy', 'element' => 'Thủy'],
            'Ất Dậu' => ['name' => 'Tuyền Trung Thủy', 'element' => 'Thủy'],

            'Bính Tuất' => ['name' => 'Ốc Thượng Thổ', 'element' => 'Thổ'],
            'Đinh Hợi' => ['name' => 'Ốc Thượng Thổ', 'element' => 'Thổ'],

            'Mậu Tý' => ['name' => 'Tích Lịch Hỏa', 'element' => 'Hỏa'],
            'Kỷ Sửu' => ['name' => 'Tích Lịch Hỏa', 'element' => 'Hỏa'],

            'Canh Dần' => ['name' => 'Tùng Bách Mộc', 'element' => 'Mộc'],
            'Tân Mão' => ['name' => 'Tùng Bách Mộc', 'element' => 'Mộc'],

            'Nhâm Thìn' => ['name' => 'Trường Lưu Thủy', 'element' => 'Thủy'],
            'Quý Tỵ' => ['name' => 'Trường Lưu Thủy', 'element' => 'Thủy'],

            'Giáp Ngọ' => ['name' => 'Sa Trung Kim', 'element' => 'Kim'],
            'Ất Mùi' => ['name' => 'Sa Trung Kim', 'element' => 'Kim'],

            'Bính Thân' => ['name' => 'Sơn Hạ Hỏa', 'element' => 'Hỏa'],
            'Đinh Dậu' => ['name' => 'Sơn Hạ Hỏa', 'element' => 'Hỏa'],

            'Mậu Tuất' => ['name' => 'Bình Địa Mộc', 'element' => 'Mộc'],
            'Kỷ Hợi' => ['name' => 'Bình Địa Mộc', 'element' => 'Mộc'],

            'Canh Tý' => ['name' => 'Bích Thượng Thổ', 'element' => 'Thổ'],
            'Tân Sửu' => ['name' => 'Bích Thượng Thổ', 'element' => 'Thổ'],

            'Nhâm Dần' => ['name' => 'Kim Bạch Kim', 'element' => 'Kim'],
            'Quý Mão' => ['name' => 'Kim Bạch Kim', 'element' => 'Kim'],

            'Giáp Thìn' => ['name' => 'Phú Đăng Hỏa', 'element' => 'Hỏa'],
            'Ất Tỵ' => ['name' => 'Phú Đăng Hỏa', 'element' => 'Hỏa'],

            'Bính Ngọ' => ['name' => 'Thiên Hà Thủy', 'element' => 'Thủy'],
            'Đinh Mùi' => ['name' => 'Thiên Hà Thủy', 'element' => 'Thủy'],

            'Mậu Thân' => ['name' => 'Đại Trạch Thổ', 'element' => 'Thổ'],
            'Kỷ Dậu' => ['name' => 'Đại Trạch Thổ', 'element' => 'Thổ'],

            'Canh Tuất' => ['name' => 'Thoa Xuyến Kim', 'element' => 'Kim'],
            'Tân Hợi' => ['name' => 'Thoa Xuyến Kim', 'element' => 'Kim'],

            'Nhâm Tý' => ['name' => 'Tang Đố Mộc', 'element' => 'Mộc'],
            'Quý Sửu' => ['name' => 'Tang Đố Mộc', 'element' => 'Mộc'],

            'Giáp Dần' => ['name' => 'Đại Khê Thủy', 'element' => 'Thủy'],
            'Ất Mão' => ['name' => 'Đại Khê Thủy', 'element' => 'Thủy'],

            'Bính Thìn' => ['name' => 'Sa Trung Thổ', 'element' => 'Thổ'],
            'Đinh Tỵ' => ['name' => 'Sa Trung Thổ', 'element' => 'Thổ'],

            'Mậu Ngọ' => ['name' => 'Thiên Thượng Hỏa', 'element' => 'Hỏa'],
            'Kỷ Mùi' => ['name' => 'Thiên Thượng Hỏa', 'element' => 'Hỏa'],

            'Canh Thân' => ['name' => 'Thạch Lựu Mộc', 'element' => 'Mộc'],
            'Tân Dậu' => ['name' => 'Thạch Lựu Mộc', 'element' => 'Mộc'],

            'Nhâm Tuất' => ['name' => 'Đại Hải Thủy', 'element' => 'Thủy'],
            'Quý Hợi' => ['name' => 'Đại Hải Thủy', 'element' => 'Thủy'],
        ];

        $canChi = trim($canChi);
        $canChi = str_replace('Tị', 'Tỵ', $canChi);
        $arr = $map[$canChi];
        return !empty($arr['name']) ? $arr['name'] : '';
    }

    public static function getHourMinuteByChi(string $chi)
    {
        $map = [
            "ty" => ["hour" => 23, "minute" => 30],
            "suu" => ["hour" => 1, "minute" => 30],
            "dan" => ["hour" => 3, "minute" => 30],
            "mao" => ["hour" => 5, "minute" => 30],
            "thin" => ["hour" => 7, "minute" => 30],
            "ti" => ["hour" => 9, "minute" => 30],
            "ngo" => ["hour" => 11, "minute" => 30],
            "mui" => ["hour" => 13, "minute" => 30],
            "than" => ["hour" => 15, "minute" => 30],
            "dau" => ["hour" => 17, "minute" => 30],
            "tuat" => ["hour" => 19, "minute" => 30],
            "hoi" => ["hour" => 21, "minute" => 30],
        ];

        return $map[$chi] ?? null;
    }

    public static function genTypes($data)
    {
        $prefix = 'CALENDARGOOD';
        $arr = [];
        if (!empty($data['type'])) {
            if ($data['level'] == 1) {
                foreach (\dataMenu::menus() as $k => $row) {
                    if ($row['level'] != '1' && $row['parent'] == $data['type']) {
                        $arr[] = $prefix . '_' . $row['type'];
                    }
                }
                return implode(',', $arr);
            }

            return $prefix . '_' . $data['type'];
        }

        return '';
    }

    public static function genMenu($menu)
    {
        $data['key'] = [];
        $data['keyValue'] = [];
        foreach ($menu as $k => $row) {
            if (!empty($row['isPost'])) {
                $row['type'] = 'CALENDARGOOD_' . $row['type'];
                $data['key'][] = $row['type'];
                $data['keyValue'][] = ["title" => $row['title'], "type" => $row['type']];
                $data['keyTitle'][$row['type']] = $row['title'];
            }
        }

        return $data;
    }

    public static function timeAgo($createdAt): string
    {
        if (empty($createdAt)) {
            return '';
        }

        $createdAt = Carbon::parse($createdAt);
        $now = Carbon::now();

        $diffMinutes = (int)$createdAt->diffInMinutes($now);
        $diffHours = (int)$createdAt->diffInHours($now);
        $diffDays = (int)$createdAt->diffInDays($now);
        $diffMonths = (int)$createdAt->diffInMonths($now);
        $diffYears = (int)$createdAt->diffInYears($now);

        return match (true) {
            $diffMinutes < 10 => 'Vừa xong',
            $diffMinutes < 60 => "{$diffMinutes} phút trước",
            $diffHours < 24 => "{$diffHours} giờ trước",
            $diffDays < 30 => "{$diffDays} ngày trước",
            $diffMonths < 12 => "{$diffMonths} tháng trước",
            default => "{$diffYears} năm trước",
        };
    }

    public static function formatViews($views): string
    {
        $views = max(0, (int)$views);

        if ($views < 1_000) {
            return number_format($views, 0, ',', '.') . ' lượt xem';
        }

        if ($views < 1_000_000) {
            $value = round($views / 1_000, 1);

            return rtrim(rtrim(number_format($value, 1, ',', ''), '0'), ',')
                . 'K lượt xem';
        }

        if ($views < 1_000_000_000) {
            $value = round($views / 1_000_000, 1);

            return rtrim(rtrim(number_format($value, 1, ',', ''), '0'), ',')
                . ' triệu lượt xem';
        }

        $value = round($views / 1_000_000_000, 1);

        return rtrim(rtrim(number_format($value, 1, ',', ''), '0'), ',')
            . ' tỷ lượt xem';
    }

    public static function getDateFromSlug(string $slug): ?array
    {
        if (!preg_match('/(\d{2})-(\d{2})-(\d{4})$/', $slug, $matches)) {
            return null;
        }

        return [
            'day' => str_pad($matches[1], 2, '0', STR_PAD_LEFT),
            'month' => str_pad($matches[2], 2, '0', STR_PAD_LEFT),
            'year' => $matches[3],
            'date' => sprintf(
                '%02d-%02d-%04d',
                $matches[1],
                $matches[2],
                $matches[3]
            ),
        ];
    }

    public static function checkDateWithinDays(string $date, int $maxDays = 90): int|false
    {
        try {
            $date = Carbon::createFromFormat('d-m-Y', $date)->startOfDay();
            $now = Carbon::now()->startOfDay();

            // Hôm nay hoặc quá khứ => bỏ qua
            if ($date->lte($now)) {
                return true;
            }

            // Ngày tương lai: chỉ cho phép tối đa $maxDays
            return $now->diffInDays($date) <= $maxDays;

        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getKongMingFortune(?string $value): array
    {
        if (empty(trim((string)$value))) {
            return ['', ''];
        }

        $value = strip_tags($value);

        $value = str_replace('Bạch Hổ Đầu', 'Bạch Hổ Đầu:', $value);

        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Chuẩn hóa <br> thành xuống dòng
        $value = preg_replace('/<br\s*\/?>/i', "\n", $value);

        // Bỏ HTML tag nếu có
        $value = strip_tags($value);

        // Chuẩn hóa line ending
        $value = str_replace(["\r\n", "\r"], "\n", $value);

        // Bỏ khoảng trắng thừa đầu/cuối
        $value = trim($value);

        /*
         * Tách:
         * Thiên Đạo (Xấu) Nội dung...
         * Bạch Hổ Đầu (Tốt) Nội dung...
         *
         * Không hard-code Tốt/Xấu để sau này có trạng thái khác vẫn chạy.
         */
        if (preg_match('/^(.+?)\s*\([^)]*\)\s*(.*)$/us', $value, $matches)) {
            $name = trim($matches[1]);
            $content = trim($matches[2]);
        } else {
            // Không xác định được tên
            $name = '';
            $content = $value;
        }

        if ($content === '') {
            return [$name, ''];
        }

        /*
         * CASE 1:
         *
         * Xuất hành cầu tài...
         *
         * Ngày xuất hành theo lịch...
         */
        if (preg_match('/^(.+?)(?:\n\s*\n+)/us', $content, $matches)) {
            $description = trim($matches[1]);

            return [
                $name,
                self::cleanKongMingText($description),
            ];
        }

        /*
         * CASE 2:
         * Có xuống dòng đơn:
         *
         * Xuất hành cầu tài...
         * Ngày xuất hành theo lịch...
         *
         * Chỉ dùng nếu dòng đầu đã kết thúc thành một câu hoàn chỉnh.
         */
        $lines = preg_split('/\n+/', $content);

        if (
            count($lines) > 1
            && preg_match('/[.!?。]$/u', trim($lines[0]))
        ) {
            return [
                $name,
                self::cleanKongMingText($lines[0]),
            ];
        }

        /*
         * CASE 3:
         * Data bị collapse hết xuống dòng:
         *
         * Xuất hành cầu tài...thua.* Ngày xuất hành...
         *
         * hoặc:
         *
         * Xuất hành cầu tài...thua. Ngày xuất hành...
         *
         * => lấy câu hoàn chỉnh đầu tiên.
         */
        if (preg_match('/^(.+?[.!?。])(?:\s|$|\*|◀|▶|•|▪|–|-)/us', $content, $matches)) {
            return [
                $name,
                self::cleanKongMingText($matches[1]),
            ];
        }

        /*
         * CASE 4:
         * Có dấu kết câu nhưng sau dấu câu dính luôn text:
         *
         * "...thua.Ngày xuất hành..."
         *
         * Trường hợp data rất bẩn.
         */
        if (preg_match('/^(.+?[.!?。])/us', $content, $matches)) {
            return [
                $name,
                self::cleanKongMingText($matches[1]),
            ];
        }

        /*
         * CASE 5:
         * Không có dấu câu, không xuống dòng.
         * Không thể xác định ranh giới chính xác,
         * nên trả nguyên nội dung thay vì tự cắt sai.
         */
        return [
            $name,
            self::cleanKongMingText($content),
        ];
    }

    private static function cleanKongMingText(?string $value): string
    {
        if (!$value) {
            return '';
        }

        $value = trim($value);

        // Bỏ các ký hiệu trang trí cuối chuỗi
        $value = preg_replace('/\s*[◀▶]+\s*$/u', '', $value);

        // Gom nhiều khoảng trắng thành 1
        $value = preg_replace('/[ \t]+/u', ' ', $value);

        return trim($value);
    }

    public static function getValueByLabel(?string $html, string $label): string
    {
        if (empty(trim((string)$html))) {
            return '';
        }

        // Decode HTML entities
        $text = html_entity_decode(
            $html,
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );

        // Các tag block => xuống dòng
        $text = preg_replace(
            '/<(?:br\s*\/?|\/p|\/div|\/li|\/pre|\/h[1-6])>/iu',
            "\n",
            $text
        );

        // Bỏ toàn bộ HTML còn lại
        $text = strip_tags($text);

        // Chuẩn hóa xuống dòng
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // Xóa dòng trống/khoảng trắng thừa
        $text = preg_replace('/[ \t]+/u', ' ', $text);
        $text = preg_replace('/\n\s*\n+/u', "\n", $text);

        $text = trim($text);

        // Escape label để dùng trong regex
        $labelRegex = preg_quote($label, '/');

        if (
        preg_match(
            '/(?:^|\n)\s*' . $labelRegex . '\s*:\s*([^\n]+)/iu',
            $text,
            $matches
        )
        ) {
            return trim($matches[1]);
        }

        return '';
    }

    public static function getXungInfo(?string $text): array
    {
        $result = [
            'xungngay' => '',
            'xungthang' => '',
        ];

        if (empty(trim((string)$text))) {
            return $result;
        }

        $text = html_entity_decode(
            $text,
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );

        // Nếu đầu vào có HTML
        $text = preg_replace(
            '/<(?:br\s*\/?|\/p|\/div|\/li|\/pre)>/iu',
            "\n",
            $text
        );

        $text = strip_tags($text);
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        if (preg_match('/Xung\s*ngày\s*:\s*([^\n]+)/iu', $text, $matches)) {
            $result['xungngay'] = trim($matches[1]);
        }

        if (preg_match('/Xung\s*tháng\s*:\s*([^\n]+)/iu', $text, $matches)) {
            $result['xungthang'] = trim($matches[1]);
        }

        return $result;
    }

    public static function getThanHuongInfo(?string $html): array
    {
        if (empty(trim((string)$html))) {
            return [];
        }

        // Decode HTML
        $text = html_entity_decode(
            $html,
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );

        // <br> => xuống dòng
        $text = preg_replace('/<br\s*\/?>/iu', "\n", $text);

        // Các tag block => xuống dòng
        $text = preg_replace(
            '/<\/(?:p|div|li|pre)>/iu',
            "\n",
            $text
        );

        // Bỏ HTML còn lại
        $text = strip_tags($text);

        // Chuẩn hóa xuống dòng
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        $lines = preg_split('/\n+/', trim($text));

        $result = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            // Bỏ -, –, — đầu dòng
            $line = preg_replace('/^\s*[-–—]\s*/u', '', $line);

            // Phải có dấu :
            $colonPosition = strrpos($line, ':');

            if ($colonPosition === false) {
                continue;
            }

            // Phần bên trái và nội dung
            $left = trim(substr($line, 0, $colonPosition));
            $content = trim(substr($line, $colonPosition + 1));

            /*
             * Ví dụ left:
             * Hỷ thần (hướng thần may mắn) - TỐT
             *
             * Chỉ lấy phần trước dấu "("
             * => Hỷ thần
             */
            if (preg_match('/^([^(]+?)(?:\s*\(|\s*[-–—]|$)/u', $left, $matches)) {
                $title = trim($matches[1]);
            } else {
                $title = $left;
            }

            $result[] = [
                'title' => $title,
                'content' => $content,
            ];
        }

        return $result;
    }

    public static function getContentInBrackets(?string $text): string
    {
        if (empty(trim((string)$text))) {
            return '';
        }

        // Bỏ markdown **
        $text = str_replace('**', '', trim($text));

        if (preg_match('/\((.*?)\)/us', $text, $matches)) {
            return trim($matches[1]);
        }

        return '';
    }

    public static function buildCalendar(array $data): array
    {
        if (empty($data)) {
            return [];
        }

        // Đảm bảo đúng thứ tự ngày
        usort($data, function ($a, $b) {
            return strcmp($a['day'], $b['day']);
        });

        $firstDate = Carbon::parse($data[0]['day']);

        // ISO: T2 = 1 ... CN = 7
        $firstDayOfWeek = $firstDate->dayOfWeekIso;

        $cells = [];

        /*
         * Thêm ô trống trước ngày đầu tháng
         *
         * Ví dụ:
         * ngày 1 rơi vào T5
         * => thêm T2, T3, T4 = null
         */
        for ($i = 1; $i < $firstDayOfWeek; $i++) {
            $cells[] = null;
        }

        foreach ($data as $item) {
            $solar = Carbon::parse($item['day']);
            $lunar = Carbon::parse($item['lunarDay']);

            $cells[] = [
                'id' => $item['id'] ?? null,

                'date' => $item['day'],
                'day' => (int)$solar->day,
                'month' => (int)$solar->month,
                'year' => (int)$solar->year,

                'lunarDate' => $item['lunarDay'],
                'lunarDay' => (int)$lunar->day,
                'lunarMonth' => (int)$lunar->month,

                'lunarLabel' => $lunar->day === 1
                    ? $lunar->day . '/' . $lunar->month
                    : (string)$lunar->day,

                'isDay' => (bool)($item['isDay'] ?? false),

                // T2 = 1 ... T7 = 6, CN = 7
                'dayOfWeek' => $solar->dayOfWeekIso,

                'isSaturday' => $solar->dayOfWeekIso === 6,
                'isSunday' => $solar->dayOfWeekIso === 7,
                'isWeekend' => in_array($solar->dayOfWeekIso, [6, 7]),

                'strDay' => $item['strDay'] ?? null,
                'strMonth' => $item['strMonth'] ?? null,
                'strYear' => $item['strYear'] ?? null,

                'options' => $item['options'] ?? [],
            ];
        }

        /*
         * Bổ sung ô trống cuối tháng
         * để tuần cuối luôn đủ 7 cột
         */
        while (count($cells) % 7 !== 0) {
            $cells[] = null;
        }

        // Chia thành từng tuần
        return array_chunk($cells, 7);
    }

    public static function parseEvent($text)
    {
        if (!preg_match('/^(\d{1,2})\/(\d{1,2}):\s*(.+)$/u', trim($text), $matches)) {
            return null;
        }

        return [
            'day' => self::checkNumber((int) $matches[1]),
            'month' => self::checkNumber((int) $matches[2]),
            'value' => trim($matches[3]),
        ];
    }

    public static function checkNumber($number)
    {
        return $number < 10 ? '0' . $number : $number;
    }

}
