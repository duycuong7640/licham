<?php

namespace App\Helpers;

use Carbon\Carbon;

class ZodiacHelper
{
    /**
     * Danh sách 12 con giáp theo thứ tự Địa chi.
     */
    private const ZODIACS = [
        'Tý',
        'Sửu',
        'Dần',
        'Mão',
        'Thìn',
        'Tị',
        'Ngọ',
        'Mùi',
        'Thân',
        'Dậu',
        'Tuất',
        'Hợi',
    ];

    /**
     * Slug của 12 con giáp.
     *
     * Tý dùng "ty", Tị dùng "ty-ran" để tránh trùng slug.
     */
    private const ZODIAC_SLUGS = [
        'Tý' => 'ty',
        'Sửu' => 'suu',
        'Dần' => 'dan',
        'Mão' => 'mao',
        'Thìn' => 'thin',
        'Tị' => 'ti',
        'Ngọ' => 'ngo',
        'Mùi' => 'mui',
        'Thân' => 'than',
        'Dậu' => 'dau',
        'Tuất' => 'tuat',
        'Hợi' => 'hoi',
    ];

    /**
     * Bốn nhóm Tam hợp.
     */
    private const ZODIAC_TAM_HOP_GROUPS = [
        ['Thân', 'Tý', 'Thìn'],
        ['Tị', 'Dậu', 'Sửu'],
        ['Dần', 'Ngọ', 'Tuất'],
        ['Hợi', 'Mão', 'Mùi'],
    ];

    /**
     * Sáu cặp Lục hợp.
     */
    private const ZODIAC_LUC_HOP_PAIRS = [
        ['Tý', 'Sửu'],
        ['Dần', 'Hợi'],
        ['Mão', 'Tuất'],
        ['Thìn', 'Dậu'],
        ['Tị', 'Thân'],
        ['Ngọ', 'Mùi'],
    ];

    /**
     * Sáu cặp Lục xung.
     */
    private const ZODIAC_LUC_XUNG_PAIRS = [
        ['Tý', 'Ngọ'],
        ['Sửu', 'Mùi'],
        ['Dần', 'Thân'],
        ['Mão', 'Dậu'],
        ['Thìn', 'Tuất'],
        ['Tị', 'Hợi'],
    ];

    /**
     * Sáu cặp Lục hại.
     */
    private const ZODIAC_LUC_HAI_PAIRS = [
        ['Tý', 'Mùi'],
        ['Sửu', 'Ngọ'],
        ['Dần', 'Tị'],
        ['Mão', 'Thìn'],
        ['Thân', 'Hợi'],
        ['Dậu', 'Tuất'],
    ];

    /**
     * Các cặp Tương phá.
     */
    private const ZODIAC_TUONG_PHA_PAIRS = [
        ['Tý', 'Dậu'],
        ['Mão', 'Ngọ'],
        ['Thìn', 'Sửu'],
        ['Tuất', 'Mùi'],
        ['Dần', 'Hợi'],
        ['Tị', 'Thân'],
    ];

    /**
     * Các cặp Tương hình khác chi.
     */
    private const ZODIAC_TUONG_HINH_PAIRS = [
        ['Tý', 'Mão'],

        ['Dần', 'Tị'],
        ['Tị', 'Thân'],
        ['Thân', 'Dần'],

        ['Sửu', 'Tuất'],
        ['Tuất', 'Mùi'],
        ['Mùi', 'Sửu'],
    ];

    /**
     * Bốn Địa chi tự hình khi gặp chính nó.
     */
    private const ZODIAC_TU_HINH_BRANCHES = [
        'Thìn',
        'Ngọ',
        'Dậu',
        'Hợi',
    ];

