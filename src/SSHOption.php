<?php


    namespace sudnonk\Rsync;


    class SSHOption {
        private $option;
        private $param;
        private $has_param = false;

        public function __construct(string $option, string $param = null) {
            if (!SSHOptionDirectory::is_exists($option)) {
                throw new \InvalidArgumentException("option does not exists.");
            } else {
                $this->option = $option;
            }
            if ($param !== null) {
                if (!SSHOptionDirectory::is_accept_param($option)) {
                    throw new \InvalidArgumentException("this option does not accept parameter.");
                } else {
                    $this->param = $param;
                    $this->has_param = true;
                }
            }
        }

        public function get(): string {
            return $this->option;
        }

        public function getParam(): ?string {
            return $this->param;
        }

        public function hasParam(): bool {
            return $this->has_param;
        }

        /**
         * @param SSHOption[] $options
         * @return string
         */
        public static function combine(array $options): string {
            $str = "ssh ";

            foreach ($options as $option) {
                $str .= sprintf("-%s ", $option->get());
                if ($option->hasParam()) {
                    $str .= sprintf("%s ", $option->getParam());
                }
            }

            return sprintf("'%s'", trim($str));
        }
    }