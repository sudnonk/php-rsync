<?php


    namespace sudnonk\Rsync;


    abstract class Target {
        /** @var string $path 末尾にスラッシュが有る絶対パス */
        protected $path;
        /** @var UserHost|null $userHost */
        protected $userHost;

        /**
         * @return string
         */
        public function get(): string {
            if ($this->userHost !== null) {
                return sprintf("%s:%s", $this->userHost->getUserHost(), $this->path);
            } else {
                return $this->path;
            }
        }
    }