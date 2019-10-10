<?php


    namespace sudnonk\Rsync;


    class RsyncOption {
        /** @var string $option */
        private $option;
        private $is_short = false;
        private $is_long = false;

        /**
         * RsyncOption constructor.
         * @param string $option
         */
        public function __construct(string $option) {
            if (!RsyncOptions::is_exists($option)) {
                throw new \InvalidArgumentException("option does not exists.");
            } else {
                $this->option = $option;
            }
            if (RsyncOptions::is_short($option)) {
                $this->is_short = true;
            } else {
                $this->is_long = true;
            }
        }

        /**
         * @return string
         */
        public function get(): string {
            return $this->option;
        }

        /**
         * @return bool
         */
        public function isShort(): bool {
            return $this->is_short;
        }

        /**
         * @return bool
         */
        public function isLong(): bool {
            return $this->is_long;
        }

        /**
         * @return string|null 短い形式にして返す
         */
        public function asShort(): ?string {
            if ($this->isShort()) {
                return $this->get();
            } else {
                return RsyncOptions::get_short($this->get());
            }
        }

        /**
         * @return string|null 長い形式にして返す
         */
        public function asLong(): ?string {
            if ($this->isLong()) {
                return $this->get();
            } else {
                return RsyncOptions::get_long($this->get());
            }
        }

        /**
         * @param RsyncOption[] $longs
         * @return string
         */
        public static function combine_long(array $longs): string {
            $str = "";
            foreach ($longs as $long) {
                $str .= sprintf("--%s ", $long->get());
            }

            return trim($str);
        }

        /**
         * @param RsyncOption[] $shorts
         * @return string
         */
        public static function combine_short(array $shorts): string {
            $str = "-";
            foreach ($shorts as $short) {
                $str .= $short->get();
            }
            return $str;
        }

        /**
         * オプションを全部くっつける
         *
         * @param RsyncOption[] $options
         * @return string
         */
        public static function combine(array $options): string {
            $contains_long = false;
            $contains_short = false;

            /** @var RsyncOption[] $longs 長いやつ */
            $longs = [];
            /** @var RsyncOption[] $shorts 短いやつ */
            $shorts = [];

            foreach ($options as $option) {
                if ($option->isLong()) {
                    $longs[] = $option;
                    $contains_long = true;
                } else {
                    $shorts[] = $option;
                    $contains_short = true;
                }
            }

            switch (true) {
                case $contains_long && $contains_short:
                    return sprintf("%s %s ", self::combine_long($longs), self::combine_short($shorts));
                case $contains_long && !$contains_short:
                    return sprintf("%s ", self::combine_long($longs));
                case !$contains_long && $contains_short:
                    return sprintf("%s ", self::combine_short($shorts));
                default:
                    return "";

            }
        }
    }