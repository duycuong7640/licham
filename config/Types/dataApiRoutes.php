<?php

if (!class_exists('dataApiRoutes')) {
    final class dataApiRoutes
    {
        public const LOGIN = 'auth/login';
        public const REGISTER = 'auth/register';
        public const ME = 'auth/me';
        public const USER_UPDATE = 'auth/update';

        public const CONFIG = 'config';

        public const POSTS = 'posts';
        public const POST_BY_TAGS = 'posts/by-tag';
        public const POSTS_SEARCH = 'posts/search';
        public const POST_DETAIL = 'posts/:slug';

        public const COPE_DETAIL = 'copes/:day';
        public const COPE_WEEK = 'copes/from-week-day/:from/:to';
        public const COPE_MONTH = 'copes/from-month-day/:month/:year';
        public const COPE_YEAR = 'copes/from-year/:year';
        public const COPE_CALENDAR_MONTH = 'copes/calendar-month/:month/:year';
        public const COPE_HISTORICAL_EVENT = 'copes/historical-events';

        public const FORTUNE_TINH_DUYEN = 'fortune/tinh-duyen';
        public const FORTUNE_NAM_LAY_CHONG = 'fortune/nam-lay-chong';
        public const FORTUNE_NAM_LAY_VO = 'fortune/nam-lay-vo';
        public const FORTUNE_SINH_CON_HOP_TUOI = 'fortune/sinh-con-hop-tuoi';
        public const FORTUNE_SINH_CON_THEO_Y_MUON = 'fortune/sinh-con-theo-y-muon';
        public const FORTUNE_XEM_TUOI_XAY_NHA = 'fortune/xem-tuoi-xay-nha';
        public const FORTUNE_CAN_XUONG_TINH_SO = 'fortune/can-xuong-tinh-so';
        public const FORTUNE_TINH_TRUNG_TANG = 'fortune/tinh-trung-tang';
        public const FORTUNE_BOI_NGAY_SINH = 'fortune/boi-ngay-sinh';
        public const FORTUNE_THAN_SO_HOC = 'fortune/than-so-hoc';
        public const FORTUNE_LAS_SO_TU_VI = 'fortune/la-so-tu-vi';
        public const FORTUNE_NGAY_TOT_XAU = 'fortune/ngay-hom-nay-tot-xau';
        public const FORTUNE_12_CHD_HANG_NGAY = 'fortune/tu-vi-12-cung-hoang-dao';
        public const FORTUNE_XONG_DAT = 'fortune/xong-dat';
        public const FORTUNE_TUOI_VO_CHONG = 'fortune/tuoi-vo-chong';
        public const FORTUNE_TUOI_SINH_CON = 'fortune/tuoi-sinh-con';
        public const FORTUNE_TUOI_KET_HON = 'fortune/tuoi-ket-hon';
        public const FORTUNE_TUOI_HOP_NHAU = 'fortune/tuoi-hop-nhau';
        public const FORTUNE_TUOI_LAM_AN = 'fortune/tuoi-lam-an';
        public const FORTUNE_TUOI_LAM_NHA = 'fortune/tuoi-lam-nha';

        public const HASHTAGS = 'hashtags';
    }
}
