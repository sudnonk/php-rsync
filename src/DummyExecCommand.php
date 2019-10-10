<?php

    namespace sudnonk\Rsync;

    class DummyExecCommand implements ExecCommandInterface {
        public $execute_return;
        public $is_RsyncEnabled_return;

        /**
         * DummyExecCommand constructor.
         * @param int  $execute_return         execute()が返す値
         * @param bool $is_RsyncEnabled_return isRsyncEnabled()が返す値
         */
        public function __construct(int $execute_return, bool $is_RsyncEnabled_return) {
            $this->execute_return = $execute_return;
            $this->is_RsyncEnabled_return = $is_RsyncEnabled_return;
        }

        public function execute(string $command): int {
            return $this->execute_return;
        }

        public function isRsyncEnabled(): bool {
            return $this->is_RsyncEnabled_return;
        }
    }