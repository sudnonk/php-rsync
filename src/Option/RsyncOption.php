<?php


    namespace sudnonk\Rsync\Option;


    class RsyncOption implements OptionInterface {
        /** @var string $option */
        private $option;
        /** @var string|null $param オプションの引数 */
        private $param = null;
        private $has_param = false;
        private $is_short = false;
        private $is_long = false;

        /**
         * RsyncOption constructor.
         * @param string $option
         */
        public function __construct(string $option, string $param = null) {
            if (!RsyncOptionDirectory::is_exists($option)) {
                throw new \InvalidArgumentException("option does not exists.");
            } else {
                $this->option = $option;
            }
            if ($param !== null) {
                if (!RsyncOptionDirectory::is_accept_param($option)) {
                    throw new \InvalidArgumentException("this option does not accept parameter.");
                } else {
                    $this->param = $param;
                    $this->has_param = true;
                }
            }

            if (RsyncOptionDirectory::is_short($option)) {
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
         * @return string|null
         */
        public function getParam(): ?string {
            return $this->param;
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

        public function hasParam(): bool {
            return $this->has_param;
        }

        /**
         * @return string|null 短い形式にして返す
         */
        public function asShort(): ?string {
            if ($this->isShort()) {
                return $this->get();
            } else {
                return RsyncOptionDirectory::get_short($this->get());
            }
        }

        /**
         * @return string|null 長い形式にして返す
         */
        public function asLong(): ?string {
            if ($this->isLong()) {
                return $this->get();
            } else {
                return RsyncOptionDirectory::get_long($this->get());
            }
        }

        /**
         * オプションを全部くっつける
         *
         * @param RsyncOption[] $options
         * @return string
         */
        public static function combine(array $options): string {
            $str = "";
            foreach ($options as $option) {
                if ($option->isLong()) {
                    $str .= sprintf("--%s ", $option->get());
                } else {
                    $str .= sprintf("-%s ", $option->get());
                }
                if ($option->hasParam()) {
                    $str .= sprintf("%s ", $option->getParam());
                }
            }

            return $str;
        }
    }