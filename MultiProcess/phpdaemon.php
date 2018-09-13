<?php

function daemon() {
    // 1-1
    $pid = pcntl_fork();

    switch ($pid) {
    case -1:
        die('Create failed');
        break;
    case 0:
        // Child

        // 2.
        if ( ($sid = posix_setsid()) <= 0 ) {
            die("Set sid failed.\n");
        }

        // 3.
        if (chdir('/') === false) {
            die("Change dir failed.\n");
        }

        // 4.
        umask(0);

        // 5.
        fclose(STDIN);
        fclose(STDOUT);
        fclose(STDERR);

        break;
    default:
        // Parent

        // 1-2
        exit;
        break;
    }
}

function fork() {
    global $childs;

    $pid = pcntl_fork();

    switch ($pid) {
    case -1:
        die('Create failed');
        break;
    case 0:
        // Child
        while (true) {
            sleep(5);
        }

        break;
    default:
        // Parent

        $childs[$pid] = $pid;
        break;
    }
}

daemon();

$childs = [];

$count = 3;

for ($i = 0; $i < $count; $i++) {
    fork();
}

while ( count($childs) ) {
    if ( ($exit_id = pcntl_wait($status)) > 0 ) {
        unset($childs[$exit_id]);
    }

    if ( count($childs) < 3 ) {
        fork();
    }
}

