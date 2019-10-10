<?php


    namespace sudnonk\Rsync\Option;

    class RsyncOptionDirectory {
        /**
         * @const array OPTIONS [[長い,短い],[長い=>短い],...] ないやつはnull
         * @see   https://www.atmarkit.co.jp/ait/articles/1702/02/news031.html
         *        todo: 引数を取るオプションに対応する（set_optionに第二引数作ったらできそう）
         */
        public const OPTIONS = [
            ["long" => "verbose", "short" => "v", "accept" => false],
            ["long" => "quiet", "short" => "q", "accept" => false],
            ["long" => "dry-run", "short" => "n", "accept" => false],
            ["long" => "stats", "short" => null, "accept" => false],
            ["long" => "list-only", "short" => null, "accept" => false],
            ["long" => "archive", "short" => "a", "accept" => false],
            //--no-**
            ["long" => "ignore-times", "short" => "I", "accept" => false],
            ["long" => "size-only", "short" => null, "accept" => false],
            ["long" => "checksum", "short" => "c", "accept" => false],
            ["long" => "daemon", "short" => null, "accept" => false],
            ["long" => "recursive", "short" => "r", "accept" => false],
            ["long" => "links", "short" => "l", "accept" => false],
            ["long" => "perms", "short" => "p", "accept" => false],
            ["long" => "times", "short" => "t", "accept" => false],
            ["long" => "group", "short" => "g", "accept" => false],
            ["long" => "owner", "short" => "o", "accept" => false],
            ["long" => null, "short" => "D", "accept" => false],
            ["long" => "devices", "short" => null, "accept" => false],
            ["long" => "specials", "short" => null, "accept" => false],
            ["long" => "hard-links", "short" => "H", "accept" => false],
            ["long" => "acls", "short" => "A", "accept" => false],
            ["long" => "xattrs", "short" => "X", "accept" => false],
            ["long" => "relative", "short" => "R", "accept" => false],
            ["long" => "dirs", "short" => "d", "accept" => false],
            ["long" => "update", "short" => "u", "accept" => false],
            ["long" => "inplace", "short" => null, "accept" => false],
            ["long" => "append", "short" => null, "accept" => false],
            ["long" => "backup", "short" => "b", "accept" => false],
            ["long" => "backup-dir", "short" => null, "accept" => true],
            ["long" => "suffix", "short" => null, "accept" => true],
            ["long" => "existing", "short" => null, "accept" => false],
            ["long" => "ignore-existing", "short" => null, "accept" => false],
            ["long" => "remove-source-files", "short" => null, "accept" => false],
            ["long" => "delete", "short" => null, "accept" => false],
            ["long" => "delete-before", "short" => null, "accept" => false],
            ["long" => "delete-during", "short" => null, "accept" => false],
            ["long" => "del", "short" => null, "accept" => false],
            ["long" => "delete-delay", "short" => null, "accept" => false],
            ["long" => "delete-after", "short" => null, "accept" => false],
            ["long" => "delete-excluded", "short" => null, "accept" => false],
            ["long" => "delete-excluded", "short" => null, "accept" => false],
            ["long" => "ignore-errors", "short" => null, "accept" => false],
            ["long" => "force", "short" => null, "accept" => false],
            ["long" => "max-delete", "short" => null, "accept" => true],
            ["long" => "max-size", "short" => null, "accept" => true],
            ["long" => "min-size", "short" => null, "accept" => true],
            ["long" => "exclude", "short" => null, "accept" => true],
            ["long" => "exclude-from", "short" => null, "accept" => true],
            ["long" => "include", "short" => null, "accept" => true],
            ["long" => "include-from", "short" => null, "accept" => true],
            ["long" => "files-from", "short" => null, "accept" => true],
            ["long" => "from0", "short" => "0", "accept" => false],
            ["long" => "copy-links", "short" => "L", "accept" => false],
            ["long" => "safe-links", "short" => null, "accept" => false],
            ["long" => "copy-unsafe-links", "short" => null, "accept" => false],
            ["long" => "copy-dirlinks", "short" => "k", "accept" => false],
            ["long" => "keep-dirlinks", "short" => "K", "accept" => false],
            ["long" => "executability", "short" => "E", "accept" => false],
            ["long" => "chmod", "short" => null, "accept" => true],
            ["long" => "omit-dir-times", "short" => "O", "accept" => false],
            ["long" => "temp-dir", "short" => null, "accept" => true],
            ["long" => "compress", "short" => "z", "accept" => false],
            ["long" => "comress-level", "short" => null, "accept" => true],
            ["long" => "skip-compress", "short" => null, "accept" => true],
            ["long" => "port", "short" => null, "accept" => true],
            ["long" => "sockopts", "short" => null, "accept" => true],
            ["long" => "8-bit-output", "short" => "8", "accept" => false],
            ["long" => "human-readable", "short" => "h", "accept" => false],
            ["long" => "progress", "short" => null, "accept" => false],
            ["long" => null, "short" => "P", "accept" => false],
            ["long" => "partial", "short" => null, "accept" => false],
            ["long" => "partial-dir", "short" => null, "accept" => true],
            ["long" => "ipv4", "short" => "4", "accept" => false],
            ["long" => "ipv6", "short" => "6", "accept" => false],
            ["long" => "timeout", "short" => null, "accept" => true],
            ["long" => "iconv", "short" => null, "accept" => true],
            ["long" => null, "short" => "e", "accept" => true],
        ];

        /**
         * @const array CONFLICT 同時に指定できないオプション
         *        todo: これの検知はrsyncコマンドに任せるべきかも
         */
        public const CONFLICT = [
            "a" => ["H", "A", "X"],
        ];

        /**
         * @const array DUPLICATE 同じ意味のオプション
         * todo: これの対処はrsyncコマンドに任せるべきかも
         */
        public const DUPLICATE = [
            "a" => ["r", "l", "p", "t", "g", "o", "D"],
            "D" => ["devices", "specials"],
            "P" => ["partial", "progress"]
        ];

        /**
         * そのオプションのキーを返す
         *
         * @param string $option
         * @param bool   $is_short どちらかわかってるならもう片方を検索しない
         * @param bool   $is_long
         * @return int|false 見つからなかったらfalse、あったらそれに対応した番号
         */
        public static function get_index(string $option, bool $is_short = false, bool $is_long = false): ?int {
            switch (true) {
                case $is_short:
                    return array_search($option, self::get_shorts(), true);
                case $is_long:
                    return array_search($option, self::get_longs(), true);
                default:
                    $v = array_search($option, self::get_shorts(), true);
                    if ($v === false) {
                        $v = array_search($option, self::get_longs(), true);
                    }

                    return $v;
            }


        }

        /**
         * 短い形式のやつに対応する長い形式のやつを返す
         *
         * @param string $short
         * @return string|null
         */
        public static function get_long(string $short): ?string {
            $index = self::get_index($short, true, false);
            if ($index === false) {
                return null;
            } else {
                return self::OPTIONS[$index]["long"];
            }
        }

        /**
         * 長い形式のやつに対応する短い形式のやつを返す
         *
         * @param string $long
         * @return string|null
         */
        public static function get_short(string $long): ?string {
            $index = self::get_index($long, false, true);
            if ($index === false) {
                return null;
            } else {
                return self::OPTIONS[$index]["short"];
            }
        }

        /**
         * 長い形式のやつだけ取得する。nullも含まれる
         *
         * @return string[]
         */
        public static function get_longs(): array {
            return array_column(self::OPTIONS, "long");
        }

        /**
         * 短い形式のやつだけ取得する。nullも含まれる
         *
         * @return string|null[]
         */
        public static function get_shorts(): array {
            return array_column(self::OPTIONS, "short");
        }

        /**
         * そのオプションが存在するかを調べる
         *
         * @param string $option
         * @return bool 存在したらtrue
         */
        public static function is_exists(string $option): bool {
            //両方falseでfalse、片方trueでtrue
            return self::is_short($option) || self::is_long($option);
        }

        /**
         * その$optionが長い形式かを調べる
         *
         * @param string $option
         * @return bool 長かったらtrue
         */
        public static function is_long(string $option): bool {
            return (in_array($option, self::get_longs(), true));
        }

        /**
         * その$optionが短い形式のオプションかを調べる
         *
         * @param string $option
         * @return bool 短かったらtrue
         */
        public static function is_short(string $option): bool {
            return (in_array($option, self::get_shorts(), true));
        }

        /**
         * その$optionが引数を取るか
         *
         * @param string $option
         * @return bool
         */
        public static function is_accept_param(string $option): bool {
            $index = self::get_index($option);
            return self::OPTIONS[$index]["accept"];
        }
    }