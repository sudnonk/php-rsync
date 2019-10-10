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
         * @return string
         */
        public function get_option(): string {
            return RsyncOption::combine($this->options);
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
         */
        public function build_command(): string {
            return "rsync " . $this->get_option() . $this->from . " " . $this->to;
        }

        /**
         * rsyncコマンドを実行する
         *
         * @throws \RuntimeException rsyncコマンドに失敗した場合
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