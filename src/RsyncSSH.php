<?php

    class RsyncSSH extends Rsync {
        /* @var bool $use_ssh sshを使うか */
        private $use_ssh = true;
        /* @var string $ssh_option sshのオプション */
        private $ssh_option = "";
        /* @var string $from_userhost コピー元のuser@host */
        private $from_userhost = "";
        /* @var string $to_userhost コピー先のuser@host */
        private $to_userhost = "";

        /* setter */
        /**
         * コピー元のuser@hostを設定する。
         *
         * @param string $user
         * @param string $host
         */
        public function from_userhost(string $user, string $host) {
            $this->from_userhost = $user . "@" . $host . ":";
        }

        /**
         * コピー先のuser@hostを設定する
         *
         * @param string $user
         * @param string $host
         */
        public function to_userhost(string $user, string $host) {
            $this->to_userhost = $user . "@" . $host . ":";
        }

        /**
         * sshを使用する
         */
        public function enable_ssh() {
            $this->use_ssh = true;
        }

        /**
         * sshを使用しない
         */
        public function disable_ssh() {
            $this->use_ssh = false;
        }

        /**
         * 証明書のパスを明示する
         *
         * @param string $path_of_cert
         */
        public function set_cert(string $path_of_cert) {
            $this->ssh_option .= " -i " . $path_of_cert;
        }

        /**
         * 標準以外のポートを使う
         *
         * @param int $port
         */
        public function set_port(int $port) {
            $this->ssh_option .= "-p " . $port;
        }

        /**
         * rsyncコマンドを実行する
         *
         * @throws RuntimeException
         */
        public function run() {
            if ($this->use_ssh) {
                $this->options .= " -e 'ssh $this->ssh_option'";
                $this->from = $this->from_userhost . $this->from;
                $this->to = $this->to_userhost . $this->to;
            }

            parent::run();
        }
    }