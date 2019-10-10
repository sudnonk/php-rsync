<?php
    /**
     * Created by IntelliJ IDEA.
     * User: sudnonk
     */

    class Rsync {

        /* @var string $options オプション。aとかuとか */
        protected $options = null;
        /* @var bool $is_delete --deleteオプションを付けるか */
        protected $is_delete = false;
        /* @var string $from コピー元 */
        protected $from = null;
        /* @var string $to コピー先 */
        protected $to = null;
        /** @var ExecCommandInterface $execCommand コマンドを実行するクラス */
        protected $execCommand = null;
        protected $is_cli = false;


        /**
         * rsync constructor.
         *
         * @param bool $is_cli CLIからの実行で、出力を標準出力に出したかったらtrue
         * @param ExecCommandInterface|null $execCommand
         * @throws RuntimeException
         */
        public function __construct(bool $is_cli = false,ExecCommandInterface $execCommand = null) {
            clearstatcache();

            $this->is_cli = $is_cli;
            $this->execCommand = $execCommand ?? new ExecCommand();
            if (!$this->execCommand->isRsyncEnabled()) {
                throw new RuntimeException("Cannot execute rsync command. Check exists.");
            }
        }

        /**
         * ディレクトリ自体をコピーする
         * パスの末尾に強制で/を付ける
         *
         * @param string $from
         * @throws RuntimeException ディレクトリが見つからなかった場合
         */
        public function from_dir_itself(string $from) {
            $from = rtrim($from, "/") . "/";

            if (!is_dir($from)) {
                throw new RuntimeException("from_dir does not exist.\n");
            } else {
                $this->from = $from;
            }
        }

        /**
         * ディレクトリの中身またはファイル自体をコピーする
         * パスの末尾の/を強制で取る
         *
         * @param string $from
         * @throws RuntimeException ディレクトリが見つからなかった場合
         */
        public function from_file(string $from) {
            $from = rtrim($from, "/");

            if (!file_exists($from)) {
                throw new RuntimeException("from_file does not exist.\n");
            } else {
                $this->from = $from;
            }
        }

        /**
         * 宛先を指定する
         *
         * @param string $to
         * @throws RuntimeException 宛先フォルダの生成に失敗した場合
         */
        public function to(string $to) {
            if (!file_exists($to) && !is_dir($to)) {
                if (!mkdir($to)) {
                    throw new RuntimeException("failed to mkdir.\n");
                }elseif($this->is_cli){
                    echo "create target dir.";
                }
            }
            $this->to = $to;
        }

        /**
         * オプションを設定する
         *
         * @param string $option
         */
        public function set_option(string $option) {
            $this->options .= $option;
        }

        /**
         * --deleteオプションを有効にする
         */
        public function enable_delete() {
            $this->is_delete = true;
        }

        private function build_command(): string {
            $delete = $this->is_delete ? " --delete" : "";
            return "rsync " . $this->options . $delete . " " . $this->from . " " . $this->to;
        }

        /**
         * rsyncコマンドを実行する
         *
         * @throws RuntimeException rsyncコマンドに失敗した場合
         */
        public function run() {
            $command = $this->build_command();

            if ($this->execCommand->execute($command) !== 0) {
                throw new RuntimeException("failed to exec rsync.\n");
            }else{
                echo "rsync success.";
            }
        }

        /**
         * rsyncコマンドをdry-runで実行する
         *
         * @throws RuntimeException
         */
        public function dry_run() {
            $this->set_option("n");
            $this->run();
        }
    }