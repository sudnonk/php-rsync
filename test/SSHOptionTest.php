<?php
    namespace sudnonk\Rsync\Test;

    use sudnonk\Rsync\Option\SSHOption;
    use PHPUnit\Framework\TestCase;

    class SSHOptionTest extends TestCase {
        /**
         * @test
         */
        public function インスタンス化できる() {
            $option = new SSHOption("p");
            self::assertInstanceOf(SSHOption::class, $option);
            $option = new SSHOption("i");
            self::assertInstanceOf(SSHOption::class, $option);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function インスタンス化できない() {
            $this->expectException(\InvalidArgumentException::class);
            new SSHOption("po");
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function getter() {
            $option = new SSHOption("p");
            self::assertSame("p", $option->get());
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function 引数が付く() {
            $option = new SSHOption("p", "1022");
            self::assertTrue($option->hasParam());
            self::assertSame("1022", $option->getParam());
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function 引数を付けられない() {
            $this->expectException(\InvalidArgumentException::class);
            new SSHOption("C", "po");
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function combineできる() {
            $option[] = new SSHOption("p", "1022");
            $option[] = new SSHOption("C");

            $expected = "'ssh -p 1022 -C'";
            self::assertSame($expected, SSHOption::combine($option));
        }
    }
