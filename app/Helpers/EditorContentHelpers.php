<?php

namespace App\Helpers;

class EditorContentHelpers
{
    public static function render(
        array $row,
        int $day,
        int $month,
        int $year,
        int $lunarDay,
        int $lunarMonth,
        int $lunarYear,
        ?bool $isLeapMonth = null
    ): string {
        $date = self::validDate($day, $month, $year);
        $solarDate = $date->format('d/m/Y');
        $lunarDate = sprintf('%02d/%02d/%04d', $lunarDay, $lunarMonth, $lunarYear);
        $quality = ! empty($row['isDay']) ? 'Hoàng đạo' : 'Hắc đạo';

        $data = [
            'officer' => self::resolved($row, 'DAY_OFFICER'),
            'officer_content' => self::resolved($row, 'DAY_OFFICER', false),
            'solar_term' => self::resolved($row, 'LUNAR', null, 'SOLAR_SEASONS'),
            'five_elements' => self::resolved($row, 'FIVE_ELEMENTS', false),
            'ages' => self::resolved($row, 'INCOMPATIBLE_AGES', false),
            'good_hours' => self::values($row, 'AUSPICIOUS_HOUR'),
            'bad_hours' => self::values($row, 'DARK_HOUR'),
            'good_stars' => self::values($row, 'GOOD_STAR'),
            'bad_stars' => self::values($row, 'BAD_STAR'),
            'taboos' => self::values($row, 'TABOO_DAY'),
            'directions' => self::values($row, 'DIRECTION_OF_TRAVEL'),
            'mansion' => self::values($row, 'LUNAR_MANSION'),
            'departure_day' => self::values($row, 'KONG_MING_FORTUNE_DAY'),
            'peng_zu' => self::values($row, 'PENG_ZU_TABOOS'),
        ];

        $identity = self::identity(
            $date,
            $lunarDate,
            $quality,
            self::clean($row['strDay'] ?? ''),
            self::clean($row['strMonth'] ?? ''),
            self::clean($row['strYear'] ?? '')
        );

        $detailUrl = 'https://lichamtot.com/lich-ngay-'.$date->format('d-m-Y');
        $weekday = self::weekday($date);
        $leapText = $isLeapMonth === null ? null : ($isLeapMonth ? 'Tháng nhuận' : 'Tháng thường');
        $html = '<article class="converted-date-editorial" aria-labelledby="converted-date-title">'
            .'<header class="converted-date-editorial__header">'
            .'<p class="section-label">KIẾN THỨC ÂM DƯƠNG LỊCH</p>'
            .'<h2 id="converted-date-title">Đổi ngày âm dương: tra đúng ngày, đúng tháng nhuận</h2>'
            .'<p>Trong đời sống Việt, một mốc thời gian thường được ghi bằng cả âm lịch và dương lịch. Ngày sinh trên giấy tờ '
            .'dùng dương lịch, trong khi ngày giỗ và nhiều lễ truyền thống lại theo lịch âm. Công cụ của <strong>Lịch Âm Tốt</strong> '
            .'giúp đối chiếu hai cách ghi này trên cùng một kết quả, theo lịch Việt Nam.</p>'
            .'<p>Điểm cần lưu ý là ngày âm không lặp lại ở một ngày dương cố định qua các năm. Vì thế, một kết quả đáng tin cậy '
            .'luôn phải đi kèm năm tra cứu và, khi đổi từ âm sang dương, phải xác định rõ tháng thường hay tháng nhuận.</p>'
            .'</header>';
        $html .= '<section><h3>Kết quả chuyển đổi ngày '.self::e($solarDate).'</h3><p>'.$identity.'</p>'
            .'<dl class="converted-date-editorial__summary"><div><dt>Dương lịch</dt><dd>'.self::e($solarDate).'</dd></div>'
            .'<div><dt>Âm lịch</dt><dd>'.self::e($lunarDate).'</dd></div><div><dt>Ngày trong tuần</dt><dd>'.self::e($weekday).'</dd></div>'
            .($leapText === null ? '' : '<div><dt>Loại tháng âm</dt><dd>'.self::e($leapText).'</dd></div>').'</dl>'
            .'<p>Khi đối chiếu kết quả, cần ghi đủ ngày, tháng, năm và trạng thái tháng nhuận.</p></section>';
        $html .= '<section><h3>Thông tin bổ sung của ngày đã đổi</h3><ul>';
        if ($data['solar_term'] !== '') $html .= '<li><strong>Tiết khí:</strong> '.self::e(self::firstSentence($data['solar_term'])).'</li>';
        if ($data['officer'] !== '') $html .= '<li><strong>Trực ngày:</strong> '.self::e($data['officer']).'</li>';
        $html .= '<li><strong>Tính chất ngày:</strong> '.self::e($quality).'</li></ul></section>';
        $html .= '<p><a href="'.self::e($detailUrl).'">Xem đầy đủ lịch ngày '.self::e($solarDate).'</a></p>';
        $html .= self::conversionGuide().self::conversionFaq();

        return $html.'<footer><p><small>Kết quả được tính theo âm dương lịch Việt Nam. Khi đổi ngày âm sang dương, '
            .'hãy kiểm tra đúng tùy chọn tháng nhuận.</small></p></footer></article>';
    }

