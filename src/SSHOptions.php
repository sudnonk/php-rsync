<?php


    namespace sudnonk\Rsync;


    class SSHOptions {
        /** @var SSHOption[] $options */
        private $options;

        public function __construct() {
            $this->options = [];
        }

        /**
         * @param string      $option
         * @param string|null $param
         */
        public function set(string $option, string $param = null) {
            $this->options[] = new SSHOption($option, $param);
        }

        /**
         * @param string ...$options
         */
        public function sets(string ...$options) {
            foreach ($options as $option) {
                $this->set($option);
            }
        }

        public function setCerts(string $cert_path) {
            $this->set("i", $cert_path);
        }

        public function setPorts(int $port) {
            $this->set("p", (string)$port);
        }

        /**
         * @return SSHOption[]
         */
        public function get(): array {
            return $this->options;
        }

        public function count():int{
            return count($this->options);
        }
    }