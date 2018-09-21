<?php

$file = '/tmp/abc';

if (! file_exists($file)) {
    if (! posix_mkfifo($file, 0664)) {
        die("Create fifo file failed.\n");
    }
}

$i = 0;
while (true) {
    $i++;
    $handle = fopen($file, 'w');
    fwrite($handle, "({$i})");
    sleep(1);
    if ($i == 10) break;
}
