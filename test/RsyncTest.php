<?php

    namespace sudnonk\Rsync\Test;

    use PHPUnit\Framework\TestCase;
    use sudnonk\Rsync\DummyExecCommand;
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
        public function コマンドが組み立てられる() {
            $rsync = new Rsync(true, $this->exec);
            $rsync->from_file(__FILE__);
            $rsync->to(__DIR__);

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
            $rsync->from_file(__FILE__);
            $rsync->to(__DIR__);
            $rsync->enable_dry_run();

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
            $rsync->from_file(__FILE__);
            $rsync->to(__DIR__);
            $rsync->set_option("a", "c", "v", "stats");

            $command = $rsync->build_command();
            $expect = "rsync --stats -acv " . __FILE__ . " " . __DIR__;
            self::assertSame($expect, $command);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function deleteが付く() {
            $rsync = new Rsync(true, $this->exec);
            $rsync->from_file(__FILE__);
            $rsync->to(__DIR__);
            $rsync->enable_delete();

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
            $rsync->from_file(__FILE__);
            $rsync->to(__DIR__);
            $rsync->enable_dry_run();
            $rsync->enable_delete();
            $rsync->set_option("a", "c", "v", "stats");

            $command = $rsync->build_command();
            $expect = "rsync --dry-run --delete --stats -acv " . __FILE__ . " " . __DIR__;
            self::assertSame($expect, $command);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function ディレクトリのとき() {
            $rsync = new Rsync(true, $this->exec);
            $rsync->from_dir_itself(__DIR__);
            $rsync->to(__DIR__);

            $command = $rsync->build_command();
            $expect = "rsync " . __DIR__ . DIRECTORY_SEPARATOR . " " . __DIR__;
            self::assertSame($expect, $command);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function 変なオプション(){
            $rsync = new Rsync(true, $this->exec);
            $this->expectException(\InvalidArgumentException::class);
            $rsync->set_option("popopo");
        }
    }