    private static function overview(array $data, string $quality, array $verdict): string
    {
        $items = [
            'Tính chất ngày' => $quality,
            'Đánh giá' => $verdict['label'],
            'Trực ngày' => self::firstSentence($data['officer']),
            'Tiết khí' => self::firstSentence($data['solar_term']),
            'Ngũ hành' => self::firstSentence($data['five_elements']),
        ];
        $html = '';
        foreach ($items as $label => $value) {
            if ($value !== '') {
                $html .= '<div><dt>'.self::e($label).'</dt><dd>'.self::e($value).'</dd></div>';
            }
        }

        return $html === '' ? '' : '<section aria-labelledby="day-overview-title">'
            .'<h3 id="day-overview-title">Thông tin quyết định</h3>'
            .'<dl class="converted-date-editorial__summary">'.$html.'</dl></section>';
    }

    private static function workAdvice(array $advice): string
    {
        if ($advice['good'] === [] && $advice['avoid'] === []) {
            return '';
        }
        $html = '<section aria-labelledby="day-work-title"><h3 id="day-work-title">Nên làm và nên tránh</h3>';
        if ($advice['good'] !== []) {
            $html .= '<div class="editorial-advice editorial-advice--good"><h4>Việc có chỉ dấu thuận lợi</h4><ul>'
                .self::listHtml($advice['good']).'</ul></div>';
        }
        if ($advice['avoid'] !== []) {
            $html .= '<div class="editorial-advice editorial-advice--avoid"><h4>Việc cần tránh hoặc cân nhắc</h4><ul>'
                .self::listHtml($advice['avoid']).'</ul></div>';
        }

        return $html.'</section>';
    }

    private static function conflictExplanation(array $data, array $advice, array $verdict): string
    {
        if (! $verdict['mixed']) {
            return '';
        }
        $reasons = [];
        if ($data['taboos'] !== []) {
            $reasons[] = 'có thông tin ngày kỵ';
        }
        if ($data['good_stars'] !== [] && $data['bad_stars'] !== []) {
            $reasons[] = 'sao tốt và sao xấu cùng xuất hiện';
        }
        if ($advice['good'] !== [] && $advice['avoid'] !== []) {
            $reasons[] = 'có nhóm việc được khuyến nghị nhưng cũng có nhóm việc bị hạn chế';
        }

        return $reasons === [] ? '' : '<section aria-labelledby="day-conflict-title">'
            .'<h3 id="day-conflict-title">Vì sao không nên chỉ nhìn Hoàng đạo hoặc Hắc đạo?</h3>'
            .'<p>Dữ liệu cho thấy '.self::e(self::naturalJoin($reasons)).'. Vì vậy cần đánh giá theo từng công việc; '
            .'một yếu tố tốt không tự động hóa giải mọi điều kiêng và một yếu tố xấu cũng không làm toàn bộ ngày trở nên vô dụng.</p></section>';
    }

