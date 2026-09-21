<?php

if (!class_exists('dataKey')) {
    final class dataKey
    {
        public const SESSION_LOGIN_TOKEN = 'SESSION_LOGIN_TOKEN';
        public const CACHE_CONTENT_TYPE = 'text/html; charset=UTF-8';
        public const CACHE = 'private, no-store';

        public const COPE = 'COPE';
        public const COPE_DAY = 'COPE_DAY';
        public const COPE_WEEK = 'COPE_WEEK';
        public const COPE_MONTH = 'COPE_MONTH';
        public const COPE_YEAR = 'COPE_YEAR';
        public const COPE_LUNAR = 'COPE_LUNAR';
        public const CALENDARGOOD = 'CALENDARGOOD_';

        public const HISTORIES_IMPORTANT = [
            1 => [
                '02/01/1963', // Chiến thắng Ấp Bắc
                '06/01/1946', // Tổng tuyển cử đầu tiên
                '07/01/1979', // Giải phóng Phnom Penh khỏi chế độ Khmer Đỏ
                '19/01/1974', // Hải chiến Hoàng Sa
                '27/01/1973', // Ký Hiệp định Paris
                '30/01/1968', // Bắt đầu Tổng tiến công Tết Mậu Thân
            ],

            2 => [
                '01/02/1961', // Thành lập Thông tấn xã Giải phóng
                '01/02/1962', // Đài Phát thanh Giải phóng bắt đầu hoạt động
                '03/02/1930', // Thành lập Đảng Cộng sản Việt Nam
                '15/02/1961', // Thống nhất các lực lượng thành Quân Giải phóng miền Nam
                '17/02/1979', // Chiến tranh bảo vệ biên giới phía Bắc
                '26/02/1973', // Hội nghị quốc tế về Việt Nam tại Paris
            ],

            3 => [
                '04/03/1975', // Mở màn Chiến dịch Tây Nguyên
                '11/03/1975', // Giải phóng Buôn Ma Thuột
                '13/03/1954', // Mở màn Chiến dịch Điện Biên Phủ
                '14/03/1988', // Sự kiện Gạc Ma
                '18/03/1979', // Kết thúc chiến tranh biên giới Việt–Trung năm 1979
                '26/03/1931', // Thành lập Đoàn Thanh niên Cộng sản
                '26/03/1975', // Giải phóng Huế
            ],

            4 => [
                '09/04/1975', // Mở màn trận Xuân Lộc
                '14/04/1975', // Chiến dịch giải phóng các đảo Trường Sa
                '15/04/1992', // Quốc hội thông qua Hiến pháp năm 1992
                '25/04/1976', // Tổng tuyển cử bầu Quốc hội chung của cả nước
                '28/04/1956', // Quân Pháp rút khỏi miền Nam Việt Nam
                '30/04/1975', // Giải phóng miền Nam, thống nhất đất nước
            ],

            5 => [
                '07/05/1954', // Chiến thắng Điện Biên Phủ
                '08/05/1954', // Khai mạc Hội nghị Genève về Đông Dương
                '10/05/1941', // Hội nghị Trung ương 8 tại Pác Bó
                '15/05/1945', // Thành lập Việt Nam Giải phóng quân
                '15/05/1975', // Mít tinh mừng đất nước thống nhất
                '19/05/1890', // Ngày sinh Chủ tịch Hồ Chí Minh
                '19/05/1941', // Thành lập Mặt trận Việt Minh
            ],

            6 => [
                '04/06/1945', // Thành lập Khu giải phóng Việt Bắc
                '05/06/1911', // Nguyễn Tất Thành ra đi tìm đường cứu nước
                '11/06/1948', // Chủ tịch Hồ Chí Minh phát động thi đua ái quốc
                '11/06/1963', // Hòa thượng Thích Quảng Đức tự thiêu
                '12/06/1969', // Thành lập Chính phủ Cách mạng lâm thời miền Nam
                '16/06/1919', // Nguyễn Ái Quốc gửi Bản yêu sách tại Versailles
                '21/06/1925', // Báo Thanh Niên ra số đầu tiên
            ],

            7 => [
                '03/07/1980', // Ký hiệp định hợp tác khai thác dầu khí Việt–Xô
                '20/07/1954', // Ký Hiệp định Genève về Đông Dương
                '21/07/1786', // Nguyễn Huệ tiến ra Bắc Hà
                '22/07/1954', // Lệnh ngừng bắn trên toàn chiến trường Việt Nam
                '27/07/1947', // Ngày Thương binh – Liệt sĩ đầu tiên
                '28/07/1929', // Thành lập Công đoàn Việt Nam
                '28/07/1995', // Việt Nam gia nhập ASEAN
            ],

            8 => [
                '01/08/1941', // Báo Việt Nam Độc lập ra số đầu tiên
                '04/08/1925', // Bắt đầu cuộc bãi công Ba Son
                '13/08/1945', // Ban bố Quân lệnh số 1, phát động Tổng khởi nghĩa
                '19/08/1945', // Cách mạng Tháng Tám thành công tại Hà Nội
                '28/08/1941', // Nguyễn Hữu Tiến, người vẽ cờ đỏ sao vàng, bị xử bắn
                '29/08/1975', // Khánh thành Lăng Chủ tịch Hồ Chí Minh
            ],

            9 => [
                '02/09/1945', // Chủ tịch Hồ Chí Minh đọc Tuyên ngôn Độc lập
                '02/09/1969', // Chủ tịch Hồ Chí Minh qua đời
                '13/09/1913', // Ngày sinh Giáo sư, Viện sĩ Trần Đại Nghĩa
                '15/09/1973', // Fidel Castro thăm vùng giải phóng miền Nam
                '23/09/1945', // Nam Bộ kháng chiến
                '27/09/1940', // Khởi nghĩa Bắc Sơn
            ],

            10 => [
                '04/10/2013', // Đại tướng Võ Nguyên Giáp từ trần
                '09/10/1921', // Thành lập Hội Liên hiệp Thuộc địa
                '10/10/1954', // Giải phóng Thủ đô Hà Nội
                '10/10/2010', // Đại lễ 1.000 năm Thăng Long – Hà Nội
                '14/10/1930', // Cuộc đấu tranh của nhân dân Tiền Hải
                '23/10/1896', // Thành lập Trường Quốc học Huế
                '23/10/1961', // Thành lập Lữ đoàn Hải quân 125
            ],

            11 => [
                '01/11/1968', // Mỹ chấm dứt ném bom miền Bắc lần thứ nhất
                '04/11/1981', // Hội nghị thống nhất Phật giáo Việt Nam
                '15/11/1975', // Hội nghị Hiệp thương chính trị thống nhất đất nước
                '18/11/1930', // Thành lập Mặt trận Dân tộc thống nhất Việt Nam
                '20/11/1982', // Ngày Nhà giáo Việt Nam đầu tiên
                '23/11/1940', // Khởi nghĩa Nam Kỳ
                '29/11/1969', // Quyết định giữ gìn thi hài và xây Lăng Chủ tịch Hồ Chí Minh
            ],

            12 => [
                '04/12/1976', // Khai thông tuyến đường sắt Thống Nhất
                '13/12/1974', // Mở màn Chiến dịch Đường 14 – Phước Long
                '15/12/1986', // Khai mạc Đại hội VI, mở đầu công cuộc Đổi mới
                '18/12/1972', // Bắt đầu chiến dịch Điện Biên Phủ trên không
                '19/12/1946', // Toàn quốc kháng chiến
                '20/12/1960', // Thành lập Mặt trận Dân tộc Giải phóng miền Nam
                '22/12/1944', // Thành lập Đội Việt Nam Tuyên truyền Giải phóng quân
                '29/12/1972', // Kết thúc đợt tập kích chiến lược B-52
            ],
        ];

        public const CUNG_HOANG_DAO = [
            'bach-duong' => [
                'name' => 'Bạch Dương',
                'start' => '21/03',
                'end' => '19/04',
                'icon' => '♈',
            ],

            'kim-nguu' => [
                'name' => 'Kim Ngưu',
                'start' => '20/04',
                'end' => '20/05',
                'icon' => '♉',
            ],

            'song-tu' => [
                'name' => 'Song Tử',
                'start' => '21/05',
                'end' => '20/06',
                'icon' => '♊',
            ],

            'cu-giai' => [
                'name' => 'Cự Giải',
                'start' => '21/06',
                'end' => '22/07',
                'icon' => '♋',
            ],

            'su-tu' => [
                'name' => 'Sư Tử',
                'start' => '23/07',
                'end' => '22/08',
                'icon' => '♌',
            ],

            'xu-nu' => [
                'name' => 'Xử Nữ',
                'start' => '23/08',
                'end' => '22/09',
                'icon' => '♍',
            ],

            'thien-binh' => [
                'name' => 'Thiên Bình',
                'start' => '23/09',
                'end' => '22/10',
                'icon' => '♎',
            ],

            'bo-cap' => [
                'name' => 'Bọ Cạp',
                'start' => '23/10',
                'end' => '21/11',
                'icon' => '♏',
            ],

            'nhan-ma' => [
                'name' => 'Nhân Mã',
                'start' => '22/11',
                'end' => '21/12',
                'icon' => '♐',
            ],

            'ma-ket' => [
                'name' => 'Ma Kết',
                'start' => '22/12',
                'end' => '19/01',
                'icon' => '♑',
            ],

            'bao-binh' => [
                'name' => 'Bảo Bình',
                'start' => '20/01',
                'end' => '18/02',
                'icon' => '♒',
            ],

            'song-ngu' => [
                'name' => 'Song Ngư',
                'start' => '19/02',
                'end' => '20/03',
                'icon' => '♓',
            ],
        ];

        public const ICON_12_CUNG = [
            "ty" => "🐭",
            "suu" => "🐃",
            "dan" => "🐯",
            "mao" => "🐱",
            "thin" => "🐲",
            "ti" => "🐍",
            "ngo" => "🐴",
            "mui" => "🐐",
            "than" => "🐵",
            "dau" => "🐔",
            "tuat" => "🐶",
            "hoi" => "🐷"
        ];

        public const ICON_12_CUNG_TUOI = [
            "tuoi-ty" => "🐭",
            "tuoi-suu" => "🐃",
            "tuoi-dan" => "🐯",
            "tuoi-mao" => "🐱",
            "tuoi-thin" => "🐲",
            "tuoi-ti" => "🐍",
            "tuoi-ngo" => "🐴",
            "tuoi-mui" => "🐐",
            "tuoi-than" => "🐵",
            "tuoi-dau" => "🐔",
            "tuoi-tuat" => "🐶",
            "tuoi-hoi" => "🐷"
        ];

        public const COPE_TEXT_KEYS = [
            'COPE_DAY' => 'Lịch ngày',
            'COPE_WEEK' => 'Lịch tuần',
            'COPE_MONTH' => 'Lịch tháng',
            'COPE_YEAR' => 'Lịch năm',
            'COPE_LUNAR' => 'Lịch âm',
        ];

        public const COPE_YEAR_TEXT_KEYS = [
            'YEAR_NAME' => 'Tên gọi',
            'YEAR_TIME' => 'Thời gian',
            'YEAR_DISTINY_ELEMENT' => 'Ngũ hành nạp âm (Mạng)',
            'YEAR_IN_COMPATIBLE' => 'Khắc',
            'YEAR_MALE_DESTINY' => 'Nam mệnh',
            'YEAR_FEMALE_DESTINY' => 'Nữ mệnh',
            'YEAR_FAMILY_LINEAGE' => 'Con nhà',
            'YEAR_BONE_WEIGHT' => 'Xương',
            'YEAR_GUARDIAN_STAR' => 'Tướng tinh',
            'YEAR_PATRON_BUDDHA' => 'Phật bản mệnh'
        ];

        public const COPE_KEYS = [
            'GREGORIAN_CALENDAR' => 'Dương lịch',
            'LUNAR_CALENDAR' => 'Âm lịch',
            'SOLAR_SEASONS' => 'Tiết khí',
        ];

        public const GIO_SINH = [
            "ty" => "Tý (23h-00h59)",
            "suu" => "Sửu (01h-02h59)",
            "dan" => "Dần (03h-04h59)",
            "mao" => "Mão (05h-06h59)",
            "thin" => "Thìn (07h-08h59)",
            "ti" => "Tị (09h-10h59)",
            "ngo" => "Ngọ (11h-12h59)",
            "mui" => "Mùi (13h-14h59)",
            "than" => "Thân (15h-16h59)",
            "dau" => "Dậu (17h-18h59)",
            "tuat" => "Tuất (19h-20h59)",
            "hoi" => "Hợi (21h-22h59)",
        ];

    }
}

