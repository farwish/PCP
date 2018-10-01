<?php

$key = 123456;

$resource = sem_get($key);

if ( false === $resource ) {
    die("Get sem failed\n");
}

// 获取信号量
if (sem_acquire($resource)) {
    echo "Sem acquire success\n";
    echo "Doing something ...\n";
    // 释放信号量, 使其它程序可以获取该信号量
    sem_release($resource);
    sleep(10);
}

echo "Done\n";
