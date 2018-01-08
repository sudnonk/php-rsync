<?php
/**
 * Created by IntelliJ IDEA.
 * User: sudnonk
 */

require_once "rsync.php";

try {
    /* normal rsync */
    $rsync = new rsync();
    $rsync->set_option('-auvz');
    $rsync->from_dir_itself('/root/test');
    $rsync->to("/root/test2");
    $rsync->dry_run();

    /* rsync with ssh */
    $rsync_ssh = new rsync_ssh();
    $rsync_ssh->set_option('-auvz');
    $rsync_ssh->from_file('/root/test/test1.txt');
    $rsync_ssh->to("/root/test2");
    $rsync_ssh->from_userhost('root', 'example.com');
    $rsync_ssh->set_port(10022);
    $rsync_ssh->run();

} catch (Exception $e) {
    echo $e->getMessage();
}