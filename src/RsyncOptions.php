<?php


    namespace sudnonk\Rsync;


    class RsyncOptions {
        /** @var RsyncOption[] $options */
        private $options;

        public function __construct() {
            $this->options = [];
        }

        /**
         * @param string      $option
         * @param string|null $param
         */
        public function set(string $option, string $param = null) {
            $this->options[] = new RsyncOption($option, $param);
        }

        /**
         * @param string ...$options
         */
        public function sets(string ...$options) {
            foreach ($options as $option) {
                $this->set($option);
            }
        }

        public function setDryRun() {
            $this->set("n");
        }

        public function setDelete() {
            $this->set("delete");
        }

        /**
         * @return RsyncOption[]
         */
        public function get(): array {
            return $this->options;
        }

        public function count(): int {
            return count($this->options);
        }
    }