    /**
     * Cấu hình quan hệ và điểm số.
     *
     * Điểm số là quy ước dùng cho hệ thống website.
     */
    private const ZODIAC_RELATION_CONFIG = [
        'luc_hop' => [
            'label' => 'Lục hợp',
            'impact' => 'positive',
            'score' => 90,
        ],

        'tam_hop' => [
            'label' => 'Tam hợp',
            'impact' => 'positive',
            'score' => 78,
        ],

        'neutral' => [
            'label' => 'Bình hòa',
            'impact' => 'neutral',
            'score' => 60,
        ],

        'nam_tuoi' => [
            'label' => 'Năm tuổi',
            'impact' => 'warning',
            'score' => 50,
        ],

        'tu_hinh' => [
            'label' => 'Năm tuổi, tự hình',
            'impact' => 'negative',
            'score' => 35,
        ],

        'tuong_pha' => [
            'label' => 'Tương phá',
            'impact' => 'negative',
            'score' => 45,
        ],

        'tuong_hinh' => [
            'label' => 'Tương hình',
            'impact' => 'negative',
            'score' => 40,
        ],

        'luc_hai' => [
            'label' => 'Lục hại',
            'impact' => 'negative',
            'score' => 35,
        ],

        'luc_xung' => [
            'label' => 'Lục xung',
            'impact' => 'negative',
            'score' => 20,
        ],
    ];

    /**
     * Lấy đầy đủ xếp hạng 12 con giáp theo năm.
     *
     * Danh sách bắt đầu từ con giáp của năm đang xét.
     * Những con giáp phía sau sẽ lấy năm tương lai trong vòng 12 con giáp.
     *
     * Ví dụ năm 2026 là năm Ngọ:
     *
     * Ngọ  => [2026, 2014, 2002]
     * Mùi  => [2027, 2015, 2003]
     * ...
     * Tý   => [2032, 2020, 2008]
     * ...
     * Tị   => [2037, 2025, 2013]
     *
     * @param int|null $lunarYear Năm âm lịch đang xét.
     * @param int $yearLimit Số năm trả về cho mỗi con giáp.
     */
    public static function getZodiacYearRanking(
        ?int $lunarYear = null,
        int $yearLimit = 3
    ): array
    {
        $isAutoYear = $lunarYear === null;

        /*
         * Nếu không truyền năm, hệ thống tạm lấy năm hiện tại.
         *
         * Trước Tết âm lịch, nên truyền chính xác năm âm lịch
         * từ bộ chuyển đổi âm lịch của hệ thống.
         */
        $lunarYear = $lunarYear
            ?? (int)Carbon::now('Asia/Ho_Chi_Minh')->format('Y');

        self::validateZodiacYear($lunarYear);
        self::validateZodiacYearLimit($yearLimit);

        $yearZodiac = self::getZodiacByYear($lunarYear);

        $zodiacOrder = self::getZodiacOrderFromYear(
            $lunarYear
        );

        $data = [];
        $items = [];

        foreach ($zodiacOrder as $displayIndex => $zodiac) {
            $data[] = self::buildZodiacYearItem(
                $zodiac,
                $lunarYear,
                $yearZodiac,
                $yearLimit,
                $displayIndex
            );
        }

        foreach ($data as $row) {
            $items['tuoi-' . $row['slug']] = $row;
        }

        return [
            'success' => true,

            'lunar_year' => $lunarYear,

            'is_auto_year' => $isAutoYear,

            'year_zodiac' => $yearZodiac,

            'year_zodiac_slug' => self::ZODIAC_SLUGS[$yearZodiac],

            'year_limit' => $yearLimit,

            'zodiac_order' => $zodiacOrder,

            'title' => sprintf(
                'Xếp hạng 12 con giáp trong năm %d - năm %s',
                $lunarYear,
                $yearZodiac
            ),

            'description' => sprintf(
                'Mức độ tương hợp của 12 con giáp trong năm %d, bắt đầu từ tuổi %s.',
                $lunarYear,
                $yearZodiac
            ),

            'method' => implode(' ', [
                'Kết quả được xác định dựa trên quan hệ giữa',
                'Địa chi của tuổi và Địa chi của năm, gồm',
                'Lục hợp, Tam hợp, Lục xung, Lục hại,',
                'Tương phá, Tương hình và Năm tuổi.',
            ]),

            'year_rule' => implode(' ', [
                'Danh sách bắt đầu từ con giáp của năm đang xét.',
                'Các con giáp tiếp theo sử dụng năm hiện tại hoặc năm tương lai',
                'trong cùng một vòng 12 con giáp.',
                'Các năm còn lại được lùi dần, mỗi lần 12 năm.',
            ]),

            'disclaimer' => implode(' ', [
                'Nội dung chỉ mang tính tham khảo.',
                'Điểm số và mức xếp hạng là quy ước của hệ thống,',
                'không phải kết luận tử vi cá nhân.',
            ]),

            'items' => $items,
        ];
    }

