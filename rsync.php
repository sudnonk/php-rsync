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
        self::check_rsync();

    }

    /**
     * rsyncコマンドの存在を確認する
     *
     * @throws RuntimeException rsyncコマンドが無かった場合
     */
    private static function check_rsync()
    {
        if (self::execute("type rsync")['return_var'] !== 0) {
            throw new RuntimeException("rsync command not found.");
        }
    }

    /**
     * ディレクトリ自体をコピーする
     * パスの末尾に強制で/を付ける
     *
     * @param string $from
     */
    public function from_dir_itself(string $from)
    {
        $from = rtrim($from, "/");
        $this->from = $from . "/";
    }

    /**
     * ディレクトリの中身またはファイル自体をコピーする
     * パスの末尾の/を強制で取る
     *
     * @param string $from
     */
    public function from_file(string $from)
    {
        $from = rtrim($from, "/");
        $this->from = $from;
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

    public function run()
    {
        $delete = $this->is_delete ? "--delete" : "";
        $command = "rsync -" . $this->options . $delete . " " . $this->from . " " . $this->to;

        $exec = self::execute($command);
        if($exec['return_var'] !== 0){
            throw new RuntimeException("failed to exec rsync.");
        }
    }

    public function dry_run()
    {
        $this->set_option("n");
        $this->run();
    }

    /**
     * コマンドを実行し、実行結果と終了ステータスを返す
     *
     * @param string $command 実行するコマンド
     * @return array('output'=>string[],'return_var'=>int) array('output'=>実行結果,'return_var'=>終了ステータス)
     */
    private static function execute(string $command): array
    {
        $output = array();
        $return_var = 1;

        exec($command, $output, $return_var);
        return array('output' => $output, 'return_var' => $return_var);
    }
}
