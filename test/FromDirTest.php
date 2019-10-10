<?php

    namespace sudnonk\Rsync\Test;

    use sudnonk\Rsync\Target\FromDir;
    use PHPUnit\Framework\TestCase;
    use sudnonk\Rsync\Target\UserHost;

    class FromDirTest extends TestCase {
        /**
         * @test
         */
        public function 末尾にスラッシュが付く() {
            $dir = new FromDir(__DIR__);
            $expected = __DIR__ . DIRECTORY_SEPARATOR;
            self::assertSame($expected, $dir->get());
        }

        /**
         * @test
         */
        public function ユーザとホストが付く() {
            $dir = new FromDir(__DIR__, new UserHost("root", "localhost"));
            $expected = "root@localhost:" . __DIR__ . DIRECTORY_SEPARATOR;
            self::assertSame($expected, $dir->get());
        }
    }
