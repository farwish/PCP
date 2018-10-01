<?php

// 等同执行 create - bind - listen
$resource = stream_socket_server('tcp://192.168.0.53:8848');

if (false === $resource) {
    die("Create failed\n");
}

while (true) {
    // 接受连接
    $conn = stream_socket_accept($resource);

    // 数据传输
    $msg = 'Welcome - ' . rand() . PHP_EOL;

    fwrite($conn, $msg);

    fclose($conn);
}
