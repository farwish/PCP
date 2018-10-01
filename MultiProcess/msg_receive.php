<?php

$key = 12345678;

if (msg_queue_exists($key)) {
    echo "Queue exists\n";
}

$resource = msg_get_queue($key, 0666);

if ( false === $resource ) {
    die("Get queue failed\n");
}

$array = msg_stat_queue($resource);

print_r($array);

// 修改系统变量需要root权限，此处演示函数用法
if (! msg_set_queue($resource, [
    'msg_qbytes' => 99999,
])) {
    echo "Set queue failed\n";
}

// 第七个参数 $flags 决定了 receive 的行为，默认是阻塞的，MSG_IPC_NOWAIT 是非阻塞
if (! msg_receive($resource, 1, $msgtype, 100, $message, true, MSG_IPC_NOWAIT)) {
    echo "Receive failed\n";
} else {
    echo "Message: {$message}\n";
}

echo "Done\n";