    /**
     * Lấy kết quả của một con giáp trong năm.
     */
    public static function getZodiacYearItem(
        int $lunarYear,
        string $zodiac,
        int $yearLimit = 3
    ): array
    {
        self::validateZodiacYear($lunarYear);
        self::validateZodiac($zodiac);
        self::validateZodiacYearLimit($yearLimit);

        $yearZodiac = self::getZodiacByYear(
            $lunarYear
        );

        $zodiacOrder = self::getZodiacOrderFromYear(
            $lunarYear
        );

        $displayIndex = array_search(
            $zodiac,
            $zodiacOrder,
            true
        );

        if ($displayIndex === false) {
            $displayIndex = 0;
        }

        return self::buildZodiacYearItem(
            $zodiac,
            $lunarYear,
            $yearZodiac,
            $yearLimit,
            (int)$displayIndex
        );
    }

    /**
     * Xác định con giáp theo năm âm lịch.
     *
     * Mốc:
     * 2020 = Tý
     * 2025 = Tị
     * 2026 = Ngọ
     */
    public static function getZodiacByYear(
        int $lunarYear
    ): string
    {
        self::validateZodiacYear($lunarYear);

        /*
         * Modulo dương để hoạt động cả với năm trước 2020.
         */
        $index = (
                ($lunarYear - 2020) % 12 + 12
            ) % 12;

        return self::ZODIACS[$index];
    }

    /**
     * Lấy các năm gần nhất của một con giáp.
     *
     * Năm đầu tiên nằm trong vòng 12 con giáp
     * bắt đầu từ năm đang xét.
     *
     * Các năm sau lùi dần 12 năm.
     */
    public static function getZodiacCycleYears(
        string $zodiac,
        int $lunarYear,
        int $limit = 3
    ): array
    {
        self::validateZodiac($zodiac);
        self::validateZodiacYear($lunarYear);
        self::validateZodiacYearLimit($limit);

        $currentZodiac = self::getZodiacByYear(
            $lunarYear
        );

        $currentIndex = array_search(
            $currentZodiac,
            self::ZODIACS,
            true
        );

        $targetIndex = array_search(
            $zodiac,
            self::ZODIACS,
            true
        );

        if (
            $currentIndex === false
            || $targetIndex === false
        ) {
            throw new \RuntimeException(
                'Không xác định được vị trí con giáp.'
            );
        }

        /*
         * Khoảng cách tiến từ con giáp năm hiện tại
         * đến con giáp cần tìm.
         *
         * Ví dụ năm Ngọ:
         *
         * Ngọ = 0
         * Mùi = 1
         * Hợi = 5
         * Tý  = 6
         * Tị  = 11
         */
        $forwardOffset = (
                $targetIndex
                - $currentIndex
                + 12
            ) % 12;

        $firstYear = $lunarYear + $forwardOffset;

        $years = [];

        for ($i = 0; $i < $limit; $i++) {
            $years[] = $firstYear - ($i * 12);
        }

        return $years;
    }

    /**
     * Sắp xếp con giáp bắt đầu từ con giáp của năm đang xét.
     */
    private static function getZodiacOrderFromYear(
        int $lunarYear
    ): array
    {
        $yearZodiac = self::getZodiacByYear(
            $lunarYear
        );

        $startIndex = array_search(
            $yearZodiac,
            self::ZODIACS,
            true
        );

        if ($startIndex === false) {
            throw new \RuntimeException(
                'Không xác định được thứ tự con giáp.'
            );
        }

        return array_values(
            array_merge(
                array_slice(
                    self::ZODIACS,
                    $startIndex
                ),
                array_slice(
                    self::ZODIACS,
                    0,
                    $startIndex
                )
            )
        );
    }