    private static function timeAndTravel(array $data): string
    {
        $parts = [];
        $labels = [
            'Giờ hoàng đạo' => 'good_hours',
            'Giờ hắc đạo' => 'bad_hours',
            'Hướng xuất hành' => 'directions',
            'Ngày xuất hành' => 'departure_day',
        ];
        foreach ($labels as $label => $key) {
            if ($data[$key] !== []) {
                $parts[] = '<p><strong>'.self::e($label).':</strong> '.self::e(implode('; ', $data[$key])).'</p>';
            }
        }

        return $parts === [] ? '' : '<section aria-labelledby="day-time-title">'
            .'<h3 id="day-time-title">Chọn giờ và hướng xuất hành</h3>'.implode('', $parts).'</section>';
    }

    private static function supportingEvidence(array $data): string
    {
        $groups = [
            'Luận theo Trực ngày' => $data['officer_content'] === '' ? [] : [$data['officer_content']],
            'Tuổi xung khắc' => $data['ages'] === '' ? [] : [$data['ages']],
            'Sao tốt' => $data['good_stars'],
            'Sao xấu' => $data['bad_stars'],
            'Ngày kỵ' => $data['taboos'],
            'Nhị thập bát tú' => $data['mansion'],
            'Bành Tổ bách kỵ' => $data['peng_zu'],
        ];
        $blocks = [];
        foreach ($groups as $title => $values) {
            if ($values !== []) {
                $blocks[] = '<details><summary>'.self::e($title).'</summary>'.self::paragraphs($values).'</details>';
            }
        }

        return $blocks === [] ? '' : '<section aria-labelledby="day-evidence-title">'
            .'<h3 id="day-evidence-title">Dữ liệu dùng để đưa ra nhận định</h3>'
            .'<div class="converted-date-editorial__evidence">'.implode('', $blocks).'</div></section>';
    }

    private static function recommendations(array $data): array
    {
        $good = [];
        $avoid = [];
        $sources = [
            'Trực ngày' => [$data['officer_content']],
            'Sao tốt' => $data['good_stars'],
            'Sao xấu' => $data['bad_stars'],
            'Ngày kỵ' => $data['taboos'],
            'Nhị thập bát tú' => $data['mansion'],
            'Bành Tổ bách kỵ' => $data['peng_zu'],
        ];
        foreach ($sources as $source => $values) {
            foreach ($values as $value) {
                foreach (self::sentences($value) as $sentence) {
                    $lower = mb_strtolower($sentence);
                    $isNegative = preg_match('/\b(kỵ|kiêng|không nên|không hợp|bất lợi|xấu|hung tú|tránh|không khỏi hại)\b/u', $lower) === 1;
                    $isPositive = preg_match('/\b(nên làm|tốt cho|tốt mọi việc|thuận lợi|đại cát|cát lợi)\b/u', $lower) === 1;
                    if ($isNegative) {
                        $avoid[] = self::withSource($sentence, $source);
                    } elseif ($isPositive) {
                        $good[] = self::withSource($sentence, $source);
                    }
                }
            }
        }

        return [
            'good' => array_slice(array_values(array_unique($good)), 0, 8),
            'avoid' => array_slice(array_values(array_unique($avoid)), 0, 8),
        ];
    }

    private static function verdict(bool $auspicious, array $data, array $advice): array
    {
        $caution = $data['taboos'] !== [] || count($advice['avoid']) >= 2;
        $support = $data['good_stars'] !== [] || $advice['good'] !== [];
        $mixed = ($support && $caution) || ($data['good_stars'] !== [] && $data['bad_stars'] !== []);
        if ($mixed) {
            return ['label' => 'Cát hung đan xen', 'mixed' => true,
                'summary' => 'Ngày có yếu tố thuận và hạn chế cùng tồn tại; nên lựa chọn theo đúng loại việc thay vì dùng một kết luận chung.',
                'guidance' => 'Đối chiếu mục nên làm và nên tránh trước, sau đó mới chọn giờ. Không nên dùng riêng một sao tốt hoặc giờ tốt để bỏ qua điều kiêng.'];
        }
        if ($caution || ! $auspicious) {
            return ['label' => 'Nên thận trọng', 'mixed' => false,
                'summary' => 'Dữ liệu của ngày thiên về thận trọng; việc quan trọng cần kiểm tra ngày kỵ, sao xấu và tuổi xung.',
                'guidance' => 'Nếu không thể đổi ngày, tránh đúng nhóm việc bị nêu tên và chọn giờ phù hợp với điều kiện thực tế.'];
        }
        if ($support && $auspicious) {
            return ['label' => 'Có nhiều chỉ dấu thuận', 'mixed' => false,
                'summary' => 'Ngày có các chỉ dấu thuận trong dữ liệu lịch pháp, nhưng mức độ phù hợp vẫn phụ thuộc công việc và người chủ sự.',
                'guidance' => 'Chỉ áp dụng cho nhóm việc được dữ liệu nêu phù hợp; tiếp tục kiểm tra tuổi xung, giờ và hướng.'];
        }

        return ['label' => 'Chưa đủ dữ kiện để kết luận rộng', 'mixed' => false,
            'summary' => 'Dữ liệu hiện có chưa tạo thành kết luận mạnh cho mọi loại công việc; trang chỉ trình bày những chỉ dấu xác định được.',
            'guidance' => 'Không suy rộng từ một dữ kiện đơn lẻ và nên ưu tiên điều kiện thực tế.'];
    }