if (!class_exists('dataMenu')) {
    final class dataMenu
    {
        public static function year()
        {
            return (int)date('Y');
        }

        public static function menus(): array
        {
            return [
                "COPE" => [
                    'title' => 'Xem ngày',
                    'name' => 'Xem ngày tốt xấu',
                    'slug' => 'xem-ngay',
                    'type' => 'COPE',
                    'position' => 1,
                    'level' => 1,
                    'h1' => 'Xem ngày tốt xấu, lịch âm dương và lịch vạn niên',
                    'description' => 'Tra cứu ngày tốt xấu và lịch âm dương theo ngày, tuần, tháng hoặc năm. Nội dung cung cấp ngày âm lịch, can chi, tiết khí, giờ hoàng đạo, tuổi xung khắc, hướng xuất hành và các gợi ý chọn ngày phù hợp cho từng công việc.',
                    'title_seo' => 'Xem ngày tốt xấu, lịch âm dương và lịch vạn niên',
                    'meta_des' => 'Xem ngày tốt xấu, lịch âm dương, giờ hoàng đạo, ngày hoàng đạo, tuổi xung khắc và các thông tin lịch vạn niên theo ngày, tuần, tháng, năm.',
                    'meta_key' => 'xem ngày, xem ngày tốt xấu, lịch âm dương, lịch vạn niên, ngày hoàng đạo, giờ hoàng đạo',
                ],

                "COPE_DAY" => [
                    'title' => 'Xem ngày tốt xấu',
                    'name' => 'Xem ngày tốt xấu hôm nay',
                    'slug' => 'xem-ngay-tot-xau',
                    'type' => 'COPE_DAY',
                    'parent' => 'COPE',
                    'level' => 2,
                    'h1' => 'Xem ngày tốt xấu hôm nay theo lịch âm dương',
                    'description' => 'Xem đầy đủ thông tin tốt xấu của từng ngày theo lịch âm dương, gồm can chi, trực ngày, sao tốt, sao xấu, giờ hoàng đạo, tuổi xung khắc và hướng xuất hành. Kết quả giúp bạn tham khảo trước khi sắp xếp công việc quan trọng.',
                    'title_seo' => 'Xem ngày tốt xấu hôm nay, giờ hoàng đạo và lịch âm',
                    'meta_des' => 'Xem ngày tốt xấu hôm nay, ngày âm lịch, can chi, giờ hoàng đạo, tuổi xung khắc, hướng xuất hành, sao tốt xấu và việc nên làm trong ngày.',
                    'meta_key' => 'xem ngày tốt xấu, ngày tốt hôm nay, ngày hoàng đạo, giờ hoàng đạo, lịch âm hôm nay, việc nên làm hôm nay',
                ],

                "COPE_WEEK" => [
                    'title' => 'Xem ngày tốt tuần',
                    'name' => 'Xem ngày tốt trong tuần',
                    'slug' => 'xem-ngay-tot-trong-tuan',
                    'type' => 'COPE_WEEK',
                    'parent' => 'COPE',
                    'level' => 2,
                    'h1' => 'Xem ngày tốt trong tuần theo lịch âm dương',
                    'description' => 'Theo dõi lịch tốt xấu của các ngày trong tuần trên cùng một trang để dễ so sánh. Bạn có thể xem ngày hoàng đạo, giờ đẹp, tuổi xung khắc và công việc phù hợp, từ đó lựa chọn thời điểm thuận tiện cho kế hoạch cá nhân.',
                    'title_seo' => 'Xem ngày tốt trong tuần, chọn ngày hoàng đạo phù hợp',
                    'meta_des' => 'Xem ngày tốt trong tuần, so sánh ngày hoàng đạo, giờ đẹp, tuổi xung khắc và các công việc phù hợp để lựa chọn ngày thuận tiện theo lịch âm dương.',
                    'meta_key' => 'xem ngày tốt trong tuần, ngày đẹp tuần này, ngày hoàng đạo trong tuần, chọn ngày tốt, lịch tuần',
                ],

                "COPE_MONTH" => [
                    'title' => 'Xem ngày tốt tháng',
                    'name' => 'Xem ngày tốt trong tháng',
                    'slug' => 'xem-ngay-tot-trong-thang',
                    'type' => 'COPE_MONTH',
                    'parent' => 'COPE',
                    'level' => 2,
                    'h1' => 'Xem ngày tốt trong tháng theo lịch âm dương',
                    'description' => 'Tổng hợp lịch âm dương và ngày tốt xấu trong tháng, giúp bạn nhanh chóng so sánh các ngày hoàng đạo, ngày hắc đạo, giờ đẹp và tuổi xung khắc. Phù hợp để tham khảo khi lên lịch cưới hỏi, khai trương, động thổ, xuất hành hoặc công việc quan trọng.',
                    'title_seo' => 'Xem ngày tốt trong tháng, chọn ngày đẹp theo công việc',
                    'meta_des' => 'Xem danh sách ngày tốt trong tháng, ngày hoàng đạo, giờ đẹp, tuổi xung khắc và việc nên làm để lựa chọn ngày phù hợp cho các kế hoạch quan trọng.',
                    'meta_key' => 'xem ngày tốt trong tháng, ngày đẹp trong tháng, ngày hoàng đạo, chọn ngày tốt, lịch âm tháng',
                ],

                "COPE_YEAR" => [
                    'title' => 'Xem ngày tốt năm',
                    'name' => 'Lịch vạn niên theo năm',
                    'slug' => 'lich-van-nien-nam',
                    'type' => 'COPE_YEAR',
                    'parent' => 'COPE',
                    'level' => 2,
                    'h1' => 'Lịch vạn niên theo năm, xem ngày tốt xấu các tháng',
                    'description' => 'Tra cứu lịch vạn niên của 12 tháng trong năm với thông tin ngày âm dương, can chi, tiết khí và ngày tốt xấu. Trang giúp bạn theo dõi lịch theo tháng, tìm ngày phù hợp và chủ động xây dựng kế hoạch dài hạn.',
                    'title_seo' => 'Lịch vạn niên theo năm, xem ngày tốt xấu 12 tháng',
                    'meta_des' => 'Tra cứu lịch vạn niên theo năm, xem lịch âm dương, ngày hoàng đạo, ngày tốt xấu, tiết khí và thông tin các ngày trong 12 tháng.',
                    'meta_key' => 'lịch vạn niên năm, xem ngày tốt năm, lịch âm dương năm, ngày hoàng đạo, lịch 12 tháng',
                ],

                "COPE_LUNAR" => [
                    'title' => 'Lịch âm',
                    'name' => 'Lịch âm dương',
                    'slug' => 'lich-am-duong',
                    'type' => 'COPE_LUNAR',
                    'parent' => 'COPE',
                    'level' => 2,
                    'h1' => 'Lịch âm dương hôm nay và lịch vạn niên',
                    'description' => 'Xem lịch âm dương hôm nay, ngày can chi, tiết khí, giờ hoàng đạo và các thông tin lịch vạn niên cần thiết. Công cụ hỗ trợ chuyển đổi ngày dương sang ngày âm hoặc ngày âm sang ngày dương nhanh chóng, thuận tiện.',
                    'title_seo' => 'Lịch âm dương hôm nay, đổi ngày âm sang dương',
                    'meta_des' => 'Xem lịch âm dương hôm nay, ngày can chi, tiết khí, giờ hoàng đạo và chuyển đổi ngày âm sang ngày dương hoặc ngày dương sang ngày âm.',
                    'meta_key' => 'lịch âm, lịch âm dương, lịch âm hôm nay, đổi ngày âm dương, lịch vạn niên, ngày can chi',
                ],

                // ---------------------------
                "HOROSCOPE" => [
                    'title' => 'Tử vi',
                    'slug' => 'xem-tu-vi',
                    'type' => 'HOROSCOPE',
                    'position' => 1,
                    'level' => 1,
                    'h1' => 'Tử vi: Xem lá số, 12 con giáp và vận hạn',
                    'description' => 'Khám phá hệ thống tử vi với các công cụ lập lá số, luận giải 12 cung, xem tử vi 12 con giáp và vận hạn theo ngày giờ sinh. Nội dung được trình bày theo từng chủ đề để bạn dễ tra cứu công danh, tài lộc, tình duyên, sức khỏe và các giai đoạn vận trình.',
                    'title_seo' => 'Tử vi: Xem lá số, 12 con giáp và luận giải vận hạn',
                    'meta_des' => 'Xem tử vi, lập lá số và luận giải 12 cung, 12 con giáp, công danh, tài lộc, tình duyên, sức khỏe, đại vận và vận hạn theo ngày giờ sinh.',
                    'meta_key' => 'tử vi, xem tử vi, lá số tử vi, lập lá số tử vi, tử vi 12 con giáp, luận giải tử vi, xem vận hạn',
                ],

                "HOROSCOPE_TV_12_CON_GIAP" => [
                    'title' => 'Tử vi hàng ngày 12 con giáp',
                    'slug' => 'tu-vi-hang-ngay-12-con-giap',
                    'type' => 'HOROSCOPE_TV_12_CON_GIAP',
                    'h1' => 'Tử vi hàng ngày 12 con giáp: Xem vận trình hôm nay, tuần, tháng và năm',
                    'description' => 'Cập nhật tử vi 12 con giáp theo ngày, tuần, tháng và năm, với các nội dung về công việc, tài lộc, tình duyên, sức khỏe và những điều cần lưu ý. Chọn con giáp của bạn để theo dõi vận trình và tham khảo lời khuyên phù hợp từng thời điểm.',
                    'parent' => 'HOROSCOPE',
                    'level' => 2,
                    'title_seo' => 'Tử vi hàng ngày 12 con giáp hôm nay, tuần, tháng và năm',
                    'meta_des' => 'Xem tử vi 12 con giáp hôm nay, ngày mai, theo tuần, tháng và năm. Luận giải vận trình công việc, tài lộc, tình duyên, sức khỏe của từng tuổi.',
                    'meta_key' => 'tử vi 12 con giáp, tử vi hôm nay, tử vi hàng ngày, tử vi tuần, tử vi tháng, tử vi năm',
                ],

                "HOROSCOPE_TV_NAM" => [
                    'title' => 'Tử vi năm ' . date('Y'),
                    'slug' => 'tu-vi-nam-' . date('Y') . '-12-con-giap',
                    'type' => 'HOROSCOPE_TV_NAM',
                    'isPost' => 'news',
                    'h1' => 'Tử vi 12 con giáp năm ' . date('Y') . ': Công danh, tài lộc, tình duyên và sức khỏe',
                    'description' => 'Xem tổng quan tử vi 12 con giáp năm ' . date('Y') . ', bao gồm công danh, sự nghiệp, tài lộc, tình duyên, sức khỏe và các giai đoạn đáng chú ý. Mỗi tuổi có phần luận giải riêng, giúp bạn tham khảo cơ hội, thử thách và định hướng trong năm.',
                    'parent' => 'HOROSCOPE',
                    'level' => 2,
                    'title_seo' => 'Tử vi 12 con giáp năm ' . date('Y') . ': Vận mệnh từng tuổi',
                    'meta_des' => 'Xem tử vi 12 con giáp năm ' . date('Y') . ' chi tiết từng tuổi. Luận giải vận mệnh, công danh, sự nghiệp, tài lộc, tình duyên, sức khỏe và vận hạn trong năm.',
                    'meta_key' => 'tử vi 12 con giáp năm ' . date('Y') . ', tử vi năm ' . date('Y') . ', tử vi ' . date('Y') . ', vận mệnh 12 con giáp, tử vi từng tuổi',
                ],

                "HOROSCOPE_LAS_SO" => [
                    'title' => 'Lập Lá số tử vi',
                    'slug' => 'lap-la-so-tu-vi',
                    'type' => 'HOROSCOPE_LAS_SO',
                    'parent' => 'HOROSCOPE',
                    'level' => 2,
                    'description' => 'Nhập họ tên, ngày tháng năm sinh, giờ sinh và giới tính để lập lá số tử vi online. Hệ thống hiển thị bố cục 12 cung, các sao và phần luận giải về cung mệnh, công danh, tài lộc, tình duyên, sức khỏe cùng vận hạn theo từng giai đoạn.',
                    'title_seo' => 'Lập lá số tử vi online miễn phí, luận giải chi tiết',
                    'meta_des' => 'Lập lá số tử vi online miễn phí theo ngày giờ sinh. Xem lá số và luận giải chi tiết cung mệnh, công danh, tài lộc, tình duyên, sức khỏe và vận hạn.',
                    'meta_key' => 'lập lá số tử vi, lá số tử vi online, lập lá số tử vi miễn phí, xem lá số tử vi, luận giải lá số tử vi',
                ],

                "HOROSCOPE_TV_CAN_BIET" => [
                    'title' => 'Kiến thức tử vi',
                    'slug' => 'kien-thuc-tu-vi',
                    'type' => 'HOROSCOPE_TV_CAN_BIET',
                    'isPost' => 'news',
                    'h1' => 'Kiến thức tử vi cơ bản và chuyên sâu dễ hiểu',
                    'description' => 'Tổng hợp kiến thức tử vi từ cơ bản đến chuyên sâu dành cho người mới tìm hiểu và người muốn nghiên cứu sâu hơn. Nội dung giải thích cách đọc lá số, ý nghĩa 12 cung, hệ thống sao, cung mệnh, đại vận, tiểu vận và các thuật ngữ thường gặp.',
                    'parent' => 'HOROSCOPE',
                    'level' => 2,
                    'title_seo' => 'Kiến thức tử vi cơ bản, dễ hiểu cho người mới',
                    'meta_des' => 'Tổng hợp kiến thức tử vi từ cơ bản đến chuyên sâu: cách đọc lá số, ý nghĩa 12 cung, các sao, cung mệnh, đại vận, tiểu vận và thuật ngữ tử vi.',
                    'meta_key' => 'kiến thức tử vi, tử vi cơ bản, học tử vi, cách đọc lá số tử vi, ý nghĩa các sao tử vi, 12 cung tử vi',
                ],

                // ---------------------------
                "FORTUNE" => [
                    'title' => 'Bói vui',
                    'slug' => 'boi-vui',
                    'type' => 'FORTUNE',
                    'position' => 1,
                    'level' => 1,
                    'h1' => 'Xem bói online: Tình yêu, ngày sinh và vận mệnh',
                    'description' => 'Khám phá các công cụ xem bói online về tình yêu, ngày giờ sinh, hôn nhân, sinh con, xây nhà và vận mệnh. Mỗi kết quả được trình bày dễ hiểu, mang tính tham khảo và giúp bạn tiếp cận các phương pháp dự đoán dân gian theo từng nhu cầu.',
                    'title_seo' => 'Xem bói online: Tình yêu, ngày sinh và vận mệnh',
                    'meta_des' => 'Tổng hợp công cụ xem bói tình yêu, ngày sinh, giờ sinh, năm kết hôn, sinh con, xây nhà, cân xương tính số và các nội dung dự đoán mang tính tham khảo.',
                    'meta_key' => 'xem bói, xem bói online, bói tình yêu, bói ngày sinh, xem vận mệnh, cân xương tính số',
                ],

                "FORTUNE_LOVE" => [
                    'title' => 'Bói tình yêu',
                    'slug' => 'xem-tinh-duyen',
                    'type' => 'FORTUNE_LOVE',
                    'parent' => 'FORTUNE',
                    'level' => 2,
                    'h1' => 'Bói tình yêu: Xem mức độ hòa hợp của hai người',
                    'description' => 'Nhập thông tin của hai người để tham khảo mức độ hòa hợp trong tình yêu, tính cách và cách xây dựng mối quan hệ. Phần luận giải giúp nhận diện điểm tương đồng, khác biệt và những điều hai người nên lưu ý khi đồng hành cùng nhau.',
                    'title_seo' => 'Bói tình yêu, xem mức độ hòa hợp của hai người',
                    'meta_des' => 'Bói tình yêu theo thông tin ngày sinh của hai người, xem mức độ hòa hợp trong tính cách, tình cảm và mối quan hệ với kết quả luận giải dễ hiểu.',
                    'meta_key' => 'bói tình yêu, xem tình duyên, bói tình duyên, xem tình yêu hai người, tình yêu hợp nhau',
                ],

                "FORTUNE_MARRIAGE" => [
                    'title' => 'Xem năm lấy chồng',
                    'slug' => 'xem-nam-lay-chong',
                    'type' => 'FORTUNE_MARRIAGE',
                    'parent' => 'FORTUNE',
                    'level' => 2,
                    'h1' => 'Xem năm lấy chồng và tuổi kết hôn phù hợp',
                    'description' => 'Tra cứu thời điểm lấy chồng dựa trên ngày sinh, tuổi âm lịch và các yếu tố cưới hỏi theo quan niệm dân gian. Kết quả giúp bạn tham khảo độ tuổi, năm kết hôn phù hợp và những lưu ý thường được xem trước khi tổ chức hôn lễ.',
                    'title_seo' => 'Xem năm lấy chồng, chọn tuổi kết hôn phù hợp',
                    'meta_des' => 'Xem năm lấy chồng theo ngày sinh, tham khảo độ tuổi và năm kết hôn phù hợp dựa trên tuổi âm lịch, Kim Lâu cùng các yếu tố cưới hỏi dân gian.',
                    'meta_key' => 'xem năm lấy chồng, bao giờ lấy chồng, tuổi kết hôn, xem tuổi cưới, năm kết hôn phù hợp',
                ],

                "FORTUNE_WIFE" => [
                    'title' => 'Xem năm lấy vợ',
                    'slug' => 'xem-nam-lay-vo',
                    'type' => 'FORTUNE_WIFE',
                    'parent' => 'FORTUNE',
                    'level' => 2,
                    'h1' => 'Xem năm lấy vợ và tuổi kết hôn phù hợp',
                    'description' => 'Tra cứu thời điểm lấy vợ dựa trên ngày sinh, can chi, ngũ hành và các yếu tố cưới hỏi dân gian. Công cụ cung cấp gợi ý về năm kết hôn phù hợp để bạn tham khảo khi lên kế hoạch cho chuyện hôn nhân.',
                    'title_seo' => 'Xem năm lấy vợ, chọn năm kết hôn phù hợp',
                    'meta_des' => 'Xem năm lấy vợ theo ngày sinh, tham khảo thời điểm kết hôn phù hợp dựa trên tuổi, can chi, ngũ hành và các yếu tố cưới hỏi theo quan niệm dân gian.',
                    'meta_key' => 'xem năm lấy vợ, bao giờ lấy vợ, tuổi kết hôn nam, xem tuổi cưới, năm lấy vợ phù hợp',
                ],

                "FORTUNE_CHILD" => [
                    'title' => 'Sinh con hợp tuổi',
                    'slug' => 'sinh-con-hop-tuoi',
                    'type' => 'FORTUNE_CHILD',
                    'parent' => 'FORTUNE',
                    'level' => 2,
                    'h1' => 'Xem năm sinh con hợp tuổi bố mẹ',
                    'description' => 'Tra cứu năm sinh con hợp tuổi bố mẹ dựa trên can chi, ngũ hành và mức độ tương hợp giữa các thành viên. Kết quả giúp gia đình tham khảo khi lập kế hoạch sinh con, không thay thế tư vấn y tế hoặc quyết định cá nhân.',
                    'title_seo' => 'Xem năm sinh con hợp tuổi bố mẹ theo năm sinh',
                    'meta_des' => 'Xem năm sinh con hợp tuổi bố mẹ, phân tích can chi, ngũ hành và mức độ tương hợp giữa tuổi con dự kiến với tuổi cha mẹ để tham khảo.',
                    'meta_key' => 'sinh con hợp tuổi, xem năm sinh con, sinh con hợp tuổi bố mẹ, chọn năm sinh con, tuổi con hợp bố mẹ',
                ],

                "FORTUNE_CHILD_CHOICE" => [
                    'title' => 'Sinh con theo ý muốn',
                    'slug' => 'sinh-con-theo-y-muon',
                    'type' => 'FORTUNE_CHILD_CHOICE',
                    'parent' => 'FORTUNE',
                    'level' => 2,
                    'h1' => 'Sinh con theo ý muốn: Tra cứu thời điểm sinh con tham khảo',
                    'description' => 'Tham khảo thời điểm sinh con theo kế hoạch dựa trên thông tin của bố mẹ và lịch dự kiến. Nội dung chỉ mang tính tham khảo dân gian, không phải phương pháp y khoa, không bảo đảm giới tính và không nên dùng thay cho tư vấn của bác sĩ.',
                    'title_seo' => 'Sinh con theo ý muốn, tra cứu thời điểm sinh con',
                    'meta_des' => 'Tra cứu thời điểm sinh con theo kế hoạch dựa trên thông tin bố mẹ và lịch sinh tham khảo. Kết quả không phải phương pháp y khoa và không bảo đảm giới tính em bé.',
                    'meta_key' => 'sinh con theo ý muốn, tính thời điểm sinh con, kế hoạch sinh con, lịch sinh con, dự tính sinh con',
                ],

                "FORTUNE_BUILDING_HOUSE" => [
                    'title' => 'Xem tuổi xây nhà',
                    'slug' => 'xem-tuoi-xay-nha',
                    'type' => 'FORTUNE_BUILDING_HOUSE',
                    'parent' => 'FORTUNE',
                    'level' => 2,
                    'h1' => 'Xem năm xây nhà ' . date('Y') . ' hợp tuổi gia chủ',
                    'description' => 'Xem năm xây nhà ' . date('Y') . ' theo tuổi gia chủ và kiểm tra các yếu tố Kim Lâu, Hoang Ốc, Tam Tai. Kết quả hỗ trợ tham khảo thời điểm xây dựng, sửa chữa hoặc động thổ và gợi ý cách xử lý khi tuổi chưa phù hợp.',
                    'title_seo' => 'Xem năm xây nhà ' . date('Y') . ': Kim Lâu, Hoang Ốc, Tam Tai',
                    'meta_des' => 'Xem năm xây nhà ' . date('Y') . ' theo tuổi gia chủ, kiểm tra Kim Lâu, Hoang Ốc và Tam Tai để tham khảo khi xây dựng, sửa nhà hoặc động thổ.',
                    'meta_key' => 'xem năm xây nhà ' . date('Y') . ', xem tuổi xây nhà, tuổi làm nhà ' . date('Y') . ', Kim Lâu, Hoang Ốc, Tam Tai',
                ],

                "FORTUNE_WEIGHT_BONES" => [
                    'title' => 'Cân xương tính số',
                    'slug' => 'can-xuong-tinh-so',
                    'type' => 'FORTUNE_WEIGHT_BONES',
                    'parent' => 'FORTUNE',
                    'level' => 2,
                    'h1' => 'Cân xương tính số theo ngày, tháng, năm và giờ sinh',
                    'description' => 'Nhập ngày, tháng, năm và giờ sinh âm lịch để tính số cân lượng theo phương pháp cân xương tính số. Kết quả cung cấp phần luận giải tổng quan về vận mệnh, công danh, tài lộc và cuộc sống theo quan niệm dân gian.',
                    'title_seo' => 'Cân xương tính số, xem số cân lượng theo giờ sinh',
                    'meta_des' => 'Cân xương tính số theo ngày, tháng, năm và giờ sinh âm lịch, tra cứu số cân lượng và phần luận giải vận mệnh theo phương pháp dân gian.',
                    'meta_key' => 'cân xương tính số, cân xương đoán số, số cân lượng, xem cân lượng, cân xương theo giờ sinh',
                ],

                "FORTUNE_DOUBLE_FUNERAL" => [
                    'title' => 'Tính trùng tang',
                    'slug' => 'cach-tinh-trung-tang',
                    'type' => 'FORTUNE_DOUBLE_FUNERAL',
                    'parent' => 'FORTUNE',
                    'level' => 2,
                    'h1' => 'Tính trùng tang theo ngày giờ mất và tuổi người mất',
                    'description' => 'Nhập tuổi cùng ngày giờ mất để tham khảo cách tính trùng tang, nhập mộ và thiên di theo phương pháp dân gian. Kết quả chỉ có giá trị tham khảo văn hóa, gia đình nên cân nhắc phong tục địa phương và hướng dẫn của người có chuyên môn nghi lễ.',
                    'title_seo' => 'Tính trùng tang, nhập mộ và thiên di theo ngày giờ mất',
                    'meta_des' => 'Công cụ tính trùng tang theo tuổi và ngày giờ mất, phân loại nhập mộ, thiên di hoặc trùng tang theo phương pháp dân gian để gia đình tham khảo.',
                    'meta_key' => 'tính trùng tang, cách tính trùng tang, nhập mộ, thiên di, trùng tang theo ngày giờ mất',
                ],

                "FORTUNE_BIRTHDAY" => [
                    'title' => 'Xem bói ngày sinh, giờ sinh',
                    'slug' => 'xem-boi-ngay-sinh-gio-sinh',
                    'type' => 'FORTUNE_BIRTHDAY',
                    'parent' => 'FORTUNE',
                    'level' => 2,
                    'h1' => 'Bói ngày sinh, giờ sinh và luận giải vận mệnh',
                    'description' => 'Tra cứu đặc điểm tính cách và vận trình dựa trên ngày sinh, giờ sinh. Phần luận giải đề cập đến công việc, tình duyên, tài lộc, sức khỏe và những xu hướng nổi bật trong cuộc sống theo quan niệm dự đoán dân gian.',
                    'title_seo' => 'Bói ngày sinh, giờ sinh và xem vận mệnh cuộc đời',
                    'meta_des' => 'Bói ngày sinh và giờ sinh, luận giải tính cách, công việc, tình duyên, tài lộc và những đặc điểm vận mệnh theo thông tin ngày giờ sinh.',
                    'meta_key' => 'bói ngày sinh, bói giờ sinh, xem bói ngày sinh giờ sinh, xem vận mệnh, tính cách theo giờ sinh',
                ],

                // ---------------------------
                "FENG_SHUI" => [
                    'title' => 'Phong thủy',
                    'slug' => 'phong-thuy',
                    'type' => 'FENG_SHUI',
                    'position' => 1,
                    'level' => 1,
                    'h1' => 'Phong thủy nhà ở, công việc và đời sống',
                    'description' => 'Tổng hợp kiến thức phong thủy ứng dụng cho nhà ở, nơi làm việc, kinh doanh, tình duyên và đời sống. Bạn có thể tìm hiểu hướng, màu sắc, tuổi mệnh, vật phẩm, cách bố trí không gian và các nguyên tắc giúp môi trường sống cân đối, thuận tiện hơn.',
                    'title_seo' => 'Phong thủy nhà ở, tuổi mệnh, công việc và đời sống',
                    'meta_des' => 'Tổng hợp kiến thức phong thủy nhà ở, tuổi mệnh, hướng nhà, màu sắc, vật phẩm, công việc và đời sống, giúp bạn tham khảo cách bố trí không gian hài hòa.',
                    'meta_key' => 'phong thủy, phong thủy nhà ở, xem phong thủy, phong thủy tuổi mệnh, hướng nhà, màu sắc phong thủy, vật phẩm phong thủy',
                ],
                "FENG_SHUI_LOVE_MARRIAGE" => [
                    'title' => 'Tình duyên & hôn nhân',
                    'slug' => 'phong-thuy-tinh-duyen-hon-nhan',
                    'type' => 'FENG_SHUI_LOVE_MARRIAGE',
                    'isPost' => 'news',
                    'parent' => 'FENG_SHUI',
                    'level' => 2,
                    'h1' => 'Phong thủy tình duyên và hôn nhân',
                    'description' => 'Tìm hiểu các nguyên tắc phong thủy thường được áp dụng cho tình duyên, hôn nhân và không gian gia đình. Nội dung gợi ý cách bố trí phòng ngủ, lựa chọn màu sắc, hướng và vật phẩm để tạo cảm giác hài hòa, ấm cúng và thuận tiện trong sinh hoạt.',
                    'title_seo' => 'Phong thủy tình duyên, hôn nhân và phòng ngủ',
                    'meta_des' => 'Tìm hiểu phong thủy tình duyên và hôn nhân, cách bố trí phòng ngủ, lựa chọn màu sắc, hướng và vật phẩm giúp không gian gia đình hài hòa, ấm cúng.',
                    'meta_key' => 'phong thủy tình duyên, phong thủy hôn nhân, phong thủy phòng ngủ, phong thủy vợ chồng, tình duyên',
                ],
                "FENG_SHUI_ITEMS" => [
                    'title' => 'Vật phẩm phong thủy',
                    'slug' => 'vat-pham-phong-thuy',
                    'type' => 'FENG_SHUI_ITEMS',
                    'isPost' => 'news',
                    'parent' => 'FENG_SHUI',
                    'level' => 2,
                    'h1' => 'Vật phẩm phong thủy: Ý nghĩa, cách chọn và bài trí',
                    'description' => 'Khám phá ý nghĩa, công dụng và cách sử dụng các vật phẩm phong thủy phổ biến. Nội dung hướng dẫn lựa chọn theo tuổi, mệnh, mục đích và vị trí bài trí trong nhà ở, nơi làm việc hoặc không gian kinh doanh để bạn tham khảo phù hợp.',
                    'title_seo' => 'Vật phẩm phong thủy theo tuổi, mệnh và công dụng',
                    'meta_des' => 'Tìm hiểu ý nghĩa, công dụng và cách bài trí vật phẩm phong thủy. Hướng dẫn lựa chọn vật phẩm phù hợp với tuổi, mệnh, nhà ở, công việc và kinh doanh.',
                    'meta_key' => 'vật phẩm phong thủy, vật phẩm phong thủy theo tuổi, vật phẩm theo mệnh, cách đặt vật phẩm phong thủy',
                ],
                "FENG_SHUI_LAND_VIEW_HOUSE" => [
                    'title' => 'Đất đai & nhà cửa',
                    'slug' => 'phong-thuy-dat-dai-nha-cua',
                    'type' => 'FENG_SHUI_LAND_VIEW_HOUSE',
                    'isPost' => 'news',
                    'parent' => 'FENG_SHUI',
                    'level' => 2,
                    'h1' => 'Phong thủy đất đai và nhà cửa',
                    'description' => 'Tìm hiểu phong thủy đất đai và nhà cửa qua thế đất, hướng nhà, vị trí cổng, cửa chính và môi trường xung quanh. Các bài viết giúp bạn tham khảo những nguyên tắc cần lưu ý khi chọn đất, xây nhà hoặc cải tạo không gian sống.',
                    'title_seo' => 'Phong thủy đất đai, nhà cửa, hướng nhà và thế đất',
                    'meta_des' => 'Tổng hợp kiến thức phong thủy đất đai và nhà cửa: xem thế đất, hướng nhà, cổng, cửa chính, vị trí xây nhà và những nguyên tắc cần lưu ý khi chọn đất.',
                    'meta_key' => 'phong thủy đất đai, phong thủy nhà cửa, xem thế đất, xem hướng nhà, phong thủy cổng nhà, chọn đất xây nhà',
                ],
                "FENG_SHUI_BUSINESS_WEALTH" => [
                    'title' => 'Kinh doanh & tài lộc',
                    'slug' => 'phong-thuy-kinh-doanh-tai-loc',
                    'type' => 'FENG_SHUI_BUSINESS_WEALTH',
                    'isPost' => 'news',
                    'parent' => 'FENG_SHUI',
                    'level' => 2,
                    'h1' => 'Phong thủy kinh doanh và tài lộc',
                    'description' => 'Tổng hợp kiến thức phong thủy dành cho cửa hàng, công ty, văn phòng và hoạt động kinh doanh. Bạn có thể tham khảo cách chọn hướng, bố trí bàn làm việc, quầy thu ngân, biển hiệu, lối đi và vật phẩm phù hợp với không gian sử dụng.',
                    'title_seo' => 'Phong thủy kinh doanh, cửa hàng, văn phòng và tài lộc',
                    'meta_des' => 'Kiến thức phong thủy kinh doanh cho cửa hàng, công ty và văn phòng: cách chọn hướng, bố trí quầy thu ngân, bàn làm việc, biển hiệu và không gian kinh doanh.',
                    'meta_key' => 'phong thủy kinh doanh, phong thủy cửa hàng, phong thủy văn phòng, quầy thu ngân, bàn làm việc, tài lộc',
                ],
                "FENG_SHUI_LIVING_WORKING" => [
                    'title' => 'Nhà ở & nơi làm việc',
                    'slug' => 'phong-thuy-nha-o-noi-lam-viec',
                    'type' => 'FENG_SHUI_LIVING_WORKING',
                    'isPost' => 'news',
                    'parent' => 'FENG_SHUI',
                    'level' => 2,
                    'h1' => 'Phong thủy nhà ở và nơi làm việc',
                    'description' => 'Hướng dẫn bố trí nhà ở và nơi làm việc theo các nguyên tắc phong thủy phổ biến. Nội dung bao gồm phòng khách, phòng ngủ, phòng bếp, bàn làm việc, cây xanh, ánh sáng và màu sắc, giúp không gian gọn gàng, cân đối và thuận tiện.',
                    'title_seo' => 'Phong thủy nhà ở, phòng khách, phòng ngủ và nơi làm việc',
                    'meta_des' => 'Hướng dẫn bố trí nhà ở và nơi làm việc theo phong thủy: phòng khách, phòng ngủ, phòng bếp, bàn làm việc, cây xanh, ánh sáng và màu sắc phù hợp.',
                    'meta_key' => 'phong thủy nhà ở, phong thủy nơi làm việc, phong thủy phòng khách, phong thủy phòng ngủ, phong thủy phòng bếp',
                ],
                "FENG_SHUI_RULER_LO_BAN" => [
                    'title' => 'Thước Lỗ Ban',
                    'slug' => 'thuoc-lo-ban',
                    'type' => 'FENG_SHUI_RULER_LO_BAN',
                    'parent' => 'FENG_SHUI',
                    'level' => 2,
                    'h1' => 'Thước Lỗ Ban online: Tra cứu kích thước đẹp',
                    'description' => 'Tra cứu kích thước bằng Thước Lỗ Ban online cho cửa, bàn thờ, nội thất và các hạng mục xây dựng. Công cụ giúp xác định loại thước phù hợp, cung tốt xấu và ý nghĩa của từng khoảng đo để bạn tham khảo khi thiết kế, thi công.',
                    'title_seo' => 'Thước Lỗ Ban online: Tra cứu kích thước cửa, bàn thờ',
                    'meta_des' => 'Tra cứu Thước Lỗ Ban online theo kích thước cửa, bàn thờ, nội thất và xây dựng. Xem loại thước phù hợp, cung tốt xấu và ý nghĩa từng khoảng đo.',
                    'meta_key' => 'thước lỗ ban, thước lỗ ban online, kích thước lỗ ban, kích thước cửa đẹp, kích thước bàn thờ',
                ],
                "FENG_SHUI_EVERY_DAY" => [
                    'title' => 'Mẹo phong thủy',
                    'slug' => 'meo-phong-thuy',
                    'type' => 'FENG_SHUI_EVERY_DAY',
                    'isPost' => 'news',
                    'parent' => 'FENG_SHUI',
                    'level' => 2,
                    'h1' => 'Mẹo phong thủy hữu ích trong đời sống hàng ngày',
                    'description' => 'Tổng hợp những mẹo phong thủy đơn giản, dễ áp dụng trong nhà ở, nơi làm việc và sinh hoạt hàng ngày. Nội dung tập trung vào cách sắp xếp đồ dùng, lựa chọn màu sắc, cây xanh, ánh sáng và vị trí bố trí để không gian hài hòa hơn.',
                    'title_seo' => 'Mẹo phong thủy nhà ở và đời sống dễ áp dụng',
                    'meta_des' => 'Tổng hợp mẹo phong thủy đơn giản về nhà ở, bàn làm việc, màu sắc, cây xanh và cách sắp xếp đồ dùng, giúp không gian sống gọn gàng, cân đối và hài hòa.',
                    'meta_key' => 'mẹo phong thủy, phong thủy hàng ngày, mẹo phong thủy nhà ở, phong thủy đời sống, bố trí nhà cửa',
                ],

                // ---------------------------
                "ZODIAC" => [
                    'title' => 'Cung hoàng đạo',
                    'slug' => 'cung-hoang-dao',
                    'type' => 'ZODIAC',
                    'position' => 1,
                    'level' => 1,
                    'h1' => '12 cung hoàng đạo: Tính cách, tình yêu và tử vi',
                    'description' => 'Khám phá 12 cung hoàng đạo theo ngày sinh với thông tin về tính cách, tình yêu, sự nghiệp và mức độ hòa hợp. Trang cũng tổng hợp tử vi cung hoàng đạo theo ngày, tuần, tháng và năm để bạn dễ dàng theo dõi các xu hướng nổi bật.',
                    'title_seo' => '12 cung hoàng đạo: Tính cách, tình yêu và tử vi',
                    'meta_des' => 'Khám phá 12 cung hoàng đạo, ngày sinh, tính cách, tình yêu, sự nghiệp và mức độ hợp nhau. Xem tử vi cung hoàng đạo hôm nay, tuần, tháng và năm.',
                    'meta_key' => '12 cung hoàng đạo, cung hoàng đạo, tử vi cung hoàng đạo, tính cách 12 cung hoàng đạo, tình yêu 12 cung hoàng đạo',
                ],

                "ZODIAC_12_CUNG_EVERY_DAY" => [
                    'title' => 'Tử vi 12 cung hàng ngày',
                    'slug' => 'tu-vi-12-cung-hoang-dao-hang-ngay-' . date('d-m-Y'),
                    'type' => 'ZODIAC_12_CUNG_EVERY_DAY',
                    'isPost' => 'news',
                    'parent' => 'ZODIAC',
                    'level' => 2,
                    'h1' => 'Tử vi 12 cung hoàng đạo hôm nay',
                    'description' => 'Cập nhật tử vi hôm nay của 12 cung hoàng đạo với dự báo về công việc, tài chính, tình yêu, sức khỏe và tâm trạng. Chọn cung của bạn để xem những điểm đáng chú ý trong ngày và tham khảo lời khuyên phù hợp.',
                    'title_seo' => 'Tử vi 12 cung hoàng đạo hôm nay: Công việc, tình yêu',
                    'meta_des' => 'Xem tử vi 12 cung hoàng đạo hôm nay, dự báo công việc, tài chính, tình yêu, sức khỏe và lời khuyên trong ngày dành cho từng cung hoàng đạo.',
                    'meta_key' => 'tử vi 12 cung hoàng đạo hôm nay, tử vi cung hoàng đạo, tử vi hàng ngày, 12 cung hoàng đạo hôm nay',
                ],

                "ZODIAC_12_CUNG_EVERY_WEEK" => [
                    'title' => 'Tử vi hàng tuần',
                    'slug' => 'tu-vi-12-cung-hoang-dao-hang-tuan',
                    'type' => 'ZODIAC_12_CUNG_EVERY_WEEK',
                    'isPost' => 'news',
                    'parent' => 'ZODIAC',
                    'level' => 2,
                    'h1' => 'Tử vi tuần mới của 12 cung hoàng đạo',
                    'description' => 'Theo dõi tử vi tuần mới của 12 cung hoàng đạo, bao gồm công việc, tài chính, tình cảm, sức khỏe và các mối quan hệ. Mỗi cung có phần dự báo riêng giúp bạn tham khảo cơ hội, thử thách và những thời điểm cần lưu ý trong tuần.',
                    'title_seo' => 'Tử vi tuần mới của 12 cung hoàng đạo',
                    'meta_des' => 'Xem tử vi tuần mới của 12 cung hoàng đạo, dự báo công việc, tài chính, tình yêu, sức khỏe và những điều đáng chú ý của từng cung trong tuần.',
                    'meta_key' => 'tử vi 12 cung hoàng đạo tuần này, tử vi tuần mới, tử vi hàng tuần, 12 cung hoàng đạo tuần này',
                ],

                "ZODIAC_12_CUNG_EVERY_MONTH" => [
                    'title' => 'Tử vi hàng tháng',
                    'slug' => 'tu-vi-12-cung-hoang-dao-hang-thang',
                    'type' => 'ZODIAC_12_CUNG_EVERY_MONTH',
                    'isPost' => 'news',
                    'parent' => 'ZODIAC',
                    'level' => 2,
                    'h1' => 'Tử vi tháng này của 12 cung hoàng đạo',
                    'description' => 'Xem tử vi tháng này của 12 cung hoàng đạo với các dự báo về sự nghiệp, tài chính, tình yêu, sức khỏe và đời sống cá nhân. Nội dung giúp bạn tham khảo những xu hướng chính, cơ hội và thử thách có thể xuất hiện trong tháng.',
                    'title_seo' => 'Tử vi tháng này của 12 cung hoàng đạo',
                    'meta_des' => 'Xem tử vi tháng này của 12 cung hoàng đạo, dự báo công việc, tài chính, tình yêu, sức khỏe, cơ hội và thử thách dành cho từng cung.',
                    'meta_key' => 'tử vi 12 cung hoàng đạo tháng này, tử vi hàng tháng, tử vi cung hoàng đạo tháng này, 12 cung hoàng đạo tháng này',
                ],

                "ZODIAC_12_CUNG_YEAR" => [
                    'title' => 'Tử vi năm ' . date('Y'),
                    'slug' => 'tu-vi-12-cung-hoang-dao-nam-' . date('Y'),
                    'type' => 'ZODIAC_12_CUNG_YEAR',
                    'isPost' => 'news',
                    'parent' => 'ZODIAC',
                    'level' => 2,
                    'h1' => 'Tử vi 12 cung hoàng đạo năm ' . date('Y'),
                    'description' => 'Khám phá tử vi 12 cung hoàng đạo năm ' . date('Y') . ' qua các phương diện công việc, tài chính, tình yêu, sức khỏe và phát triển cá nhân. Mỗi cung có phần tổng quan và các giai đoạn đáng chú ý để bạn tham khảo kế hoạch trong năm.',
                    'title_seo' => 'Tử vi 12 cung hoàng đạo năm ' . date('Y') . ': Vận trình từng cung',
                    'meta_des' => 'Xem tử vi 12 cung hoàng đạo năm ' . date('Y') . ', luận giải công việc, tài chính, tình yêu, sức khỏe, cơ hội và những giai đoạn đáng chú ý của từng cung.',
                    'meta_key' => 'tử vi 12 cung hoàng đạo năm ' . date('Y') . ', tử vi cung hoàng đạo ' . date('Y') . ', 12 cung hoàng đạo năm ' . date('Y') . ', tử vi năm ' . date('Y'),
                ],

                "ZODIAC_DECODING" => [
                    'title' => 'Giải mã 12 chòm sao',
                    'slug' => 'giai-ma-12-cung-hoang-dao',
                    'type' => 'ZODIAC_DECODING',
                    'isPost' => 'news',
                    'parent' => 'ZODIAC',
                    'level' => 2,
                    'h1' => 'Giải mã 12 cung hoàng đạo: Tính cách, tình yêu và sự nghiệp',
                    'description' => 'Tìm hiểu sâu hơn về ngày sinh, tính cách, ưu điểm, hạn chế, tình yêu và định hướng sự nghiệp của 12 cung hoàng đạo. Nội dung cũng phân tích mức độ hòa hợp giữa các cung trong tình bạn, tình cảm và cuộc sống.',
                    'title_seo' => 'Giải mã 12 cung hoàng đạo: Tính cách và tình yêu',
                    'meta_des' => 'Giải mã 12 cung hoàng đạo qua ngày sinh, tính cách, ưu nhược điểm, tình yêu, sự nghiệp và mức độ hòa hợp giữa các cung trong cuộc sống.',
                    'meta_key' => 'giải mã 12 cung hoàng đạo, tính cách 12 cung hoàng đạo, tình yêu 12 cung hoàng đạo, cung hoàng đạo hợp nhau',
                ],

                // ---------------------------
                "PHYSIOGNOMY" => [
                    'title' => 'Nhân tướng học',
                    'slug' => 'nhan-tuong-hoc',
                    'type' => 'PHYSIOGNOMY',
                    'position' => 1,
                    'level' => 1,
                    'h1' => 'Nhân tướng học: Xem tướng mặt, tướng tay và nốt ruồi',
                    'description' => 'Tổng hợp kiến thức nhân tướng học về khuôn mặt, bàn tay, nốt ruồi, dáng hình và thần thái theo quan niệm dân gian. Nội dung được trình bày theo từng đặc điểm để bạn dễ tra cứu, mang tính tham khảo văn hóa và không dùng để đánh giá giá trị hay phẩm chất của một người.',
                    'title_seo' => 'Nhân tướng học: Xem tướng mặt, tướng tay và nốt ruồi',
                    'meta_des' => 'Tổng hợp kiến thức nhân tướng học về khuôn mặt, bàn tay, nốt ruồi và đặc điểm tướng người theo quan niệm dân gian, trình bày dễ hiểu và chi tiết.',
                    'meta_key' => 'nhân tướng học, xem tướng, xem tướng mặt, xem tướng tay, xem nốt ruồi, tướng người',
                ],

                "PHYSIOGNOMY_READING_FACE" => [
                    'title' => 'Xem tướng mặt',
                    'slug' => 'xem-tuong-mat',
                    'type' => 'PHYSIOGNOMY_READING_FACE',
                    'isPost' => 'news',
                    'parent' => 'PHYSIOGNOMY',
                    'level' => 2,
                    'h1' => 'Xem tướng mặt nam nữ qua các nét trên khuôn mặt',
                    'description' => 'Tìm hiểu các đặc điểm trên khuôn mặt như trán, mắt, mũi, miệng, tai, cằm và nhân trung theo nhân tướng học dân gian. Nội dung giúp bạn tra cứu ý nghĩa truyền thống của từng nét tướng, không phải căn cứ khoa học để kết luận tính cách hoặc tương lai.',
                    'title_seo' => 'Xem tướng mặt nam nữ qua các nét trên khuôn mặt',
                    'meta_des' => 'Xem tướng mặt nam nữ qua hình dáng khuôn mặt, trán, mắt, mũi, miệng, tai, cằm và nhân trung theo kiến thức nhân tướng học.',
                    'meta_key' => 'xem tướng mặt, xem tướng khuôn mặt, tướng mặt nam, tướng mặt nữ, nhân tướng học khuôn mặt',
                ],

                "PHYSIOGNOMY_READING_HAND" => [
                    'title' => 'Xem tướng tay',
                    'slug' => 'xem-tuong-tay',
                    'type' => 'PHYSIOGNOMY_READING_HAND',
                    'isPost' => 'news',
                    'parent' => 'PHYSIOGNOMY',
                    'level' => 2,
                    'h1' => 'Xem tướng tay nam nữ và ý nghĩa các đường chỉ tay',
                    'description' => 'Khám phá ý nghĩa các đường sinh đạo, trí đạo, tâm đạo, định mệnh, hôn nhân và các gò trên bàn tay theo quan niệm xem tướng tay. Phần luận giải mang tính tham khảo văn hóa, không dùng để dự đoán chắc chắn sức khỏe, tính cách hoặc vận mệnh.',
                    'title_seo' => 'Xem tướng tay, chỉ tay nam nữ chi tiết',
                    'meta_des' => 'Xem tướng tay nam nữ, ý nghĩa đường sinh đạo, trí đạo, tâm đạo, định mệnh, hôn nhân và các đặc điểm trên bàn tay theo nhân tướng học.',
                    'meta_key' => 'xem tướng tay, xem chỉ tay, bói chỉ tay, đường chỉ tay, tướng tay nam, tướng tay nữ',
                ],

                "PHYSIOGNOMY_READING_PHYSIOGNOMY" => [
                    'title' => 'Xem tướng nốt ruồi',
                    'slug' => 'xem-tuong-not-ruoi',
                    'type' => 'PHYSIOGNOMY_READING_PHYSIOGNOMY',
                    'isPost' => 'news',
                    'parent' => 'PHYSIOGNOMY',
                    'level' => 2,
                    'h1' => 'Xem tướng nốt ruồi trên mặt và cơ thể nam nữ',
                    'description' => 'Tra cứu ý nghĩa các vị trí nốt ruồi trên khuôn mặt và cơ thể nam nữ theo quan niệm nhân tướng học. Nội dung phân chia theo từng vùng như trán, mắt, mũi, môi, tai, cổ, tay và chân, chỉ mang tính tham khảo dân gian.',
                    'title_seo' => 'Xem tướng nốt ruồi nam nữ trên mặt và cơ thể',
                    'meta_des' => 'Xem ý nghĩa các vị trí nốt ruồi trên mặt và cơ thể nam nữ, gồm trán, mắt, mũi, môi, tai, cổ, tay và chân theo nhân tướng học.',
                    'meta_key' => 'xem tướng nốt ruồi, xem nốt ruồi, bói nốt ruồi, nốt ruồi trên mặt, nốt ruồi nam nữ',
                ],

                "PHYSIOGNOMY_IDENTIFYING_FACIAL_FEATURES" => [
                    'title' => 'Nhận diện tướng người',
                    'slug' => 'nhan-dien-tuong-nguoi',
                    'type' => 'PHYSIOGNOMY_IDENTIFYING_FACIAL_FEATURES',
                    'isPost' => 'news',
                    'parent' => 'PHYSIOGNOMY',
                    'level' => 2,
                    'h1' => 'Nhận diện tướng người qua khuôn mặt và dáng hình',
                    'description' => 'Tìm hiểu cách phân loại các đặc điểm tướng mạo qua khuôn mặt, dáng hình, thần thái và cử chỉ trong tài liệu nhân tướng học dân gian. Nội dung nhằm cung cấp góc nhìn văn hóa, không nên dùng ngoại hình để phán xét phẩm chất, năng lực hoặc giá trị của bất kỳ ai.',
                    'title_seo' => 'Nhận diện tướng người theo nhân tướng học',
                    'meta_des' => 'Tìm hiểu cách nhận diện các đặc điểm tướng người qua khuôn mặt, dáng hình, thần thái và cử chỉ theo kiến thức nhân tướng học dân gian.',
                    'meta_key' => 'nhận diện tướng người, xem tướng người, tướng người, nhân tướng học, đặc điểm tướng mạo',
                ],

                // ---------------------------
                "AGE" => [
                    'title' => 'Xem tuổi',
                    'slug' => 'xem-tuoi',
                    'type' => 'AGE',
                    'position' => 1,
                    'level' => 1,
                    'h1' => 'Xem tuổi hợp trong tình duyên, làm ăn và cuộc sống',
                    'description' => 'Tổng hợp các công cụ xem tuổi theo năm sinh dành cho tình duyên, hôn nhân, làm ăn, sinh con, làm nhà và xông đất. Kết quả phân tích can chi, ngũ hành, cung mệnh cùng các yếu tố dân gian, giúp bạn có thêm thông tin tham khảo trước những kế hoạch quan trọng.',
                    'title_seo' => 'Xem tuổi hợp làm ăn, kết hôn, sinh con và làm nhà',
                    'meta_des' => 'Xem tuổi hợp trong tình yêu, hôn nhân, làm ăn, sinh con, làm nhà và xông đất. Tra cứu mức độ tương hợp theo năm sinh, can chi và ngũ hành.',
                    'meta_key' => 'xem tuổi, xem tuổi hợp nhau, xem tuổi làm ăn, xem tuổi kết hôn, xem tuổi sinh con, xem tuổi làm nhà',
                ],

                "AGE_VISIT_LAND" => [
                    'title' => 'Xem tuổi xông đất ' . date('Y'),
                    'slug' => 'xem-tuoi-xong-dat',
                    'type' => 'AGE_VISIT_LAND',
                    'parent' => 'AGE',
                    'level' => 2,
                    'h1' => 'Xem tuổi xông đất năm ' . date('Y') . ' hợp tuổi gia chủ',
                    'description' => 'Tra cứu tuổi xông đất năm ' . date('Y') . ' phù hợp với tuổi gia chủ theo can chi và ngũ hành. Công cụ gợi ý các tuổi nên ưu tiên hoặc cần cân nhắc, giúp gia đình tham khảo khi chọn người xông nhà đầu năm.',
                    'title_seo' => 'Xem tuổi xông đất năm ' . date('Y') . ' hợp tuổi gia chủ',
                    'meta_des' => 'Xem tuổi xông đất năm ' . date('Y') . ' hợp với tuổi gia chủ. Tra cứu tuổi nên chọn, tuổi cần cân nhắc theo can chi, ngũ hành và quan niệm dân gian.',
                    'meta_key' => 'xem tuổi xông đất ' . date('Y') . ', tuổi xông nhà ' . date('Y') . ', chọn tuổi xông đất, tuổi xông đất hợp gia chủ',
                ],

                "AGE_COUPLE" => [
                    'title' => 'Xem tuổi vợ chồng',
                    'slug' => 'xem-tuoi-vo-chong',
                    'type' => 'AGE_COUPLE',
                    'parent' => 'AGE',
                    'level' => 2,
                    'h1' => 'Xem tuổi vợ chồng có hợp nhau không',
                    'description' => 'Nhập năm sinh của vợ và chồng để xem mức độ hòa hợp theo can chi, ngũ hành và cung mệnh. Phần luận giải chỉ ra điểm tương đồng, khác biệt và những yếu tố cần lưu ý, mang tính tham khảo cho đời sống hôn nhân.',
                    'title_seo' => 'Xem tuổi vợ chồng có hợp nhau không theo năm sinh',
                    'meta_des' => 'Xem tuổi vợ chồng theo năm sinh, phân tích mức độ hòa hợp về can chi, ngũ hành, cung mệnh và những yếu tố cần lưu ý trong đời sống hôn nhân.',
                    'meta_key' => 'xem tuổi vợ chồng, tuổi vợ chồng có hợp nhau không, xem tuổi vợ chồng theo năm sinh, xem tuổi cưới hỏi',
                ],

                "AGE_MAKE_CHILD" => [
                    'title' => 'Xem tuổi sinh con',
                    'slug' => 'xem-tuoi-sinh-con',
                    'type' => 'AGE_MAKE_CHILD',
                    'parent' => 'AGE',
                    'level' => 2,
                    'h1' => 'Xem tuổi sinh con hợp tuổi bố mẹ',
                    'description' => 'Tra cứu năm sinh con phù hợp với tuổi bố mẹ dựa trên can chi, ngũ hành và mức độ tương hợp trong gia đình. Kết quả hỗ trợ tham khảo khi lập kế hoạch sinh con, không thay thế tư vấn y tế hoặc các quyết định cá nhân.',
                    'title_seo' => 'Xem tuổi sinh con hợp tuổi bố mẹ theo năm sinh',
                    'meta_des' => 'Xem tuổi sinh con hợp tuổi bố mẹ theo năm dự sinh. Phân tích can chi, ngũ hành và mức độ tương hợp giữa tuổi con với tuổi cha mẹ để tham khảo.',
                    'meta_key' => 'xem tuổi sinh con, sinh con hợp tuổi bố mẹ, xem năm sinh con, chọn năm sinh con, tuổi con hợp bố mẹ',
                ],

                "AGE_MARRIAGE" => [
                    'title' => 'Xem tuổi kết hôn',
                    'slug' => 'xem-tuoi-ket-hon',
                    'type' => 'AGE_MARRIAGE',
                    'parent' => 'AGE',
                    'level' => 2,
                    'h1' => 'Xem tuổi kết hôn và chọn năm cưới phù hợp',
                    'description' => 'Xem tuổi kết hôn và tham khảo năm cưới phù hợp cho nam nữ theo tuổi âm lịch, can chi, ngũ hành và Kim Lâu. Công cụ giúp bạn kiểm tra các yếu tố thường được quan tâm khi lên kế hoạch cưới hỏi.',
                    'title_seo' => 'Xem tuổi kết hôn, chọn năm cưới hợp tuổi nam nữ',
                    'meta_des' => 'Xem tuổi kết hôn và chọn năm cưới phù hợp theo tuổi nam nữ. Tra cứu can chi, ngũ hành, tuổi Kim Lâu và các yếu tố thường được xem khi cưới hỏi.',
                    'meta_key' => 'xem tuổi kết hôn, xem tuổi cưới, chọn năm cưới, tuổi kết hôn nam nữ, xem năm cưới hợp tuổi',
                ],

                "AGE_COMPATIBLE" => [
                    'title' => 'Xem tuổi hợp nhau',
                    'slug' => 'xem-tuoi-hop-nhau',
                    'type' => 'AGE_COMPATIBLE',
                    'parent' => 'AGE',
                    'level' => 2,
                    'h1' => 'Xem hai tuổi có hợp nhau không theo năm sinh',
                    'description' => 'Nhập năm sinh của hai người để xem mức độ hợp nhau theo can chi, ngũ hành và cung mệnh. Kết quả có thể dùng tham khảo trong tình cảm, tình bạn, công việc hoặc các mối quan hệ, không quyết định chất lượng thực tế của mối quan hệ.',
                    'title_seo' => 'Xem tuổi hợp nhau theo năm sinh chính xác, dễ hiểu',
                    'meta_des' => 'Xem hai tuổi có hợp nhau không qua năm sinh, can chi, ngũ hành và cung mệnh. Tra cứu mức độ tương hợp trong tình cảm, công việc và cuộc sống.',
                    'meta_key' => 'xem tuổi hợp nhau, hai tuổi có hợp nhau không, xem tuổi theo năm sinh, tuổi hợp nhau, kiểm tra tuổi hợp',
                ],

                "AGE_BUSINESS_WORKING" => [
                    'title' => 'Xem tuổi làm ăn',
                    'slug' => 'xem-tuoi-lam-an',
                    'type' => 'AGE_BUSINESS_WORKING',
                    'parent' => 'AGE',
                    'level' => 2,
                    'h1' => 'Xem tuổi làm ăn hợp nhau theo năm sinh',
                    'description' => 'Xem mức độ hợp tuổi giữa các đối tác dựa trên năm sinh, can chi và ngũ hành. Phần luận giải giúp bạn tham khảo khi lựa chọn người hợp tác, góp vốn hoặc đồng hành công việc, nhưng không thay thế việc đánh giá năng lực và điều kiện kinh doanh thực tế.',
                    'title_seo' => 'Xem tuổi làm ăn hợp nhau, chọn tuổi hợp tác kinh doanh',
                    'meta_des' => 'Xem tuổi làm ăn hợp nhau theo năm sinh, can chi và ngũ hành. Tham khảo mức độ tương hợp khi hợp tác kinh doanh, góp vốn hoặc đồng hành công việc.',
                    'meta_key' => 'xem tuổi làm ăn, tuổi hợp tác làm ăn, xem tuổi kinh doanh, tuổi làm ăn hợp nhau, chọn người hợp tác',
                ],

                "AGE_BUILDING_HOUSE" => [
                    'title' => 'Xem tuổi làm nhà',
                    'slug' => 'xem-tuoi-lam-nha',
                    'type' => 'AGE_BUILDING_HOUSE',
                    'parent' => 'AGE',
                    'level' => 2,
                    'h1' => 'Xem tuổi làm nhà năm ' . date('Y') . ' hợp tuổi gia chủ',
                    'description' => 'Xem tuổi làm nhà năm ' . date('Y') . ' và kiểm tra Kim Lâu, Hoang Ốc, Tam Tai theo tuổi gia chủ. Kết quả giúp tham khảo thời điểm xây, sửa, động thổ hoặc phương án mượn tuổi khi năm dự kiến chưa phù hợp.',
                    'title_seo' => 'Xem tuổi làm nhà năm ' . date('Y') . ': Kim Lâu, Hoang Ốc, Tam Tai',
                    'meta_des' => 'Xem tuổi làm nhà năm ' . date('Y') . ', kiểm tra Kim Lâu, Hoang Ốc và Tam Tai theo tuổi gia chủ. Tham khảo năm phù hợp để xây, sửa hoặc động thổ.',
                    'meta_key' => 'xem tuổi làm nhà ' . date('Y') . ', tuổi xây nhà ' . date('Y') . ', xem tuổi động thổ, Kim Lâu, Hoang Ốc, Tam Tai',
                ],

                // ---------------------------
                "NUMEROLOGY" => [
                    'title' => 'Thần số học',
                    'slug' => 'than-so-hoc',
                    'type' => 'NUMEROLOGY',
                    'position' => 1,
                    'level' => 1,
                    'h1' => 'Thần số học: Ý nghĩa các con số và cách tính chỉ số',
                    'description' => 'Khám phá thần số học qua ngày sinh và họ tên, từ cách tính số chủ đạo đến các chỉ số đường đời, sứ mệnh, linh hồn và nhân cách. Nội dung giúp bạn hiểu hệ thống ý nghĩa các con số theo trường phái numerology, mang tính tham khảo phát triển bản thân.',
                    'title_seo' => 'Thần số học: Cách tính và ý nghĩa các chỉ số',
                    'meta_des' => 'Tìm hiểu thần số học qua ngày sinh và họ tên, cách tính số chủ đạo, đường đời, sứ mệnh, linh hồn, nhân cách cùng ý nghĩa các chỉ số quan trọng.',
                    'meta_key' => 'thần số học, numerology, số chủ đạo, cách tính thần số học, ý nghĩa các con số, chỉ số đường đời',
                ],

                "NUMEROLOGY_SEARCH" => [
                    'title' => 'Tra cứu thần số học',
                    'slug' => 'tra-cuu-than-so-hoc',
                    'type' => 'NUMEROLOGY_SEARCH',
                    'parent' => 'NUMEROLOGY',
                    'level' => 2,
                    'h1' => 'Tra cứu thần số học online miễn phí theo ngày sinh và họ tên',
                    'description' => 'Nhập họ tên và ngày sinh để tra cứu các chỉ số thần số học cá nhân. Hệ thống tính số chủ đạo, đường đời, sứ mệnh, linh hồn, nhân cách cùng phần luận giải chi tiết, giúp bạn tham khảo điểm mạnh, xu hướng và định hướng phát triển.',
                    'title_seo' => 'Tra cứu thần số học online miễn phí, luận giải chi tiết',
                    'meta_des' => 'Tra cứu thần số học online miễn phí theo ngày sinh và họ tên. Xem số chủ đạo, đường đời, sứ mệnh, linh hồn, nhân cách và các chỉ số cá nhân.',
                    'meta_key' => 'tra cứu thần số học, thần số học online, xem thần số học miễn phí, số chủ đạo, thần số học theo ngày sinh và họ tên',
                ],

                // ---------------------------
                "MANNERS" => [
                    'title' => 'Phong tục',
                    'slug' => 'phong-tuc-tap-quan',
                    'type' => 'MANNERS',
                    'position' => 1,
                    'level' => 1,
                    'h1' => 'Phong tục tập quán Việt Nam trong đời sống và nghi lễ',
                    'description' => 'Khám phá phong tục tập quán Việt Nam trong cưới hỏi, ngày Tết, xuất hành, khai trương, xây dựng và tang lễ. Nội dung giới thiệu ý nghĩa, trình tự và những điều thường được chuẩn bị trong từng nghi lễ, đồng thời ghi nhận sự khác biệt giữa các vùng miền.',
                    'title_seo' => 'Phong tục tập quán Việt Nam: Cưới hỏi, Tết và nghi lễ',
                    'meta_des' => 'Tổng hợp phong tục tập quán Việt Nam về cưới hỏi, ngày Tết, xuất hành, khai trương, xây dựng, tang lễ và mai táng theo văn hóa từng vùng miền.',
                    'meta_key' => 'phong tục tập quán, phong tục Việt Nam, văn hóa Việt Nam, phong tục cưới hỏi, phong tục ngày Tết, nghi lễ truyền thống',
                ],

                "MANNERS_WEDDING" => [
                    'title' => 'Cưới hỏi',
                    'slug' => 'phong-tuc-cuoi-hoi',
                    'type' => 'MANNERS_WEDDING',
                    'isPost' => 'news',
                    'level' => 2,
                    'parent' => 'MANNERS',
                    'h1' => 'Phong tục cưới hỏi Việt Nam và các nghi lễ truyền thống',
                    'description' => 'Tìm hiểu trình tự và ý nghĩa các nghi lễ cưới hỏi truyền thống của người Việt như dạm ngõ, ăn hỏi, xin dâu, lễ cưới và lại mặt. Nội dung cũng giới thiệu lễ vật, cách chuẩn bị và những khác biệt thường gặp giữa các vùng miền.',
                    'title_seo' => 'Phong tục cưới hỏi Việt Nam và trình tự các nghi lễ',
                    'meta_des' => 'Tìm hiểu phong tục cưới hỏi Việt Nam, trình tự dạm ngõ, ăn hỏi, xin dâu, lễ cưới, lễ lại mặt cùng lễ vật và nghi thức phổ biến ở các vùng miền.',
                    'meta_key' => 'phong tục cưới hỏi, nghi lễ cưới hỏi, lễ dạm ngõ, lễ ăn hỏi, lễ xin dâu, lễ cưới Việt Nam',
                ],

                "MANNERS_SETTING_OUT" => [
                    'title' => 'Xuất hành',
                    'slug' => 'phong-tuc-xuat-hanh',
                    'type' => 'MANNERS_SETTING_OUT',
                    'isPost' => 'news',
                    'level' => 2,
                    'parent' => 'MANNERS',
                    'h1' => 'Phong tục xuất hành đầu năm của người Việt',
                    'description' => 'Tìm hiểu phong tục xuất hành đầu năm, ý nghĩa của chuyến đi đầu tiên và cách chọn ngày giờ, hướng đi theo quan niệm dân gian. Nội dung giúp bạn tham khảo những điều nên chuẩn bị và lưu ý khi xuất hành dịp năm mới.',
                    'title_seo' => 'Phong tục xuất hành đầu năm: Ngày, giờ và hướng đi',
                    'meta_des' => 'Tìm hiểu phong tục xuất hành đầu năm, ý nghĩa, cách chọn ngày giờ, hướng xuất hành và những điều nên lưu ý theo quan niệm văn hóa dân gian Việt Nam.',
                    'meta_key' => 'phong tục xuất hành, xuất hành đầu năm, chọn ngày xuất hành, giờ xuất hành, hướng xuất hành',
                ],

                "MANNERS_GRAND_OPENING" => [
                    'title' => 'Khai trương',
                    'slug' => 'phong-tuc-khai-truong',
                    'type' => 'MANNERS_GRAND_OPENING',
                    'isPost' => 'news',
                    'level' => 2,
                    'parent' => 'MANNERS',
                    'h1' => 'Phong tục khai trương, mở hàng và khai xuân',
                    'description' => 'Khám phá phong tục khai trương, mở hàng và khai xuân trong kinh doanh. Nội dung hướng dẫn tham khảo cách chuẩn bị lễ cúng, chọn ngày giờ, người mở hàng và các nghi thức phổ biến với mong muốn khởi đầu thuận lợi.',
                    'title_seo' => 'Phong tục khai trương, mở hàng và khai xuân đầu năm',
                    'meta_des' => 'Tìm hiểu phong tục khai trương, mở hàng và khai xuân: cách chuẩn bị lễ cúng, chọn người mở hàng, ngày giờ phù hợp và các nghi thức phổ biến.',
                    'meta_key' => 'phong tục khai trương, lễ khai trương, mở hàng đầu năm, khai xuân, người mở hàng',
                ],

                "MANNERS_TET" => [
                    'title' => 'Phong tục ngày Tết',
                    'slug' => 'phong-tuc-ngay-tet',
                    'type' => 'MANNERS_TET',
                    'isPost' => 'news',
                    'level' => 2,
                    'parent' => 'MANNERS',
                    'h1' => 'Phong tục ngày Tết cổ truyền của người Việt Nam',
                    'description' => 'Tổng hợp phong tục ngày Tết cổ truyền Việt Nam từ cúng ông Công ông Táo, gói bánh chưng, tất niên, giao thừa đến xông đất, chúc Tết, lì xì và du xuân. Mỗi phong tục được giải thích về nguồn gốc, ý nghĩa và cách thực hiện phổ biến.',
                    'title_seo' => 'Phong tục ngày Tết cổ truyền Việt Nam và ý nghĩa',
                    'meta_des' => 'Khám phá phong tục ngày Tết Việt Nam như cúng ông Công ông Táo, gói bánh chưng, tất niên, giao thừa, xông đất, chúc Tết, lì xì và du xuân.',
                    'meta_key' => 'phong tục ngày Tết, Tết cổ truyền Việt Nam, phong tục Tết Nguyên đán, xông đất, chúc Tết, lì xì',
                ],

                "MANNERS_BUILDING" => [
                    'title' => 'Xây dựng & động thổ',
                    'slug' => 'phong-tuc-xay-dung-dong-tho',
                    'type' => 'MANNERS_BUILDING',
                    'isPost' => 'news',
                    'level' => 2,
                    'parent' => 'MANNERS',
                    'h1' => 'Phong tục xây dựng, động thổ và về nhà mới',
                    'description' => 'Tìm hiểu các phong tục và nghi lễ thường gặp khi xây nhà như động thổ, đặt móng, cất nóc, nhập trạch và về nhà mới. Nội dung giới thiệu cách chuẩn bị, trình tự thực hiện và những lưu ý theo quan niệm dân gian từng vùng.',
                    'title_seo' => 'Phong tục động thổ, xây nhà, cất nóc và nhập trạch',
                    'meta_des' => 'Tìm hiểu phong tục xây dựng nhà ở gồm động thổ, đặt móng, cất nóc, nhập trạch, về nhà mới cùng các nghi lễ và điều cần chuẩn bị theo dân gian.',
                    'meta_key' => 'phong tục xây dựng, lễ động thổ, lễ cất nóc, lễ nhập trạch, phong tục làm nhà, về nhà mới',
                ],

                "MANNERS_FUNERAL_RITES" => [
                    'title' => 'Tang lễ & tẩm liệm',
                    'slug' => 'phong-tuc-tang-le-tam-liem',
                    'type' => 'MANNERS_FUNERAL_RITES',
                    'isPost' => 'news',
                    'level' => 2,
                    'parent' => 'MANNERS',
                    'h1' => 'Phong tục tang lễ và nghi thức tẩm liệm',
                    'description' => 'Tìm hiểu các nghi thức tang lễ trước khi an táng như tẩm liệm, nhập quan, phát tang, phúng viếng và động quan. Nội dung mang tính thông tin văn hóa, trình bày trang trọng và lưu ý sự khác biệt về phong tục, tôn giáo và vùng miền.',
                    'title_seo' => 'Phong tục tang lễ, tẩm liệm và nhập quan của người Việt',
                    'meta_des' => 'Tìm hiểu phong tục tang lễ của người Việt, các nghi thức tẩm liệm, nhập quan, phát tang, phúng viếng, động quan và những lưu ý theo từng vùng miền.',
                    'meta_key' => 'phong tục tang lễ, lễ tẩm liệm, khâm liệm, lễ nhập quan, phát tang, nghi thức tang lễ',
                ],

                "MANNERS_BURIAL" => [
                    'title' => 'Mai táng & chôn cất',
                    'slug' => 'phong-tuc-mai-tang-chon-cat',
                    'type' => 'MANNERS_BURIAL',
                    'isPost' => 'news',
                    'level' => 2,
                    'parent' => 'MANNERS',
                    'h1' => 'Phong tục mai táng và chôn cất của người Việt',
                    'description' => 'Tìm hiểu phong tục mai táng và chôn cất của người Việt, bao gồm an táng, hạ huyệt, cải táng và chăm sóc phần mộ. Nội dung mang tính tham khảo văn hóa, tôn trọng sự khác biệt trong tín ngưỡng, tôn giáo và tập quán địa phương.',
                    'title_seo' => 'Phong tục mai táng, chôn cất và an táng của người Việt',
                    'meta_des' => 'Tìm hiểu phong tục mai táng, chôn cất, an táng, hạ huyệt, cải táng và chăm sóc phần mộ theo văn hóa truyền thống của người Việt ở các vùng miền.',
                    'meta_key' => 'phong tục mai táng, phong tục chôn cất, lễ an táng, lễ hạ huyệt, cải táng, chăm sóc phần mộ',
                ],

                // ---------------------------
                "OTHER" => [
                    'title' => 'Khác',
                    'slug' => 'khac',
                    'type' => 'OTHER',
                    'position' => 1,
                    'level' => 1,
                    'description' => 'Tổng hợp các chuyên mục mở rộng về phong tục, nhân sinh, đời sống, văn khấn, danh ngôn và lời chúc. Khu vực này giúp bạn dễ dàng khám phá những nội dung văn hóa, tinh thần và kiến thức hữu ích chưa thuộc các nhóm công cụ chính trên website.',
                    'title_seo' => '',
                    'meta_des' => '',
                    'meta_key' => '',
                ],

                // ---------------------------
                "HUMAN_LIFE" => [
                    'title' => 'Nhân sinh',
                    'slug' => 'nhan-sinh',
                    'type' => 'HUMAN_LIFE',
                    'position' => 2,
                    'level' => 1,
                    'parent' => 'OTHER',
                    'h1' => 'Nhân sinh: Bài học cuộc sống và đạo lý làm người',
                    'description' => 'Khám phá những bài học nhân sinh, đạo lý làm người, cách đối nhân xử thế và các suy ngẫm về cuộc sống. Nội dung hướng đến góc nhìn tích cực, giúp người đọc chiêm nghiệm về lựa chọn, trách nhiệm, lòng biết ơn và cách giữ sự bình an trong đời sống.',
                    'title_seo' => 'Nhân sinh: Bài học cuộc sống và đạo lý làm người',
                    'meta_des' => 'Tổng hợp bài học nhân sinh, đạo lý làm người, cách đối nhân xử thế và những suy ngẫm ý nghĩa giúp sống tích cực, tỉnh thức và bình an hơn.',
                    'meta_key' => 'nhân sinh, bài học cuộc sống, đạo lý làm người, triết lý sống, đối nhân xử thế, cuộc sống ý nghĩa',
                ],

                "HUMAN_LAW_CAUSE" => [
                    'title' => 'Luật nhân quả',
                    'slug' => 'luat-nhan-qua',
                    'type' => 'HUMAN_LAW_CAUSE',
                    'isPost' => 'news',
                    'level' => 2,
                    'parent' => 'HUMAN_LIFE',
                    'h1' => 'Luật nhân quả trong cuộc sống và những bài học ý nghĩa',
                    'description' => 'Tìm hiểu luật nhân quả qua mối liên hệ giữa suy nghĩ, hành động, duyên và kết quả trong cuộc sống. Chuyên mục tổng hợp các câu chuyện và bài học hướng thiện, nhấn mạnh trách nhiệm cá nhân thay vì diễn giải nhân quả như một kết luận tuyệt đối hoặc tức thời.',
                    'title_seo' => 'Luật nhân quả: Ý nghĩa và bài học trong cuộc sống',
                    'meta_des' => 'Tìm hiểu luật nhân quả, nhân duyên, nghiệp và kết quả của mỗi hành động. Tổng hợp những câu chuyện, bài học giúp sống trách nhiệm và hướng thiện hơn.',
                    'meta_key' => 'luật nhân quả, nhân quả trong cuộc sống, nghiệp và nhân quả, nhân duyên quả, bài học nhân quả, sống hướng thiện',
                ],

                "HUMAN_BUDDHIST_TEACHING" => [
                    'title' => 'Lời Phật dạy',
                    'slug' => 'loi-phat-day',
                    'type' => 'HUMAN_BUDDHIST_TEACHING',
                    'isPost' => 'news',
                    'level' => 2,
                    'parent' => 'HUMAN_LIFE',
                    'h1' => 'Lời Phật dạy về cuộc sống, nhân quả và bình an',
                    'description' => 'Tổng hợp các bài viết về lời Phật dạy trong cuộc sống, nhân quả, lòng từ bi, sự buông bỏ và tỉnh thức. Nội dung hướng đến việc hiểu và ứng dụng giáo lý một cách bình tĩnh, có nguồn tham khảo, tránh gán ghép những câu nói chưa được kiểm chứng.',
                    'title_seo' => 'Lời Phật dạy về cuộc sống, nhân quả và bình an',
                    'meta_des' => 'Tổng hợp lời Phật dạy về cuộc sống, nhân quả, tình yêu thương, lòng từ bi, sự buông bỏ và cách giữ tâm an nhiên, tỉnh thức trước mọi hoàn cảnh.',
                    'meta_key' => 'lời Phật dạy, lời Phật dạy về cuộc sống, lời Phật dạy về nhân quả, Phật pháp, sống an nhiên, lòng từ bi',
                ],

                // ---------------------------
                "LIFE" => [
                    'title' => 'Đời sống',
                    'slug' => 'doi-song',
                    'type' => 'LIFE',
                    'position' => 2,
                    'level' => 1,
                    'parent' => 'OTHER',
                    'h1' => 'Đời sống: Câu chuyện, danh ngôn và cảm hứng mỗi ngày',
                    'description' => 'Khám phá các nội dung đời sống gần gũi qua câu chuyện thường ngày, danh ngôn, câu nói, caption và status hay. Chuyên mục xoay quanh tình yêu, gia đình, tình bạn, công việc, tâm trạng và những cảm hứng tích cực trong cuộc sống.',
                    'title_seo' => 'Đời sống: Câu chuyện, danh ngôn và caption hay',
                    'meta_des' => 'Tổng hợp câu chuyện đời thường, danh ngôn, câu nói, caption và status hay về tình yêu, gia đình, công việc, tâm trạng và cuộc sống mỗi ngày.',
                    'meta_key' => 'đời sống, câu chuyện đời thường, danh ngôn hay, câu nói hay, caption hay, status hay',
                ],

                "LIFE_FAMOUS_QUOTES" => [
                    'title' => 'Danh ngôn & câu nói hay',
                    'slug' => 'danh-ngon-cau-noi-hay',
                    'type' => 'LIFE_FAMUOS_QUOTES',
                    'isPost' => 'news',
                    'level' => 2,
                    'parent' => 'LIFE',
                    'h1' => 'Danh ngôn và câu nói hay về cuộc sống, tình yêu, thành công',
                    'description' => 'Tổng hợp danh ngôn và câu nói hay theo các chủ đề cuộc sống, tình yêu, gia đình, tình bạn, thành công và nghị lực. Nội dung phù hợp để suy ngẫm, chia sẻ hoặc tìm cảm hứng, đồng thời ưu tiên ghi rõ tác giả và nguồn khi có thể.',
                    'title_seo' => 'Danh ngôn, câu nói hay về cuộc sống và tình yêu',
                    'meta_des' => 'Tổng hợp danh ngôn và câu nói hay về cuộc sống, tình yêu, gia đình, tình bạn, thành công và nghị lực, giúp bạn suy ngẫm và tìm thêm cảm hứng.',
                    'meta_key' => 'danh ngôn hay, câu nói hay, danh ngôn cuộc sống, câu nói hay về tình yêu, danh ngôn thành công, câu nói truyền cảm hứng',
                ],

                "LIFE_EVERY_DAY" => [
                    'title' => 'Chuyện đời thường',
                    'slug' => 'chuyen-doi-thuong',
                    'type' => 'LIFE_EVERY_DAY',
                    'isPost' => 'news',
                    'level' => 2,
                    'parent' => 'LIFE',
                    'h1' => 'Chuyện đời thường về gia đình, tình yêu và cuộc sống',
                    'description' => 'Những câu chuyện đời thường về gia đình, tình yêu, tình bạn, công việc và các mối quan hệ xung quanh. Mỗi bài viết mang đến một góc nhìn gần gũi, cảm xúc hoặc bài học thực tế để người đọc cùng suy ngẫm.',
                    'title_seo' => 'Chuyện đời thường, gia đình, tình yêu và cuộc sống',
                    'meta_des' => 'Những câu chuyện đời thường về gia đình, tình yêu, tình bạn, công việc và các mối quan hệ, mang đến góc nhìn gần gũi và bài học ý nghĩa.',
                    'meta_key' => 'chuyện đời thường, câu chuyện cuộc sống, chuyện gia đình, chuyện tình yêu, chuyện ý nghĩa, bài học cuộc sống',
                ],

                "LIFE_CAPTION_HAY" => [
                    'title' => 'Caption & status hay',
                    'slug' => 'caption-status-hay',
                    'type' => 'LIFE_CAPTION_HAY',
                    'isPost' => 'news',
                    'level' => 2,
                    'parent' => 'LIFE',
                    'h1' => 'Caption, status hay về tình yêu, cuộc sống và tâm trạng',
                    'description' => 'Tổng hợp caption, STT và status ngắn gọn dành cho tình yêu, cuộc sống, tình bạn, gia đình, tâm trạng và động lực. Bạn có thể lựa chọn nội dung phù hợp để đăng Facebook, TikTok, Instagram hoặc gửi đến những người quan tâm.',
                    'title_seo' => 'Caption, STT hay về tình yêu, cuộc sống và tâm trạng',
                    'meta_des' => 'Tổng hợp caption, STT và status hay về tình yêu, cuộc sống, tình bạn, gia đình, tâm trạng và động lực, phù hợp đăng Facebook, TikTok, Instagram.',
                    'meta_key' => 'caption hay, status hay, stt hay, caption tình yêu, caption cuộc sống, stt tâm trạng, caption đăng mạng xã hội',
                ],

                // ---------------------------
                "HUMAN_PRAYER" => [
                    'title' => 'Bài văn khấn',
                    'slug' => 'bai-van-khan',
                    'type' => 'HUMAN_PRAYER',
                    'isPost' => 'news',
                    'position' => 2,
                    'level' => 1,
                    'parent' => 'OTHER',
                    'h1' => 'Bài văn khấn cổ truyền Việt Nam theo từng dịp lễ',
                    'description' => 'Tổng hợp các bài văn khấn cổ truyền dành cho gia tiên, Thần Tài, ngày rằm, mùng 1, lễ Tết, cúng giỗ, nhập trạch, khai trương và nhiều nghi lễ khác. Nội dung được sắp xếp theo từng dịp để bạn dễ tra cứu và chuẩn bị trang nghiêm, phù hợp phong tục gia đình.',
                    'title_seo' => 'Bài văn khấn cổ truyền Việt Nam đầy đủ theo từng dịp',
                    'meta_des' => 'Tổng hợp bài văn khấn gia tiên, Thần Tài, ngày rằm, mùng 1, lễ Tết, cúng giỗ, nhập trạch, khai trương và các nghi lễ truyền thống của người Việt.',
                    'meta_key' => 'bài văn khấn, văn khấn cổ truyền, văn khấn gia tiên, văn khấn Thần Tài, văn khấn mùng 1, văn khấn ngày rằm, văn khấn nhập trạch',
                ],

                // ---------------------------
                "HUMAN_FAMOUS_QUOTES" => [
                    'title' => 'Danh ngôn nổi tiếng',
                    'slug' => 'danh-ngon-noi-tieng',
                    'type' => 'HUMAN_FAMOUS_QUOTES',
                    'isPost' => 'news',
                    'position' => 2,
                    'level' => 1,
                    'parent' => 'OTHER',
                    'h1' => 'Danh ngôn nổi tiếng về cuộc sống, tình yêu và thành công',
                    'description' => 'Khám phá những danh ngôn nổi tiếng về cuộc sống, tình yêu, gia đình, tình bạn, thành công và nghị lực. Chuyên mục ưu tiên các câu nói có tác giả hoặc nguồn rõ ràng, giúp bạn tìm cảm hứng, suy ngẫm và chia sẻ đúng ngữ cảnh.',
                    'title_seo' => 'Danh ngôn nổi tiếng về cuộc sống, tình yêu, thành công',
                    'meta_des' => 'Tổng hợp danh ngôn nổi tiếng về cuộc sống, tình yêu, gia đình, tình bạn, thành công và nghị lực từ các tác giả, nhân vật có ảnh hưởng.',
                    'meta_key' => 'danh ngôn nổi tiếng, danh ngôn hay, danh ngôn cuộc sống, danh ngôn tình yêu, danh ngôn thành công, câu nói nổi tiếng',
                ],

                // ---------------------------
                "HUMAN_WISH" => [
                    'title' => 'Lời chúc hay',
                    'slug' => 'loi-chuc-hay',
                    'type' => 'HUMAN_WISH',
                    'isPost' => 'news',
                    'position' => 2,
                    'level' => 1,
                    'parent' => 'OTHER',
                    'h1' => 'Lời chúc hay, ý nghĩa dành cho mọi dịp',
                    'description' => 'Tổng hợp lời chúc hay và ý nghĩa cho sinh nhật, năm mới, đám cưới, khai trương, ngày lễ và nhiều dịp đặc biệt. Nội dung được phân theo người nhận như gia đình, bạn bè, người yêu, thầy cô và đồng nghiệp để bạn dễ chọn lời phù hợp.',
                    'title_seo' => 'Lời chúc hay, ý nghĩa dành cho mọi dịp đặc biệt',
                    'meta_des' => 'Tổng hợp lời chúc hay, ý nghĩa dành cho sinh nhật, năm mới, đám cưới, khai trương, ngày lễ, gia đình, bạn bè, người yêu và đồng nghiệp.',
                    'meta_key' => 'lời chúc hay, lời chúc ý nghĩa, lời chúc sinh nhật, lời chúc năm mới, lời chúc đám cưới, lời chúc khai trương',
                ],
            ];
        }

        public const mt = [
            'list' => [
                'da-nang' => 'Đà Nẵng',
                'khanh-hoa' => 'Khánh Hòa',
                'binh-dinh' => 'Bình Định',
                'quang-nam' => 'Quảng Nam',
                'quang-ngai' => 'Quảng Ngãi',
                'quang-binh' => 'Quảng Bình',
                'quang-tri' => 'Quảng Trị',
                'hue' => 'Huế',
                'phu-yen' => 'Phú Yên',
                'ninh-thuan' => 'Ninh Thuận',
                'gia-lai' => 'Gia Lai',
                'dak-lak' => 'Đắk Lắk',
                'dak-nong' => 'Đắk Nông',
                'kon-tum' => 'Kon Tum',
            ],
            'type' => 'XSMT'
        ];

        public const mn = [
            'list' => [
                'tphcm' => 'TPHCM',
                'dong-nai' => 'Đồng Nai',
                'binh-duong' => 'Bình Dương',
                'can-tho' => 'Cần Thơ',
                'an-giang' => 'An Giang',
                'vung-tau' => 'Vũng Tàu',
                'bac-lieu' => 'Bạc Liêu',
                'ben-tre' => 'Bến Tre',
                'binh-phuoc' => 'Bình Phước',
                'binh-thuan' => 'Bình Thuận',
                'ca-mau' => 'Cà Mau',
                'dong-thap' => 'Đồng Tháp',
                'hau-giang' => 'Hậu Giang',
                'kien-giang' => 'Kiên Giang',
                'lam-dong' => 'Lâm Đồng',
                'long-an' => 'Long An',
                'soc-trang' => 'Sóc Trăng',
                'tay-ninh' => 'Tây Ninh',
                'tien-giang' => 'Tiền Giang',
                'tra-vinh' => 'Trà Vinh',
                'vinh-long' => 'Vĩnh Long',
            ],
            'type' => 'XSMN'
        ];

        public const LOTTERY_SCHEDULE = [
            2 => [
                // 'xo-so-mien-bac' => ['Hà Nội'],
                'xo-so-mien-trung' => ['Phú Yên', 'Huế'],
                'xo-so-mien-nam' => ['TPHCM', 'Đồng Tháp', 'Cà Mau'],
            ],

            3 => [
                // 'xo-so-mien-bac' => ['Quảng Ninh'],
                'xo-so-mien-trung' => ['Đắk Lắk', 'Quảng Nam'],
                'xo-so-mien-nam' => ['Bến Tre', 'Vũng Tàu', 'Bạc Liêu'],
            ],

            4 => [
                // 'xo-so-mien-bac' => ['Bắc Ninh'],
                'xo-so-mien-trung' => ['Đà Nẵng', 'Khánh Hòa'],
                'xo-so-mien-nam' => ['Đồng Nai', 'Cần Thơ', 'Sóc Trăng'],
            ],

            5 => [
                // 'xo-so-mien-bac' => ['Hà Nội'],
                'xo-so-mien-trung' => ['Bình Định', 'Quảng Trị', 'Quảng Bình'],
                'xo-so-mien-nam' => ['An Giang', 'Bình Thuận', 'Tây Ninh'],
            ],

            6 => [
                // 'xo-so-mien-bac' => ['Hải Phòng'],
                'xo-so-mien-trung' => ['Gia Lai', 'Ninh Thuận'],
                'xo-so-mien-nam' => ['Bình Dương', 'Trà Vinh', 'Vĩnh Long'],
            ],

            7 => [
                // 'xo-so-mien-bac' => ['Nam Định'],
                'xo-so-mien-trung' => ['Đà Nẵng', 'Quảng Ngãi'],
                'xo-so-mien-nam' => ['TPHCM', 'Long An', 'Hậu Giang', 'Bình Phước'],
            ],

            0 => [
                // 'xo-so-mien-bac' => ['Thái Bình'],
                'xo-so-mien-trung' => ['Khánh Hòa', 'Kon Tum', 'Huế'],
                'xo-so-mien-nam' => ['Tiền Giang', 'Kiên Giang', 'Đà Lạt'],
            ],
        ];

        public const LIVE_DESCRIPTION = [
            'XSMB' => ['time' => '18:15', 'des' => '<p> Đến giờ quay thưởng <b>trực tiếp XSMB</b>, hệ thống sẽ cập nhật nhanh chóng kết quả <b>xổ số miền Bắc</b> ngay khi hội đồng bắt đầu quay. Toàn bộ các giải thưởng được hiển thị theo thời gian thực, đảm bảo <b>kết quả XSMB</b> chính xác và liên tục. </p><p> Theo dõi <b>xổ số miền Bắc trực tiếp</b> vào lúc 18h15 mỗi ngày để không bỏ lỡ bất kỳ con số nào. </p><p>Chúc bạn may mắn!</p>'],
            'XSMT' => ['time' => '17:15', 'des' => '<p> Khi đến thời điểm quay <b>trực tiếp XSMT</b>, kết quả <b>xổ số miền Trung</b> sẽ được cập nhật ngay lập tức từ trường quay. Dữ liệu hiển thị theo từng giải, giúp bạn theo dõi <b>kết quả XSMT</b> nhanh và chính xác nhất. </p><p> Xem <b>xổ số miền Trung trực tiếp</b> mỗi ngày lúc 17h15 để cập nhật kết quả mới nhất. </p><p>Chúc bạn gặp nhiều may mắn!</p>'],
            'XSMN' => ['time' => '16:15', 'des' => '<p> Đến khung giờ quay <b>trực tiếp XSMN</b>, chúng tôi sẽ liên tục cập nhật kết quả <b>xổ số miền Nam</b> ngay khi quá trình quay bắt đầu. Các giải thưởng được hiển thị theo thời gian thực, đảm bảo <b>kết quả XSMN</b> đầy đủ và chính xác. </p><p> Theo dõi <b>xổ số miền Nam trực tiếp</b> vào lúc 16h15 hàng ngày để không bỏ lỡ kết quả mới nhất. </p><p>Chúc bạn thật nhiều may mắn!</p>'],
        ];
    }
}