    /**
     * Tạo dữ liệu đầy đủ cho từng con giáp.
     */
    private static function buildZodiacYearItem(
        string $zodiac,
        int $lunarYear,
        string $yearZodiac,
        int $yearLimit,
        int $displayIndex
    ): array
    {
        $relationResult = self::resolveZodiacRelations(
            $zodiac,
            $yearZodiac
        );

        $primaryRelation = $relationResult['primary_relation'];
        $relations = $relationResult['relations'];

        $score = (int)$primaryRelation['score'];

        $rank = self::getZodiacRankByScore(
            $score
        );

        $simpleRank = self::getZodiacSimpleRank(
            $rank['code']
        );

        $years = self::getZodiacCycleYears(
            $zodiac,
            $lunarYear,
            $yearLimit
        );

        $relationLabels = array_column(
            $relations,
            'label'
        );

        $isWarning = self::hasWarningRelation(
            $relations
        );

        return [
            'display_index' => $displayIndex,

            'zodiac' => $zodiac,

            'slug' => self::ZODIAC_SLUGS[$zodiac],

            'lunar_year' => $lunarYear,

            'year_zodiac' => $yearZodiac,

            /*
             * Danh sách năm của con giáp.
             */
            'years' => $years,

            /*
             * Năm đầu tiên trong vòng 12 con giáp đang xét.
             */
            'cycle_year' => $years[0],

            /*
             * Chuỗi năm dùng hiển thị giao diện.
             */
            'year_text' => implode(
                ', ',
                $years
            ),

            /*
             * Năm đầu có nằm trong tương lai hay không.
             */
            'is_future_cycle_year' => (
                $years[0] > $lunarYear
            ),

            /*
             * Số năm tính từ năm đang xét đến cycle_year.
             */
            'year_offset' => (
                $years[0] - $lunarYear
            ),

            /*
             * Có phải con giáp của năm đang xét hay không.
             */
            'is_year_age' => (
                $zodiac === $yearZodiac
            ),

            'score' => $score,

            'rank_code' => $rank['code'],

            'rank_label' => $rank['label'],

            /*
             * Dùng khi frontend chỉ hiển thị:
             * Đại Cát, Cát, Bình.
             */
            'simple_rank_code' => $simpleRank['code'],

            'simple_rank_label' => $simpleRank['label'],

            /*
             * Quan hệ chính dùng để chấm điểm.
             */
            'primary_relation' => $primaryRelation,

            'primary_relation_code' => $primaryRelation['code'],

            'primary_relation_label' => $primaryRelation['label'],

            /*
             * Toàn bộ quan hệ phát hiện được.
             */
            'relations' => $relations,

            'relation_summary' => implode(
                ', ',
                $relationLabels
            ),

            'is_warning' => $isWarning,

            'title' => sprintf(
                'Tuổi %s trong năm %s: %s',
                $zodiac,
                $yearZodiac,
                $rank['label']
            ),

            'short_title' => sprintf(
                'Tuổi %s: %s',
                $zodiac,
                $rank['label']
            ),

            'description' => self::buildZodiacDescription(
                $zodiac,
                $yearZodiac,
                $rank['label'],
                $primaryRelation,
                $relations
            ),

            'advice' => self::buildZodiacAdvice(
                $primaryRelation['code'],
                $rank['code']
            ),

            'warning' => self::buildZodiacWarning(
                $primaryRelation['code']
            ),

            'disclaimer' => implode(' ', [
                'Thông tin mang tính tham khảo.',
                'Kết quả thực tế còn phụ thuộc vào',
                'nhiều yếu tố cá nhân khác.',
            ]),
        ];
    }

