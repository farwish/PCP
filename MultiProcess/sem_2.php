<?php

$key = 123456;

$resource = sem_get($key);

if ( false === $resource ) {
    die("Get sem failed\n");
}

while (true) {
    // 获取信号量
    if (sem_acquire($resource)) {
        echo "Sem acquire success\n";
        echo "Doing something ...\n";
        break;
    }
}

sem_remove($resource);

echo "Done\n";
