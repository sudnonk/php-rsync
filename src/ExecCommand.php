<?php

    namespace sudnonk\Rsync;

    class ExecCommand implements ExecCommandInterface {
        /**
         * ExecCommand constructor.
         *
         * DI用に動的にしたい
         */
        public function __construct() {
        }

        /**
         * @inheritDoc
         */
        public function execute(string $command, bool $is_cli = false): int {
            $return_var = 1;

            if ($is_cli) { //system()は出力を標準出力に表示する
                system($command, $return_var);
            } else {//execは表示しない
                $output = [];
                exec($command, $output, $return_var);
            }
            return $return_var;
        }

        /**
         * @inheritDoc
         */
        public function isRsyncEnabled(): bool {
            return (self::execute("type rsync") === 0);
        }
    }