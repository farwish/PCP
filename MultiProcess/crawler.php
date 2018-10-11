<?php

/**
 * 注意：
 *
 * 本版本存在一处错误，给 $pids 的赋值应该在主进程当中;
 * 否则在主进程中 $pids 总是为空，因为它们的内存空间本身是独立的。
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
        $pids[$id] = $id;
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
        sleep(2);
        break;
    }
}

while ( count($pids) ) {
    if (($id = pcntl_wait($status, WUNTRACED)) > 0) {
        unset($pids[$id]);
    }
}

echo "Done\n";
