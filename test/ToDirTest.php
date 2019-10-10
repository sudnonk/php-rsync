<?php

    namespace sudnonk\Rsync\Test;

    use sudnonk\Rsync\Target\ToDir;
    use PHPUnit\Framework\TestCase;
    use sudnonk\Rsync\Target\UserHost;

    class ToDirTest extends TestCase {
        /**
         * @test
         */
        public function 末尾にスラッシュが無くなる() {
            $dir = new ToDir(__DIR__);
            $expected = __DIR__;
            self::assertSame($expected, $dir->get());
            $dir = new ToDir(__DIR__ . DIRECTORY_SEPARATOR);
            $expected = __DIR__;
            self::assertSame($expected, $dir->get());
        }

        /**
         * @test
         */
        public function ユーザとホストが付く() {
            $dir = new ToDir(__DIR__, new UserHost("root", "localhost"));
            $expected = "root@localhost:" . __DIR__;
            self::assertSame($expected, $dir->get());
        }
    }
