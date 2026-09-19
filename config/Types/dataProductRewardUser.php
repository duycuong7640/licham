<?php

if (!class_exists('dataProductRewardUser')) {
    final class dataProductRewardUser
    {
        public const IS_EXCHANGE = 0;
        public const IS_EXCHANGE_TO_POINT = 1;
        public const IS_EXCHANGE_TO_DP = 2;
        public const IS_EXCHANGE_TO_MONEY = 3;
        public const IS_EXCHANGE_TO_COIN = 4;

        public const STATUS = 1;
        public const STATUS_FAIL = 0;

    }
}
