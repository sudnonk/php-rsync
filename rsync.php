<?php
/**
 * Created by IntelliJ IDEA.
 * User: sudnonk
 */

class rsync
{

    /**
     * @var string 実際に実行するコマンド
     */
    private $command;

    public function __construct()
    {
        self::check_rsync();
        $this->command = "rsync ";
    }

    /**
     * rsyncコマンドの存在を確認する
     *
     * @throws RuntimeException rsyncコマンドが無かった場合
     */
    private static function check_rsync()
    {
        if (self::get_return_var("type rsync") !== 0) {
            throw new RuntimeException("rsync command not found.");
        }
    }

    /**
     * コマンドを実行し、終了ステータスを返す
     *
     * @param string $command 実行するコマンド
     * @return int 終了ステータス
     */
    private static function get_return_var(string $command): int
    {
        $output = array();
        $return_var = 1;

        exec($command, $output, $return_var);
        return $return_var;
    }


}
