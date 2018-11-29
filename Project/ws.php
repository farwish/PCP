<?php
/**
 * 实时统计系统信息
 *
 * 1.运行前先安装 `composer require workerman/workerman`
 * 2.运行 `php ws.php start`
 *
 * @author ercom
 */

include __DIR__ . '/vendor/autoload.php';

use Workerman\Worker;
use Workerman\Lib\Timer;

$ws = new Worker('websocket://0.0.0.0:8081');

$ws->count = 1;
$ws->reusePort = true;

$ws->onConnect = function ($connection) {
    Timer::add(1, function () use ($connection) {
        exec('uptime', $load);
        exec('free -h', $memory);
        exec('df -h', $disk);

        $data = json_encode([
            'load' => $load,
            'memory' => $memory,
            'disk' => $disk,
        ]);

        $connection->send($data);
    });
};

Worker::runAll();
