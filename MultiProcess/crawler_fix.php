<?php

/**
 * 注意：
 *
 * 本文件为 crawler.php 的修复版;
 * 区别在于 $pids 的赋值必须放在主进程中操作，否则主进程中的 $pids 永远是空。
 */

include __DIR__ . '/vendor/autoload.php';

use Goutte\Client;

$client = new Client();

$links = [
    'http://www.nipic.com/topic/show_27192_1.html',
    'http://www.nipic.com/topic/show_27054_1.html',
    'http://www.nipic.com/topic/show_27085_1.html',
];

$pids = [];

foreach ($links as $url) {
    $pid = pcntl_fork();
    switch ($pid) {
    case -1:
        die("Fork failed\n");
    case 0:

        $id = posix_getpid();
        $data = [];

        $crawler = $client->request('GET', $url);
        $crawler->filter('.search-works-thumb')->each(function($node) use ($client, $id, &$data) {
            $url = $node->link()->getUri();

            $crawler = $client->request('GET', $url);
            $crawler->filter('#J_worksImg')->each(function($node) use ($id, &$data) {
                $src = $node->image()->getUri();

                $data[$id][] = $src;
                //echo $src . PHP_EOL;
            });
        });

        print_r($data);

        exit;

        break;
    default:
        $pids[$pid] = $pid;
        break;
    }
}

while ( count($pids) ) {
    if (($id = pcntl_wait($status, WUNTRACED)) > 0) {
        unset($pids[$id]);
    }
}

echo "Done\n";
