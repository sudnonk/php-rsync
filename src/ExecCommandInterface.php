<?php


    interface ExecCommandInterface {
        /**
         * コマンドを実行し、実行結果と終了ステータスを返す
         *
         * @param string $command 実行するコマンド
         * @return int 終了ステータス
         */
        public function execute(string $command): int;

        /**
         * @return bool Rsyncが使えたらtrue、使えなかったらfalse
         */
        public function isRsyncEnabled():bool;
    }