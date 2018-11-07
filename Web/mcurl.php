<?php

$urls = [
    'http://www.farwish.com/search?q=1',
    'http://www.farwish.com/search?q=2',
    'http://www.farwish.com/search?q=3',
    'http://www.farwish.com/search?q=4',
];

$mh = curl_multi_init();

$handles = [];
foreach ($urls as $url) {
    $ch = curl_init();
    $handles[] = $ch;
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_HEADER => 0,
    ]);
    curl_multi_add_handle($mh, $ch);
}

$still_running = false;

do {
    curl_multi_exec($mh, $still_running);
} while ($still_running);

foreach ($handles as $handle) {
    curl_multi_remove_handle($mh, $handle);
}

curl_multi_close($mh);
