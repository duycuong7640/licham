<?php

if (!class_exists('dataResponse')) {
    final class dataResponse
    {
        public const STATUS_200 = '200';
        public const STATUS_500 = '500';
        public const STATUS_404 = '404';
        public const STATUS_401 = '401';
        public const STATUS_421 = '421';
        public const STATUS_422 = '422';
        public const STATUS_429 = '429';
    }
}
