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

        /** @var RsyncOptions $options 選択されたオプション */
        protected $options;
        /* @var SSHOptions $ssh_options sshのオプション */
        private $ssh_options;


        /**
         * Rsync constructor.
         * @param bool                      $is_cli
         * @param ExecCommandInterface|null $execCommand
         */
        public function __construct(bool $is_cli = false, ExecCommandInterface $execCommand = null) {
            clearstatcache();

            $this->is_cli = $is_cli;
            $this->execCommand = $execCommand ?? new ExecCommand();
            if (!$this->execCommand->isRsyncEnabled()) {
                throw new \RuntimeException("Cannot execute rsync command. Check exists.");
            }

            $this->options = new RsyncOptions();
            $this->ssh_options = new SSHOptions();
        }

        /**
         * @param string      $file_path
         * @param bool        $only_contents ディレクトリの中身だけをコピーするときはtrue、ディレクトリごとコピーしたりファイルのときはfalse
         * @param string|null $user
         * @param string|null $host
         */
        public function set_from(string $file_path, bool $only_contents, string $user = null, string $host = null) {
            if ($user !== null && $host !== null) {
                $userHost = new UserHost($user, $host);
            } else {
                $userHost = null;
            }

            if ($only_contents) {
                $this->from = new FromDir($file_path, $userHost);
            } else {
                $this->from = new FromFile($file_path, $userHost);
            }
        }

        /**
         * @param string      $to_dir
         * @param string|null $user
         * @param string|null $host
         */
        public function set_to(string $to_dir, string $user = null, string $host = null) {
            if ($user !== null && $host !== null) {
                $userHost = new UserHost($user, $host);
            } else {
                $userHost = null;
            }

            $this->to = new ToDir($to_dir, $userHost);
        }

        public function options(): RsyncOptions {
            return $this->options;
        }

        public function ssh_options(): SSHOptions {
            return $this->ssh_options;
        }

        /**
         * オプションを取得する
         *
         * @return string
         */
        public function get_option(): string {
            if ($this->ssh_options()->count() > 0) {
                $this->options()->set("e", SSHOption::combine($this->ssh_options()->get()));
            }
            if ($this->options()->count() > 0) {
                return RsyncOption::combine($this->options()->get());
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
                throw new \BadMethodCallException("call set_from first.");
            }

            return $this->from->get();
        }

        /**
         * @return string
         * @throws \BadMethodCallException
         */
        public function get_to(): string {
            if ($this->to === null) {
                throw new \BadMethodCallException("call set_to first.");
            }

            return $this->to->get();
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

            if ($this->execCommand->execute($command, $this->is_cli) !== 0) {
                throw new \RuntimeException("failed to exec rsync.\n");
            } else {
                echo "rsync success.";
            }
        }
    }