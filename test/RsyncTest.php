<?php

    namespace sudnonk\Rsync\Test;

    use PHPUnit\Framework\TestCase;
    use sudnonk\Rsync\Exec\DummyExecCommand;
    use sudnonk\Rsync\Rsync;

    class RsyncTest extends TestCase {
        public $exec;

        public function setUp(): void {
            $this->exec = new DummyExecCommand(0, true);
            parent::setUp();
        }

        /**
         * @test
         */
        public function インスタンス化できる() {
            $rsync = new Rsync(true, $this->exec);
            self::assertInstanceOf(Rsync::class, $rsync); //インスタンス化できる
        }

        /**
         * @test
         */
        public function コマンドが見つからないとき() {
            $exec2 = new DummyExecCommand(1, false);
            $this->expectException(\RuntimeException::class);
            new Rsync(true, $exec2);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function BadMethodCallExceptionになる() {
            $rsync = new Rsync(true, $this->exec);

            $this->expectException(\BadMethodCallException::class);
            $rsync->build_command();
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function コマンドが組み立てられる() {
            $rsync = new Rsync(true, $this->exec);
            $rsync->set_from(__FILE__, false);
            $rsync->set_to(__DIR__);

            $command = $rsync->build_command();
            $expect = "rsync " . __FILE__ . " " . __DIR__;
            self::assertSame($expect, $command);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function dry_runになる() {
            $rsync = new Rsync(true, $this->exec);
            $rsync->set_from(__FILE__, false);
            $rsync->set_to(__DIR__);
            $rsync->options()->setDryRun();

            $command = $rsync->build_command();
            $expect = "rsync --dry-run " . __FILE__ . " " . __DIR__;
            self::assertSame($expect, $command);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function オプションが付く() {
            $rsync = new Rsync(true, $this->exec);
            $rsync->set_from(__FILE__, false);
            $rsync->set_to(__DIR__);
            $rsync->options()->sets("a", "c", "v", "stats");

            $command = $rsync->build_command();
            $expect = "rsync -a -c -v --stats " . __FILE__ . " " . __DIR__;
            self::assertSame($expect, $command);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function 引数付きのオブションが付く() {
            $rsync = new Rsync(true, $this->exec);
            $rsync->set_from(__FILE__, false);
            $rsync->set_to(__DIR__);
            $rsync->options()->set("e", "ssh");

            $command = $rsync->build_command();
            $expect = "rsync -e ssh " . __FILE__ . " " . __DIR__;
            self::assertSame($expect, $command);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function deleteが付く() {
            $rsync = new Rsync(true, $this->exec);
            $rsync->set_from(__FILE__, false);
            $rsync->set_to(__DIR__);
            $rsync->options()->setDelete();

            $command = $rsync->build_command();
            $expect = "rsync --delete " . __FILE__ . " " . __DIR__;
            self::assertSame($expect, $command);
        }

        /**
         * @test
         * @depends dry_runになる
         * @depends オプションが付く
         * @depends deleteが付く
         */
        public function オブションが全部同時に付く() {
            $rsync = new Rsync(true, $this->exec);
            $rsync->set_from(__FILE__, false);
            $rsync->set_to(__DIR__);
            $rsync->options()->setDryRun();
            $rsync->options()->setDelete();
            $rsync->options()->sets("a", "c", "v", "stats");
            $rsync->ssh_options()->setPorts(1022);
            $rsync->ssh_options()->setCerts(__FILE__);

            $command = $rsync->build_command();
            $expect = "rsync --dry-run --delete -a -c -v --stats -e 'ssh -p 1022 -i " . __FILE__ . "' " . __FILE__ . " " . __DIR__;
            self::assertSame($expect, $command);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function ディレクトリのとき() {
            $rsync = new Rsync(true, $this->exec);
            $rsync->set_from(__DIR__, true);
            $rsync->set_to(__DIR__);

            $command = $rsync->build_command();
            $expect = "rsync " . __DIR__ . DIRECTORY_SEPARATOR . " " . __DIR__;
            self::assertSame($expect, $command);
        }
    }
