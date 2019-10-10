<?php
    /**
     * Created by IntelliJ IDEA.
     * User: sudnonk
     */

    namespace sudnonk\Rsync;

    class Rsync {
        /* @var Target $from コピー元 */
        protected $from = null;
        /* @var Target $to コピー先 */
        protected $to = null;

        /** @var ExecCommandInterface $execCommand コマンドを実行するクラス */
        protected $execCommand = null;
        protected $is_cli = false;

        /** @var RsyncOption[] $options 選択されたオプション */
        protected $options = [];
        /* @var SSHOption[] $ssh_option sshのオプション */
        private $ssh_options = [];


        /**
         * Rsync constructor.
         * @param Target                    $from
         * @param Target                    $to
         * @param UserHost|null             $userHost
         * @param bool                      $is_cli
         * @param ExecCommandInterface|null $execCommand
         */
        public function __construct(Target $from, Target $to, UserHost $userHost = null, bool $is_cli = false, ExecCommandInterface $execCommand = null) {
            clearstatcache();

            $this->from = $from;
            $this->to = $to;
            $this->is_cli = $is_cli;
            $this->execCommand = $execCommand ?? new ExecCommand();
            if (!$this->execCommand->isRsyncEnabled()) {
                throw new \RuntimeException("Cannot execute rsync command. Check exists.");
            }
        }

        /**
         * オプションを一つ設定する
         *
         * @param string      $option
         * @param string|null $param そのオプションの引数
         */
        public function set_option(string $option, string $param = null) {
            $this->options[] = new RsyncOption($option, $param);
        }

        /**
         * オプションを複数設定する
         *
         * @param string ...$options
         */
        public function set_options(string ...$options) {
            foreach ($options as $option) {
                $this->options[] = new RsyncOption($option);
            }
        }

        /**
         * 証明書のパスを明示する
         *
         * @param string $path_of_cert
         */
        public function set_cert(string $path_of_cert) {
            $this->set_ssh_option("i", $path_of_cert);
        }

        /**
         * 標準以外のポートを使う
         *
         * @param int $port
         */
        public function set_port(int $port) {
            $this->set_ssh_option("p", (string)$port);
        }

        /**
         * SSHのオプションを設定する
         *
         * @param string      $option
         * @param string|null $param
         */
        public function set_ssh_option(string $option, string $param = null) {
            $this->ssh_options[] = new SSHOption($option, $param);
        }

        /**
         * --deleteオプションを有効にする
         */
        public function enable_delete() {
            $this->options[] = new RsyncOption("delete");
        }

        /**
         * rsyncコマンドをdry-runで実行する
         */
        public function enable_dry_run() {
            $this->options[] = new RsyncOption("dry-run");
        }

        /**
         * オプションを取得する
         *
         * @return string
         */
        public function get_option(): string {
            if (count($this->ssh_options) > 0) {
                $this->set_option("e", SSHOption::combine($this->ssh_options));
            }
            if (count($this->options) > 0) {
                return RsyncOption::combine($this->options);
            } else {
                return "";
            }
        }

        /**
         * コマンドを組み立てる
         *
         * @return string
         * @throws \BadMethodCallException
         */
        public function build_command(): string {
            return "rsync " . $this->get_option() . $this->from->get() . " " . $this->to->get();
        }

        /**
         * rsyncコマンドを実行する
         *
         * @throws \RuntimeException rsyncコマンドに失敗した場合
         * @throws \BadMethodCallException
         */
        public function run() {
            $command = $this->build_command();

            if ($this->execCommand->execute($command, $this->is_cli) !== 0) {
                throw new \RuntimeException("failed to exec rsync.\n");
            } else {
                echo "rsync success.";
            }
        }
    }