    /**
     * Phát hiện các quan hệ giữa tuổi và Địa chi của năm.
     */
    private static function resolveZodiacRelations(
        string $zodiac,
        string $yearZodiac
    ): array
    {
        $relationCodes = [];

        /*
         * Cùng con giáp với năm.
         */
        if ($zodiac === $yearZodiac) {
            $relationCodes[] = 'nam_tuoi';

            if (
            in_array(
                $zodiac,
                self::ZODIAC_TU_HINH_BRANCHES,
                true
            )
            ) {
                $relationCodes[] = 'tu_hinh';
            }
        } else {
            if (
            self::isZodiacPair(
                $zodiac,
                $yearZodiac,
                self::ZODIAC_LUC_HOP_PAIRS
            )
            ) {
                $relationCodes[] = 'luc_hop';
            }

            if (
            self::isZodiacSameGroup(
                $zodiac,
                $yearZodiac,
                self::ZODIAC_TAM_HOP_GROUPS
            )
            ) {
                $relationCodes[] = 'tam_hop';
            }

            if (
            self::isZodiacPair(
                $zodiac,
                $yearZodiac,
                self::ZODIAC_LUC_XUNG_PAIRS
            )
            ) {
                $relationCodes[] = 'luc_xung';
            }

            if (
            self::isZodiacPair(
                $zodiac,
                $yearZodiac,
                self::ZODIAC_LUC_HAI_PAIRS
            )
            ) {
                $relationCodes[] = 'luc_hai';
            }

            if (
            self::isZodiacPair(
                $zodiac,
                $yearZodiac,
                self::ZODIAC_TUONG_PHA_PAIRS
            )
            ) {
                $relationCodes[] = 'tuong_pha';
            }

            if (
            self::isZodiacPair(
                $zodiac,
                $yearZodiac,
                self::ZODIAC_TUONG_HINH_PAIRS
            )
            ) {
                $relationCodes[] = 'tuong_hinh';
            }
        }

        $relationCodes = array_values(
            array_unique($relationCodes)
        );

        if (empty($relationCodes)) {
            $relationCodes[] = 'neutral';
        }

        $relations = [];

        foreach ($relationCodes as $code) {
            $config = self::ZODIAC_RELATION_CONFIG[$code];

            $relations[] = [
                'code' => $code,
                'label' => $config['label'],
                'impact' => $config['impact'],
                'score' => $config['score'],
            ];
        }

        return [
            'relations' => $relations,

            'primary_relation' => self::getPrimaryZodiacRelation(
                $relations
            ),
        ];
    }

    /**
     * Chọn quan hệ chính để chấm điểm.
     *
     * Một số cặp Địa chi có thể đồng thời xuất hiện
     * trong nhiều nhóm nên cần quy định thứ tự ưu tiên.
     */
    private static function getPrimaryZodiacRelation(
        array $relations
    ): array
    {
        $priority = [
            'tu_hinh',
            'nam_tuoi',
            'luc_xung',
            'luc_hai',
            'luc_hop',
            'tam_hop',
            'tuong_hinh',
            'tuong_pha',
            'neutral',
        ];

        foreach ($priority as $priorityCode) {
            foreach ($relations as $relation) {
                if (
                    $relation['code'] === $priorityCode
                ) {
                    return $relation;
                }
            }
        }

        return [
            'code' => 'neutral',
            'label' => 'Bình hòa',
            'impact' => 'neutral',
            'score' => 60,
        ];
    }

    /**
     * Xếp hạng theo điểm.
     */
    private static function getZodiacRankByScore(
        int $score
    ): array
    {
        if ($score >= 85) {
            return [
                'code' => 'dai_cat',
                'label' => 'Đại Cát',
            ];
        }

        if ($score >= 70) {
            return [
                'code' => 'cat',
                'label' => 'Cát',
            ];
        }

        if ($score >= 50) {
            return [
                'code' => 'binh',
                'label' => 'Bình',
            ];
        }

        if ($score >= 30) {
            return [
                'code' => 'hung',
                'label' => 'Hung',
            ];
        }

        return [
            'code' => 'dai_hung',
            'label' => 'Đại Hung',
        ];
    }

    /**
     * Quy đổi sang ba mức đơn giản:
     * Đại Cát, Cát, Bình.
     */
    private static function getZodiacSimpleRank(
        string $rankCode
    ): array
    {
        if ($rankCode === 'dai_cat') {
            return [
                'code' => 'dai_cat',
                'label' => 'Đại Cát',
            ];
        }

        if ($rankCode === 'cat') {
            return [
                'code' => 'cat',
                'label' => 'Cát',
            ];
        }

        if ($rankCode === 'binh') {
            return [
                'code' => 'binh',
                'label' => 'Bình',
            ];
        }

        return [
            'code' => 'hung',
            'label' => 'Xấu',
        ];
    }

