<?php


    namespace sudnonk\Rsync;

    class RsyncOptions {
        /**
         * @const array OPTIONS [[長い,短い],[長い=>短い],...] ないやつはnull
         * @see https://www.atmarkit.co.jp/ait/articles/1702/02/news031.html
         *        todo: 引数を取るオプションに対応する（set_optionに第二引数作ったらできそう）
         */
        public const OPTIONS = [
            ["long" => "verbose", "short" => "v"],
            ["long" => "quiet", "short" => "q"],
            ["long" => "dry-run", "short" => "n"],
            ["long" => "stats", "short" => null],
            ["long" => "list-only", "short" => null],
            ["long" => "archive", "short" => "a"],
            //--no-**
            ["long" => "ignore-times", "short" => "I"],
            ["long" => "size-only", "short" => null],
            ["long" => "checksum", "short" => "c"],
            ["long" => "daemon", "short" => null],
            ["long" => "recursive", "short" => "r"],
            ["long" => "links", "short" => "l"],
            ["long" => "perms", "short" => "p"],
            ["long" => "times", "short" => "t"],
            ["long" => "group", "short" => "g"],
            ["long" => "owner", "short" => "o"],
            ["long" => null, "short" => "D"],
            ["long" => "devices", "short" => null],
            ["long" => "specials", "short" => null],
            ["long" => "hard-links", "short" => "H"],
            ["long" => "acls", "short" => "A"],
            ["long" => "xattrs", "short" => "X"],
            ["long" => "relative", "short" => "R"],
            ["long" => "dirs", "short" => "d"],
            ["long" => "update", "short" => "u"],
            ["long" => "inplace", "short" => null],
            ["long" => "append", "short" => null],
            ["long" => "backup", "short" => "b"],
            //backup-dir,
            //suffix,
            ["long" => "existing", "short" => null],
            ["long" => "ignore-existing", "short" => null],
            ["long" => "remove-source-files", "short" => null],
            ["long" => "delete", "short" => null],
            ["long" => "delete-before", "short" => null],
            ["long" => "delete-during", "short" => null],
            ["long" => "del", "short" => null],
            ["long" => "delete-delay", "short" => null],
            ["long" => "delete-after", "short" => null],
            ["long" => "delete-excluded", "short" => null],
            ["long" => "delete-excluded", "short" => null],
            ["long" => "ignore-errors", "short" => null],
            ["long" => "force", "short" => null],
            //max-delete
            //max-size
            //min-size,
            //exclude,
            //exclude-from,
            //include,
            //include-from,
            //files-from
            ["long" => "from0", "short" => "0"],
            ["long" => "copy-links", "short" => "L"],
            ["long" => "safe-links", "short" => null],
            ["long" => "copy-unsafe-links", "short" => null],
            ["long" => "copy-dirlinks", "short" => "k"],
            ["long" => "keep-dirlinks", "short" => "K"],
            ["long" => "executability", "short" => "E"],
            //chmod
            ["long" => "omit-dir-times", "short" => "O"],
            //temp-dir,
            ["long" => "compress", "short" => "z"],
            //comress-level
            //skip-compress
            //port
            //sockopts,
            ["long" => "8-bit-output", "short" => "8"],
            ["long" => "human-readable", "short" => "h"],
            ["long" => "progress", "short" => null],
            ["long" => null, "short" => "P"],
            ["long" => "partial", "short" => null],
            //partial-dir,
            ["long" => "ipv4", "short" => "4"],
            ["long" => "ipv6", "short" => "6"],
            //timeout
            //iconv
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
    }