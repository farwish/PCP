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
if (! socket_bind($resource, '192.168.157.130', 8848)) {
    die("Bind failed\n");
}

// 监听
if (! socket_listen($resource, 2)) {
    die("Listen failed\n");
}

$read = $write = $except = [];
$client = [$resource];

while (true) {

    $read = $client;

    socket_select($read, $write, $except, null);

    foreach ($read as $fd) {
        if ($fd == $resource) {
            $conn = socket_accept($resource);
            $client[] = $conn;
            echo "New client connected\n";
        } else {
            echo @socket_read($fd, 100);
        }
    }
}