    /**
     * Tạo mô tả cho từng con giáp.
     */
    private static function buildZodiacDescription(
        string $zodiac,
        string $yearZodiac,
        string $rankLabel,
        array $primaryRelation,
        array $relations
    ): string
    {
        $relationLabels = array_column(
            $relations,
            'label'
        );

        $baseText = sprintf(
            'Tuổi %s gặp năm %s được hệ thống xếp mức %s.',
            $zodiac,
            $yearZodiac,
            $rankLabel
        );

        $relationText = sprintf(
            'Quan hệ Địa chi ghi nhận: %s.',
            implode(', ', $relationLabels)
        );

        switch ($primaryRelation['code']) {
            case 'luc_hop':
                $detail = implode(' ', [
                    'Đây là quan hệ Lục hợp,',
                    'được đánh giá có nhiều yếu tố tương trợ',
                    'và thuận lợi trong năm.',
                ]);
                break;

            case 'tam_hop':
                $detail = implode(' ', [
                    'Đây là quan hệ Tam hợp,',
                    'thể hiện sự hòa hợp và có một số',
                    'yếu tố hỗ trợ tích cực.',
                ]);
                break;

            case 'luc_xung':
                $detail = implode(' ', [
                    'Đây là quan hệ Lục xung,',
                    'có thể xuất hiện nhiều biến động',
                    'và những vấn đề cần thận trọng.',
                ]);
                break;

            case 'luc_hai':
                $detail = implode(' ', [
                    'Đây là quan hệ Lục hại,',
                    'nên lưu ý các mối quan hệ,',
                    'kế hoạch và quyết định quan trọng.',
                ]);
                break;

            case 'tuong_pha':
                $detail = implode(' ', [
                    'Đây là quan hệ Tương phá,',
                    'có thể phát sinh một số trở ngại',
                    'hoặc thay đổi ngoài dự tính.',
                ]);
                break;

            case 'tuong_hinh':
                $detail = implode(' ', [
                    'Đây là quan hệ Tương hình,',
                    'nên kiểm soát cảm xúc và tránh',
                    'những quyết định nóng vội.',
                ]);
                break;

            case 'tu_hinh':
                $detail = implode(' ', [
                    'Đây là năm tuổi đồng thời có yếu tố tự hình,',
                    'nên ưu tiên sự ổn định',
                    'và chủ động kiểm soát rủi ro.',
                ]);
                break;

            case 'nam_tuoi':
                $detail = implode(' ', [
                    'Đây là năm tuổi,',
                    'có thể xuất hiện nhiều thay đổi.',
                    'Nên chuẩn bị kế hoạch rõ ràng',
                    'và giữ sự chủ động.',
                ]);
                break;

            case 'neutral':
            default:
                $detail = implode(' ', [
                    'Giữa tuổi và Địa chi của năm',
                    'không có quan hệ nổi bật',
                    'trong bộ quy tắc đang áp dụng.',
                ]);
                break;
        }

        return implode(
            ' ',
            [
                $baseText,
                $relationText,
                $detail,
            ]
        );
    }

    /**
     * Tạo lời khuyên.
     */
    private static function buildZodiacAdvice(
        string $relationCode,
        string $rankCode
    ): string
    {
        switch ($relationCode) {
            case 'luc_hop':
                return implode(' ', [
                    'Nên chủ động tận dụng cơ hội,',
                    'mở rộng hợp tác và triển khai',
                    'các kế hoạch phù hợp.',
                ]);

            case 'tam_hop':
                return implode(' ', [
                    'Có thể phát huy thế mạnh cá nhân,',
                    'duy trì các mối quan hệ tích cực',
                    'và nắm bắt cơ hội phù hợp.',
                ]);

            case 'luc_xung':
                return implode(' ', [
                    'Nên giữ sự ổn định,',
                    'hạn chế quyết định nóng vội',
                    'và chuẩn bị phương án dự phòng.',
                ]);

            case 'luc_hai':
                return implode(' ', [
                    'Nên thận trọng trong giao tiếp,',
                    'công việc và các vấn đề liên quan',
                    'đến quyền lợi cá nhân.',
                ]);

            case 'tuong_pha':
                return implode(' ', [
                    'Nên kiểm tra kỹ kế hoạch,',
                    'hạn chế chủ quan và linh hoạt',
                    'khi xuất hiện thay đổi.',
                ]);

            case 'tuong_hinh':
            case 'tu_hinh':
                return implode(' ', [
                    'Nên giữ bình tĩnh,',
                    'kiểm soát cảm xúc và cân nhắc kỹ',
                    'trước các quyết định quan trọng.',
                ]);

            case 'nam_tuoi':
                return implode(' ', [
                    'Nên ưu tiên sự ổn định,',
                    'chủ động thích nghi với thay đổi',
                    'và tránh tạo áp lực quá lớn.',
                ]);

            case 'neutral':
            default:
                if ($rankCode === 'binh') {
                    return implode(' ', [
                        'Tổng thể ở mức cân bằng.',
                        'Kết quả phụ thuộc nhiều vào',
                        'sự chuẩn bị và hành động thực tế.',
                    ]);
                }

                return implode(' ', [
                    'Nên duy trì sự chủ động,',
                    'lập kế hoạch rõ ràng',
                    'và cân nhắc rủi ro phù hợp.',
                ]);
        }
    }

