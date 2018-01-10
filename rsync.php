<?php
/**
 * Created by IntelliJ IDEA.
 * User: sudnonk
 */

class rsync {

    /* @var string $options オプション。aとかuとか */
    protected $options = "";
    /* @var bool $is_delete --deleteオプションを付けるか */
    protected $is_delete = false;
    /* @var string $from コピー元 */
    protected $from = "";
    /* @var string $to コピー先 */
    protected $to = "";

    /**
     * rsync constructor.
     * @throws RuntimeException
     */
    public function __construct() {
        clearstatcache();
        self::check_rsync();
    }

    /**
     * rsyncコマンドの存在を確認する
     *
     * @throws RuntimeException rsyncコマンドが無かった場合
     */
    private static function check_rsync() {
        if (self::execute("type rsync") !== 0) {
            throw new RuntimeException("rsync command not found.");
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

        if (!file_exists($from)) {
            throw new RuntimeException("from_dir does not exists.");
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
            throw new RuntimeException("from_dir does not exists.");
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
                throw new RuntimeException("failed to mkdir.");
            } else {
                echo "made to_dir.\n";
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

    /**
     * rsyncコマンドを実行する
     *
     * @throws RuntimeException rsyncコマンドに失敗した場合
     */
    public function run() {
        $delete = $this->is_delete ? " --delete" : "";
        $command = "rsync " . $this->options . $delete . " " . $this->from . " " . $this->to;

        var_dump($command);
        if (self::execute($command) !== 0) {
            throw new RuntimeException("failed to exec rsync.");
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

    /**
     * コマンドを実行し、実行結果と終了ステータスを返す
     *
     * @param string $command 実行するコマンド
     * @return int 終了ステータス
     */
    private static function execute(string $command): int {
        $return_var = 1;

        system($command, $return_var);
        return $return_var;
    }

    private function debug() {
        var_dump($this->options);
        var_dump($this->is_delete);
        var_dump($this->from);
        var_dump($this->to);
    }
}

class rsync_ssh extends rsync {
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