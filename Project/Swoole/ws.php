<?php
/**
 * Swool chat server
 *
 * @author ercom
 */

$ws = new Swoole\WebSocket\Server('0.0.0.0', 8081);

$ws->set([
    'worker_num' => 2,
]);

$ws->on('message', function ($ws, $frame) {
    $current_fd = $frame->fd;
    $data = $frame->data;
    foreach ($ws->connections as $fd) {
        $ws->push($fd, "Clinet-{$current_fd}: " . $data);
    }
});

$ws->start();
