<?php

    namespace sudnonk\Rsync\Test;

    use sudnonk\Rsync\Target\UserHost;
    use PHPUnit\Framework\TestCase;

    class UserHostTest extends TestCase {
        public function testGet() {
            $userHost = new UserHost("root", "localhost");
            self::assertSame("root@localhost", $userHost->getUserHost());
        }
    }
