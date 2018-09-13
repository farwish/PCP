<?php

echo "Master process id = " . posix_getpid() . PHP_EOL;

$childs = [];

function fork() {
    global $childs;

    $pid = pcntl_fork();

    switch ($pid) {
    case -1:
        die('Create failed');
        break;
    case 0:
        // Child
        echo "Child process id = " . posix_getpid() . PHP_EOL;

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

$count = 3;

for ($i = 0; $i < $count; $i++) {
    fork();
}

while ( count($childs) ) {
    if ( ($exit_id = pcntl_wait($status)) > 0 ) {
        echo "Child({$exit_id}) exited.\n";
        echo "中断子进程的信号值是 " . pcntl_wtermsig($status) . PHP_EOL;
        unset($childs[$exit_id]);
    }

    if ( count($childs) < 3 ) {
        fork();
    }
}

echo "Done\n";
