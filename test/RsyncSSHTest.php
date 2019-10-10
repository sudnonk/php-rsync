<?php


    use sudnonk\Rsync\DummyExecCommand;
    use sudnonk\Rsync\RsyncSSH;
    use PHPUnit\Framework\TestCase;

    class RsyncSSHTest extends TestCase {
        public $exec;

        public function setUp(): void {
            $this->exec = new DummyExecCommand(0, true);
            parent::setUp();
        }

        /**
         * @test
         */
        public function インスタンス化できる() {
            $rsync = new RsyncSSH(true, $this->exec);
            self::assertInstanceOf(RsyncSSH::class, $rsync); //インスタンス化できる
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function from_user_hostが付く() {
            $rsync = new RsyncSSH(true, $this->exec);
            $rsync->from_file(__FILE__);
            $rsync->to(__DIR__);
            $rsync->from_userhost("root", "localhost");

            $command = $rsync->build_command();
            $expect = "rsync -e 'ssh' root@localhost:" . __FILE__ . " " . __DIR__;
            self::assertSame($expect, $command);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function to_user_hostが付く() {
            $rsync = new RsyncSSH(true, $this->exec);
            $rsync->from_file(__FILE__);
            $rsync->to(__DIR__);
            $rsync->to_userhost("root", "localhost");

            $command = $rsync->build_command();
            $expect = "rsync -e 'ssh' " . __FILE__ . " root@localhost:" . __DIR__;
            self::assertSame($expect, $command);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function 両方付く() {
            $rsync = new RsyncSSH(true, $this->exec);
            $rsync->from_file(__FILE__);
            $rsync->to(__DIR__);
            $rsync->from_userhost("root", "localhost");
            $rsync->to_userhost("root", "localhost");

            $command = $rsync->build_command();
            $expect = "rsync -e 'ssh' root@localhost:" . __FILE__ . " root@localhost:" . __DIR__;
            self::assertSame($expect, $command);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function 証明書のパスが付く() {
            $rsync = new RsyncSSH(true, $this->exec);
            $rsync->from_file(__FILE__);
            $rsync->to(__DIR__);
            $rsync->set_cert(__FILE__);

            $command = $rsync->build_command();
            $expect = "rsync -e 'ssh -i " . __FILE__ . "' " . __FILE__ . " " . __DIR__;
            self::assertSame($expect, $command);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function ポートが付く() {
            $rsync = new RsyncSSH(true, $this->exec);
            $rsync->from_file(__FILE__);
            $rsync->to(__DIR__);
            $rsync->set_port(10022);

            $command = $rsync->build_command();
            $expect = "rsync -e 'ssh -p 10022' " . __FILE__ . " " . __DIR__;
            self::assertSame($expect, $command);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function sshオプションが両方付く() {
            $rsync = new RsyncSSH(true, $this->exec);
            $rsync->from_file(__FILE__);
            $rsync->to(__DIR__);
            $rsync->set_port(10022);
            $rsync->set_cert(__FILE__);

            $command = $rsync->build_command();
            $expect = "rsync -e 'ssh -p 10022 -i " . __FILE__ . "' " . __FILE__ . " " . __DIR__;
            self::assertSame($expect, $command);
        }

        /**
         * @test
         * @depends インスタンス化できる
         */
        public function 他のオプションも付く() {
            $rsync = new RsyncSSH(true, $this->exec);
            $rsync->from_file(__FILE__);
            $rsync->to(__DIR__);
            $rsync->enable_delete();
            $rsync->set_port(10022);
            $rsync->set_cert(__FILE__);

            $command = $rsync->build_command();
            $expect = "rsync --delete -e 'ssh -p 10022 -i " . __FILE__ . "' " . __FILE__ . " " . __DIR__;
            self::assertSame($expect, $command);
        }

    }
