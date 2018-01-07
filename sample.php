<?php
/**
 * Created by IntelliJ IDEA.
 * User: sudnonk
 */

require_once "rsync.php";

try {
    $rsync = new rsync();
    $rsync->set_option('auvz');
    $rsync->from_dir_itself('~/test');
    $rsync->to("~/test2");
    $rsync->dry_run();

}catch (Exception $e){
    echo $e->getMessage();
}