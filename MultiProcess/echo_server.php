<?php

// 创建
$resource = socket_create(AF_INET, SOL_SOCKET, SOL_TCP);

if (false == $resource) {
    die("Create failed\n");
}

// 设置
if (! socket_set_option($resource, SOL_SOCKET, SO_REUSEPORT, 1)) {
    die("Set option failed\n");
}

// 地址绑定
if (! socket_bind($resource, '192.168.0.53', 8848)) {
    die("Bind failed\n");
}

// 监听
if (! socket_listen($resource, 2)) {
    die("Listen failed\n");
}

while (true) {

    // 接受连接
    $conn = socket_accept($resource);

    if (false === $conn) {
        die("Accept failed\n");
    }

    // 数据传输
    $msg = "Welcome - " . rand() . PHP_EOL;
    socket_write($conn, $msg, strlen($msg));

    // 关闭
    socket_close($conn);
}
