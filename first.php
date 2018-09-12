<?php

echo "Master process id = " . posix_getpid() . PHP_EOL;

$pid = pcntl_fork();

switch ($pid) {
case -1:
    die('Create failed');
    break;
case 0:
    // Child
    echo "Child process id = " . posix_getpid() . PHP_EOL;
    sleep(2);
    echo "I will exit\n";
    break;
default:
    // Parent

    if ($exit_id = pcntl_waitpid($pid, $status, WUNTRACED)) {
        echo "Child({$exit_id}) exited\n";
    }
    echo "Parent process id = " . posix_getpid() . PHP_EOL;
    break;
}

