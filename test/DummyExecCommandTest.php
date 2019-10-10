<?php

    namespace sudnonk\Rsync\Test;

    use PHPUnit\Framework\TestCase;
    use sudnonk\Rsync\Exec\DummyExecCommand;

    class DummyExecCommandTest extends TestCase {
        /**
         * @test
         */
        public function ちゃんと指定された値を返す() {
            $exec = new DummyExecCommand(0, true);
            self::assertSame(0, $exec->execute(""));
            self::assertSame(true, $exec->isRsyncEnabled());
            $exec = new DummyExecCommand(1, false);
            self::assertSame(1, $exec->execute(""));
            self::assertSame(false, $exec->isRsyncEnabled());
        }
    }
