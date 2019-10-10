<?php


    namespace sudnonk\Rsync;


    class UserHost {
        private $user;
        private $host;

        public function __construct(string $user, string $host) {
            //todo: バリデーション

            $this->user = $user;
            $this->host = $host;
        }

        public function getUserHost(): string {
            return sprintf("%s@%s", $this->user, $this->host);
        }
    }