    private static function identity(\DateTimeImmutable $date, string $lunar, string $quality, string ...$canChi): string
    {
        $text = 'Ngày dương lịch <time datetime="'.$date->format('Y-m-d').'">'.$date->format('d/m/Y').'</time>'
            .' tương ứng <strong>'.self::e($lunar).' âm lịch</strong>, thuộc <strong>'.self::e($quality).'</strong>.';
        $canChi = array_values(array_filter($canChi));

        return $text.($canChi === [] ? '' : ' Can Chi ngày, tháng và năm lần lượt là <strong>'
                .self::e(implode(' – ', $canChi)).'</strong>.');
    }

    private static function values(array $row, string $key): array
    {
        $source = $row['options'][$key] ?? [];
        if ($source instanceof \Traversable) {
            $source = iterator_to_array($source);
        }
        if (! is_array($source)) {
            return [];
        }
        $result = [];
        foreach ($source as $item) {
            $value = is_array($item) ? ($item['value'] ?? '') : (is_object($item) ? ($item->value ?? '') : $item);
            if (($value = self::normalizeOptionValue($key, $value)) !== '') {
                $result[] = $value;
            }
        }

        return array_values(array_unique($result));
    }

    private static function resolved(array $row, string $sourceKey, ?bool $copy = null, ?string $lookupKey = null): string
    {
        $source = $row['options'][$sourceKey] ?? [];
        if (empty($source)) {
            return '';
        }
        $key = $lookupKey ?? $sourceKey;
        $value = $copy === null ? Helpers::getCopeValueByOption($source, $key)
            : Helpers::getCopeValueByOption($source, $key, $copy);

        return self::clean($value);
    }

    private static function sentences(string $text): array
    {
        $text = self::clean($text);
        $parts = $text === '' ? [] : (preg_split('/(?<=[.!?])\s+|\s*[–—]\s*/u', $text) ?: [$text]);

        return array_values(array_filter(array_map('trim', $parts), static fn (string $item): bool => mb_strlen($item) >= 12));
    }

    private static function firstSentence(string $text): string
    {
        return self::sentences($text)[0] ?? self::clean($text);
    }

    private static function withSource(string $text, string $source): string
    {
        return self::clean($text).' (theo '.mb_strtolower($source).')';
    }

    private static function listHtml(array $items): string
    {
        return implode('', array_map(static fn (string $item): string => '<li>'.self::e($item).'</li>', $items));
    }

    private static function paragraphs(array $items): string
    {
        return implode('', array_map(static fn (string $item): string => '<p>'.self::e($item).'</p>', $items));
    }

    private static function naturalJoin(array $items): string
    {
        if (count($items) < 2) {
            return $items[0] ?? '';
        }
        $last = array_pop($items);

        return implode(', ', $items).' và '.$last;
    }

