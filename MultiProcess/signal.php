<?php

// 守护进程化
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

// Fork一个子进程
function fork() {
    global $childs;

    $pid = pcntl_fork();

    switch ($pid) {
    case -1:
        die('Create failed');
        break;
    case 0:
        // Child

        pcntl_signal(SIGTERM, SIG_IGN, false);
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


$cmd = ( $_SERVER['argv'][1] ?? '' );

switch ($cmd) {
case 'start':

    // 启动

    if (file_exists('/tmp/master_pid')) {
        die("Already running\n");
    }

    break;
case 'reload':

    // 重载子进程

    $master_pid = file_get_contents('/tmp/master_pid');

    exec("ps --ppid {$master_pid} | awk '/[0-9]/{print $1}' | xargs", $output, $status);

    if ($status == 0) {
        $childs = explode(' ', current($output));
        foreach ($childs as $id) {
            posix_kill($id, SIGKILL);
        }
    }

    exit;
    break;
case 'stop':

    // 停止所有

    $master_pid = file_get_contents('/tmp/master_pid');

    exec("ps --ppid {$master_pid} | awk '/[0-9]/{print $1}' | xargs", $output, $status);

    posix_kill($master_pid, SIGKILL);

    if ($status == 0) {
        $childs = explode(' ', current($output));
        foreach ($childs as $id) {
            posix_kill($id, SIGKILL);
        }
    }

    while (true) {
        if (! posix_kill($master_pid, 0)) {
            @unlink('/tmp/master_pid');
            break;
        }
    }

    exit;
    break;

default:
    die("Please enter command\n");
    break;
}

// 守护进程
daemon();

$childs = [];

$count = 3;

// 保存主进程pid
$master_pid = posix_getpid();
file_put_contents('/tmp/master_pid', $master_pid);

// Fork子进程
for ($i = 0; $i < $count; $i++) {
    fork();
}

// 监控子进程
while ( count($childs) ) {
    if ( ($exit_id = pcntl_wait($status)) > 0 ) {
        unset($childs[$exit_id]);
    }

    if ( count($childs) < 3 ) {
        fork();
    }
}

