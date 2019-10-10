<?php
    namespace sudnonk\Rsync\Test;

    use sudnonk\Rsync\Option\RsyncOption;
    use PHPUnit\Framework\TestCase;

    class RsyncOptionTest extends TestCase {
        /**
         * @test
         */
        public function インスタンス化できる() {
            $option = new RsyncOption("a");
            self::assertInstanceOf(RsyncOption::class, $option);
            $option = new RsyncOption("delete");
            self::assertInstanceOf(RsyncOption::class, $option);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function インスタンス化できない() {
            $this->expectException(\InvalidArgumentException::class);
            new RsyncOption("po");
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function getter() {
            $option = new RsyncOption("a");
            self::assertSame("a", $option->get());
            $option = new RsyncOption("delete");
            self::assertSame("delete", $option->get());
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function 短いのが短いと判定される() {
            $option = new RsyncOption("a");
            self::assertTrue($option->isShort());
            self::assertFalse($option->isLong());
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function 長いのが長いと判定される() {
            $option = new RsyncOption("delete");
            self::assertTrue($option->isLong());
            self::assertFalse($option->isShort());
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function 長くできる() {
            $option = new RsyncOption("a");
            self::assertSame("archive", $option->asLong());
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function 短くできる() {
            $option = new RsyncOption("ignore-times");
            self::assertSame("I", $option->asShort());
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function 引数が付く() {
            $option = new RsyncOption("e", "ssh");
            self::assertTrue($option->hasParam());
            self::assertSame("ssh", $option->getParam());
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function 引数を付けられない() {
            $this->expectException(\InvalidArgumentException::class);
            new RsyncOption("delete", "po");
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function combineできる() {
            $option[] = new RsyncOption("e", "'ssh'");
            $option[] = new RsyncOption("delete");
            $option[] = new RsyncOption("a");

            $expected = "-e 'ssh' --delete -a ";
            self::assertSame($expected, RsyncOption::combine($option));
        }
    }