    private static function clean(mixed $value): string
    {
        $text = (string) $value;
        $text = preg_replace('/<br\s*\/?>|<\/(?:p|div|li|tr|h[1-6])>/iu', ' ', $text) ?: $text;
        $text = str_replace('(//)', ': ', $text);
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace('/\s+/u', ' ', $text) ?: '');
    }

    private static function normalizeOptionValue(string $key, mixed $value): string
    {
        $raw = (string) $value;
        if (in_array($key, ['AUSPICIOUS_HOUR', 'DARK_HOUR'], true)) {
            $hour = Helpers::matchHour(self::clean($raw));
            if ($hour['title'] !== '' && $hour['hour'] !== '') {
                $suffix = preg_replace('/^.*?\([^)]*\)\s*:?\s*/u', '', self::clean($raw)) ?: '';

                return trim($hour['title'].' ('.$hour['hour'].')'.($suffix !== '' ? ': '.$suffix : ''));
            }
        }
        if ($key === 'PENG_ZU_TABOOS') {
            $parts = explode('(//)', $raw, 2);
            $content = self::clean($parts[1] ?? $parts[0] ?? '');
            if (preg_match('/(Ngày\s.+)$/u', $content, $match)) {
                return trim($match[1]);
            }

            return $content;
        }
        if ($key === 'LUNAR_MANSION') {
            return self::normalizeLunarMansion($raw);
        }

        return self::clean($raw);
    }

    private static function normalizeLunarMansion(string $value): string
    {
        $text = self::clean($value);
        if ($text === '') {
            return '';
        }
        if (preg_match('/^(.*?(?:Ngoại lệ\s*:.*?\.|Kiêng cữ\s*:.*?\.))/u', $text, $match)) {
            return trim($match[1]);
        }

        return $text;
    }

    private static function conversionGuide(): string
    {
        return '<section class="converted-date-editorial__guide" aria-labelledby="conversion-guide-title">'
            .'<h3 id="conversion-guide-title">Khi nào cần đổi ngày âm sang dương hoặc dương sang âm?</h3>'
            .'<p>Nếu đã có ngày trên giấy khai sinh, căn cước, hợp đồng hoặc lịch làm việc và muốn biết ngày âm tương ứng, hãy '
            .'chọn chiều dương sang âm. Đây cũng là cách thường dùng để bổ sung ngày sinh âm lịch hoặc xác định một dịp lễ '
            .'truyền thống từ mốc dương đã biết.</p>'
            .'<p>Chiều âm sang dương phù hợp với những ngày được gia đình ghi nhớ theo âm lịch, chẳng hạn ngày giỗ, ngày lễ '
            .'họ hoặc ngày sinh âm. Do ngày dương tương ứng thay đổi từng năm, người dùng nên tra lại cho đúng năm tổ chức '
            .'thay vì sử dụng kết quả của năm trước.</p>'

            .'<h3>Tháng nhuận là dữ kiện không thể bỏ qua</h3>'
            .'<p>Âm lịch Việt Nam có những năm xuất hiện thêm một tháng để giữ nhịp lịch phù hợp với chu kỳ mùa. Tháng được '
            .'thêm này gọi là tháng nhuận. Nó không phải một tháng có thêm ngày, mà là một tháng riêng, có thể mang cùng số '
            .'với tháng đứng trước. Vì vậy “ngày 10 tháng 4” và “ngày 10 tháng 4 nhuận” là hai mốc khác nhau.</p>'
            .'<p>Khi năm tra cứu không có tháng nhuận tương ứng, tùy chọn này không cần sử dụng. Khi năm có tháng nhuận, '
            .'việc chọn nhầm trạng thái tháng sẽ làm kết quả lệch sang một thời điểm khác, dù ngày và năm nhập vào vẫn đúng.</p>'

            .'<h3>Đọc kết quả thế nào để không nhầm?</h3>'
            .'<p>Trước tiên, hãy kiểm tra đủ ba thành phần ngày, tháng và năm ở cả hai dòng âm lịch và dương lịch. Nếu đang '
            .'đối chiếu một sự kiện đã biết, thứ trong tuần là dấu hiệu kiểm tra hữu ích. Với ngày cần ghi nhớ lâu dài, nên ghi '
            .'rõ hệ lịch ngay sau ngày tháng, ví dụ “12/08/2026 âm lịch”, thay vì chỉ lưu một dãy số.</p>'
            .'<p>Can Chi, tiết khí và Trực ngày được hiển thị như thông tin mở rộng của mốc vừa tra. Chúng không làm thay đổi '
            .'kết quả chuyển đổi. Tương tự, nhãn Hoàng đạo hoặc Hắc đạo không có nghĩa ngày đó phù hợp hoặc không phù hợp với '
            .'mọi công việc.</p>'

            .'<h3>Vì sao kết quả có thể khác một số lịch nước ngoài?</h3>'
            .'<p>Thời điểm bắt đầu ngày và tháng âm phụ thuộc quy ước lịch cùng múi giờ áp dụng. Một công cụ dùng dữ liệu cho '
            .'quốc gia khác có thể cho kết quả không trùng hoàn toàn với lịch Việt Nam, nhất là ở thời điểm sát ngày đầu tháng. '
            .'Trên Lịch Âm Tốt, kết quả được trình bày theo lịch Việt Nam và múi giờ GMT+7.</p>'

            .'<h3>Đổi ngày không đồng nghĩa với xem ngày tốt xấu</h3>'
            .'<p>Chức năng này trả lời một câu hỏi cụ thể: ngày âm và ngày dương nào tương ứng với nhau. Nếu cần chọn ngày cưới, '
            .'khai trương, động thổ hoặc xuất hành, người dùng nên mở trang lịch ngày từ liên kết phía trên. Phần đó mới là nơi '
            .'đối chiếu Trực, tuổi xung, sao tốt, sao xấu và mục đích công việc.</p>'
            .'</section>';
    }

    private static function conversionFaq(): string
    {
        return '<section class="converted-date-editorial__faq" aria-labelledby="conversion-faq-title">'
            .'<h3 id="conversion-faq-title">Câu hỏi thường gặp khi đổi ngày âm dương</h3>'
            .'<details><summary>Muốn biết hôm nay là ngày bao nhiêu âm lịch thì làm thế nào?</summary><p>Chọn ngày hiện tại '
            .'ở phần dương lịch. Công cụ sẽ trả về ngày, tháng và năm âm tương ứng theo lịch Việt Nam.</p></details>'
            .'<details><summary>Vì sao ngày tương ứng thay đổi theo từng năm?</summary><p>Tháng âm theo chu kỳ Mặt Trăng, '
            .'còn năm dương theo chu kỳ Mặt Trời. Hai chu kỳ có độ dài khác nhau nên ngày tương ứng dịch chuyển qua mỗi năm.</p></details>'
            .'<details><summary>Đổi ngày âm sang dương có cần chọn tháng nhuận không?</summary><p>Có. Nếu năm có hai tháng '
            .'âm cùng số, chọn sai tháng thường hoặc tháng nhuận sẽ cho ra một ngày dương khác.</p></details>'
            .'<details><summary>Kết quả chuyển đổi có đồng nghĩa với ngày tốt không?</summary><p>Không. Chuyển đổi lịch chỉ '
            .'xác định hai mốc tương ứng; đánh giá tốt xấu cần thực hiện riêng trên trang lịch ngày.</p></details>'
            .'<details><summary>Nên lưu ngày giỗ như thế nào để không phải tra lại nguồn?</summary><p>Nếu gia đình tổ chức '
            .'theo âm lịch, hãy lưu ngày âm gốc và ghi rõ tháng nhuận nếu có. Ngày dương nên được tra lại theo từng năm tổ chức.</p></details>'
            .'</section>';
    }

    private static function weekday(\DateTimeImmutable $date): string
    {
        $names = [1 => 'Thứ Hai', 2 => 'Thứ Ba', 3 => 'Thứ Tư', 4 => 'Thứ Năm', 5 => 'Thứ Sáu', 6 => 'Thứ Bảy', 7 => 'Chủ Nhật'];

        return $names[(int) $date->format('N')];
    }

    private static function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private static function validDate(int $day, int $month, int $year): \DateTimeImmutable
    {
        $date = \DateTimeImmutable::createFromFormat('!Y-n-j', "{$year}-{$month}-{$day}");
        $errors = \DateTimeImmutable::getLastErrors();
        if (! $date || ($errors !== false && ($errors['warning_count'] || $errors['error_count']))
            || (int) $date->format('j') !== $day || (int) $date->format('n') !== $month
            || (int) $date->format('Y') !== $year) {
            throw new \InvalidArgumentException('Ngày dương lịch không hợp lệ.');
        }

        return $date;
    }
}