if (!class_exists('settingKey')) {
    final class settingKey
    {
        public const H1_HOME = 'h1_home';
        public const CODE_HEADER = 'code_header';
        public const CODE_BODY = 'code_body';
        public const CODE_FOOTER = 'code_footer';
        public const EMAIL = 'email';
        public const FACEBOOK = 'facebook';
        public const PHONE = 'phone';
        public const CONTENT_HOME = 'content_home';
        public const CONTENT_HOME_TITLE = 'content_home_title';
        public const TOP_LINK = 'top_link';
        public const FOOTER_LINK = 'footer_link';
        public const HOME_LINK_P1 = 'home_link_p1';
        public const HOME_LINK_P2 = 'home_link_p2';
        public const HOME_LINK_P3 = 'home_link_p3';
        public const HOME_LINK_P4 = 'home_link_p4';
        public const HOME_LINK_P5 = 'home_link_p5';
        public const SITEBAR_RIGHT = 'sitebar_right';
        public const SITEBAR_RIGHT_BOTTOM = 'sitebar_right_bottom';
        public const SITEBAR_LEFT = 'sitebar_left';
        public const SITEBAR_LEFT_BOTTOM = 'sitebar_left_bottom';
        public const CATE_LIST = 'cate_list';
        public const DETAIL_POST = 'detail';
        public const TELEGRAM = 'telegram';
        public const GOOGLE_INDEX = 'google_index';

        public const SCHEDULE_REWARD = 'schedule_reward';
        public const SCHEDULE_REWARD_H1 = 'schedule_reward_h1';
        public const SCHEDULE_REWARD_TITLE_SEO = 'schedule_reward_titleSeo';
        public const SCHEDULE_REWARD_DES_SEO = 'schedule_reward_metaDes';
        public const SCHEDULE_REWARD_KEY_SEO = 'schedule_reward_metaKey';

        public const TRY_SPIN_MB_CONTENT = 'spin_mb_content';
        public const TRY_SPIN_MB_H1 = 'spin_mb_h1';
        public const TRY_SPIN_MB_TITLE_SEO = 'spin_mb_titleSeo';
        public const TRY_SPIN_MB_DES_SEO = 'spin_mb_metaDes';
        public const TRY_SPIN_MB_KEY_SEO = 'spin_mb_metaKey';

        public const TRY_SPIN_MT_CONTENT = 'spin_mt_content';
        public const TRY_SPIN_MT_H1 = 'spin_mt_h1';
        public const TRY_SPIN_MT_TITLE_SEO = 'spin_mt_titleSeo';
        public const TRY_SPIN_MT_DES_SEO = 'spin_mt_metaDes';
        public const TRY_SPIN_MT_KEY_SEO = 'spin_mt_metaKey';

        public const TRY_SPIN_MN_CONTENT = 'spin_mn_content';
        public const TRY_SPIN_MN_H1 = 'spin_mn_h1';
        public const TRY_SPIN_MN_TITLE_SEO = 'spin_mn_titleSeo';
        public const TRY_SPIN_MN_DES_SEO = 'spin_mn_metaDes';
        public const TRY_SPIN_MN_KEY_SEO = 'spin_mn_metaKey';

        public const TRY_SPIN_VIETLOTT_CONTENT = 'spin_vietlott_content';
        public const TRY_SPIN_VIETLOTT_H1 = 'spin_vietlott_h1';
        public const TRY_SPIN_VIETLOTT_TITLE_SEO = 'spin_vietlott_titleSeo';
        public const TRY_SPIN_VIETLOTT_DES_SEO = 'spin_vietlott_metaDes';
        public const TRY_SPIN_VIETLOTT_KEY_SEO = 'spin_vietlott_metaKey';

        public const XOMO_CONTENT = 'xomo_content';
        public const XOMO_H1 = 'xomo_h1';
        public const XOMO_TITLE_SEO = 'xomo_titleSeo';
        public const XOMO_DES_SEO = 'xomo_metaDes';
        public const XOMO_KEY_SEO = 'xomo_metaKey';
    }
}

