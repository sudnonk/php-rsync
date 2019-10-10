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
        public function execute(string $command): int {
            $return_var = 1;

            system($command, $return_var);
            return $return_var;
        }

        /**
         * @inheritDoc
         */
        public function isRsyncEnabled(): bool {
            return (self::execute("type rsync") === 0);
        }
    }