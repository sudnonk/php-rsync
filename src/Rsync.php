<?php
    /**
     * Created by IntelliJ IDEA.
     * User: sudnonk
     */

    namespace sudnonk\Rsync;

    class Rsync {
        /* @var string $from コピー元 */
        protected $from = null;
        /* @var string $to コピー先 */
        protected $to = null;
        /** @var ExecCommandInterface $execCommand コマンドを実行するクラス */
        protected $execCommand = null;
        protected $is_cli = false;

        /** @var RsyncOption[] $options 選択されたオプション */
        protected $options = [];
        /* @var SSHOption[] $ssh_option sshのオプション */
        private $ssh_options = [];
        /* @var string $from_userhost コピー元のuser@host */
        private $from_userhost = null;
        /* @var string $to_userhost コピー先のuser@host */
        private $to_userhost = null;


        /**
         * rsync constructor.
         *
         * @param bool                      $is_cli CLIからの実行で、出力を標準出力に出したかったらtrue
         * @param ExecCommandInterface|null $execCommand
         * @throws \RuntimeException
         */
        public function __construct(bool $is_cli = false, ExecCommandInterface $execCommand = null) {
            clearstatcache();

            $this->is_cli = $is_cli;
            $this->execCommand = $execCommand ?? new ExecCommand();
            if (!$this->execCommand->isRsyncEnabled()) {
                throw new \RuntimeException("Cannot execute rsync command. Check exists.");
            }
        }

        /**
         * ディレクトリ自体をコピーする
         * パスの末尾に強制で/を付けると、宛先フォルダの中にこのフォルダの中身だけがコピーされる
         *
         * @param string $from
         * @throws \RuntimeException ディレクトリが見つからなかった場合
         */
        public function from_dir_itself(string $from) {
            $from = rtrim($from, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            if (!is_dir($from)) {
                throw new \RuntimeException("from_dir does not exist.\n");
            } else {
                $this->from = $from;
            }
        }

        /**
         * ディレクトリの中身またはファイル自体をコピーする
         * パスの末尾の/を強制で取ると、宛先フォルダの中にこのフォルダ自体がコピーされる
         *
         * @param string $from
         * @throws \RuntimeException ディレクトリが見つからなかった場合
         */
        public function from_file(string $from) {
            $from = rtrim($from, "/");

            if (!file_exists($from)) {
                throw new \RuntimeException("from_file does not exist.\n");
            } else {
                $this->from = $from;
            }
        }

        /**
         * 宛先を指定する
         * 宛先フォルダの末尾スラッシュは影響しない
         *
         * @param string $to
         * @throws \RuntimeException 宛先フォルダの生成に失敗した場合
         */
        public function to(string $to) {
            if (!file_exists($to) && !is_dir($to)) {
                if (!mkdir($to)) {
                    throw new \RuntimeException("failed to mkdir.\n");
                } elseif ($this->is_cli) {
                    echo "create target dir.";
                }
            }
            $this->to = $to;
        }

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
         * @return string
         * @throws \BadMethodCallException
         */
        public function get_from(): string {
            if ($this->from === null) {
                throw new \BadMethodCallException("set from first.");
            }
            if ($this->from_userhost !== null) {
                return $this->from_userhost . $this->from;
            } else {
                return $this->from;
            }
        }

        /**
         * @return string
         * @throws \BadMethodCallException
         */
        public function get_to(): string {
            if ($this->to === null) {
                throw new \BadMethodCallException("set to first.");
            }
            if ($this->to_userhost !== null) {
                return $this->to_userhost . $this->to;
            } else {
                return $this->to;
            }
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
         * コマンドを組み立てる
         *
         * @return string
         * @throws \BadMethodCallException
         */
        public function build_command(): string {
            return "rsync " . $this->get_option() . $this->get_from() . " " . $this->get_to();
        }

        /**
         * rsyncコマンドを実行する
         *
         * @throws \RuntimeException rsyncコマンドに失敗した場合
         * @throws \BadMethodCallException
         */
        public function run() {
            $command = $this->build_command();

            if ($this->execCommand->execute($command) !== 0) {
                throw new \RuntimeException("failed to exec rsync.\n");
            } else {
                echo "rsync success.";
            }
        }
    }