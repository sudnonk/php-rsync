<?php
    /**
     * Created by IntelliJ IDEA.
     * User: sudnonk
     */

    use sudnonk\Rsync\Rsync;

    require_once "./vendor/autoload.php";

    try {
        $rsync = new Rsync();
        $rsync->options()->sets('auvz');
        $rsync->set_from('/root/test', true);
        $rsync->set_to("/root/test2", "root", "localhost");
        $rsync->options()->setDryRun();
        $rsync->run();

    } catch (Exception $e) {
        echo $e->getMessage();
    }