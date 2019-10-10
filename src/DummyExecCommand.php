<?php


    class DummyExecCommand implements ExecCommandInterface {
        public function execute(string $command): int {
            return 0;
        }

        public function isRsyncEnabled(): bool {
            return true;
        }
    }