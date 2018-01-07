<?php
/**
 * Created by IntelliJ IDEA.
 * User: sudnonk
 */

class rsync
{

    /* @var string $options オプション。aとかuとか */
    private $options = "";
    /* @var bool $is_delete --deleteオプションを付けるか */
    private $is_delete = false;
    /* @var string $from コピー元 */
    private $from = "";
    /* @var string $to コピー先 */
    private $to = "";

    /**
     * rsync constructor.
     * @throws RuntimeException
     */
    public function __construct()
    {
        clearstatcache();
        self::check_rsync();
    }

    /**
     * rsyncコマンドの存在を確認する
     *
     * @throws RuntimeException rsyncコマンドが無かった場合
     */
    private static function check_rsync()
    {
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
    public function from_dir_itself(string $from)
    {
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
    public function from_file(string $from)
    {
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
    public function to(string $to)
    {
        if (!file_exists($to) && !is_dir($to)) {
            if (!mkdir($to)) {
                throw new RuntimeException("failed to mkdir.");
            } else {
                echo "made to_dir.";
            }
        }
        $this->to = $to;
    }

    /**
     * オプションを設定する
     *
     * @param string $option
     */
    public function set_option(string $option)
    {
        $this->options .= $option;
    }

    /**
     * --deleteオプションを有効にする
     */
    public function enable_delete()
    {
        $this->is_delete = true;
    }

    /**
     * rsyncコマンドを実行する
     *
     * @throws RuntimeException rsyncコマンドに失敗した場合
     */
    public function run()
    {
        $delete = $this->is_delete ? "--delete" : "";
        $command = "rsync -" . $this->options . $delete . " " . $this->from . " " . $this->to;

        if (self::execute($command) !== 0) {
            throw new RuntimeException("failed to exec rsync.");
        }
    }

    /**
     * rsyncコマンドをdry-runで実行する
     *
     * @throws RuntimeException
     */
    public function dry_run()
    {
        $this->set_option("n");
        $this->run();
    }

    /**
     * コマンドを実行し、実行結果と終了ステータスを返す
     *
     * @param string $command 実行するコマンド
     * @return int 終了ステータス
     */
    private static function execute(string $command): int
    {
        $return_var = 1;

        system($command, $return_var);
        return $return_var;
    }

    private function debug()
    {
        var_dump($this->options);
        var_dump($this->is_delete);
        var_dump($this->from);
        var_dump($this->to);
    }
}