    /**
     * Tạo cảnh báo.
     */
    private static function buildZodiacWarning(
        string $relationCode
    ): ?string
    {
        $warningCodes = [
            'nam_tuoi',
            'tu_hinh',
            'luc_xung',
            'luc_hai',
            'tuong_pha',
            'tuong_hinh',
        ];

        if (
        !in_array(
            $relationCode,
            $warningCodes,
            true
        )
        ) {
            return null;
        }

        return implode(' ', [
            'Kết quả có yếu tố cần lưu ý.',
            'Nên cân nhắc kỹ trước các quyết định quan trọng',
            'và tránh xem kết quả này là kết luận tuyệt đối.',
        ]);
    }

    /**
     * Kiểm tra có quan hệ cảnh báo không.
     */
    private static function hasWarningRelation(
        array $relations
    ): bool
    {
        foreach ($relations as $relation) {
            if (
            in_array(
                $relation['impact'],
                ['warning', 'negative'],
                true
            )
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Kiểm tra hai con giáp có thuộc một cặp không.
     */
    private static function isZodiacPair(
        string $firstZodiac,
        string $secondZodiac,
        array $pairs
    ): bool
    {
        foreach ($pairs as $pair) {
            $isForward = (
                $pair[0] === $firstZodiac
                && $pair[1] === $secondZodiac
            );

            $isReverse = (
                $pair[0] === $secondZodiac
                && $pair[1] === $firstZodiac
            );

            if ($isForward || $isReverse) {
                return true;
            }
        }

        return false;
    }

    /**
     * Kiểm tra hai con giáp cùng nhóm Tam hợp.
     */
    private static function isZodiacSameGroup(
        string $firstZodiac,
        string $secondZodiac,
        array $groups
    ): bool
    {
        foreach ($groups as $group) {
            $hasFirstZodiac = in_array(
                $firstZodiac,
                $group,
                true
            );

            $hasSecondZodiac = in_array(
                $secondZodiac,
                $group,
                true
            );

            if (
                $hasFirstZodiac
                && $hasSecondZodiac
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Kiểm tra con giáp hợp lệ.
     */
    private static function validateZodiac(
        string $zodiac
    ): void
    {
        if (
        !in_array(
            $zodiac,
            self::ZODIACS,
            true
        )
        ) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Con giáp "%s" không hợp lệ. Giá trị hợp lệ: %s.',
                    $zodiac,
                    implode(', ', self::ZODIACS)
                )
            );
        }
    }

    /**
     * Kiểm tra năm.
     */
    private static function validateZodiacYear(
        int $lunarYear
    ): void
    {
        if ($lunarYear < 1) {
            throw new \InvalidArgumentException(
                'Năm âm lịch phải là số nguyên lớn hơn 0.'
            );
        }
    }

    /**
     * Kiểm tra số lượng năm trả về.
     */
    private static function validateZodiacYearLimit(
        int $yearLimit
    ): void
    {
        if ($yearLimit < 1 || $yearLimit > 20) {
            throw new \InvalidArgumentException(
                'Số năm cần lấy phải nằm trong khoảng từ 1 đến 20.'
            );
        }
    }

    /*
     * Các function khác hiện có của Helpers...
     */
}
