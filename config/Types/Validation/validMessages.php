<?php

if (!class_exists('validMessages')) {
    final class validMessages
    {
        public const REQUIRED = '入力してください。';
        public const REQUIRED_SELECT = '選択してください。';

        public const EMAIL = 'テキスト入力出来る。';
        public const EMAIL_REQUIRED = 'メールアドレスは必ず入力してください。';
        public const EMAIL_UNIQUE = 'メールはすでに使用されています。';

        public const PASSWORD_MIN6 = '6以上の数字を指定してください。';
        public const PASSWORD_REQUIRED = 'パスワードは必ず入力してください。';
    }
}
