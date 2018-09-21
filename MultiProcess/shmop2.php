<?php

$resource = shmop_open('1946288158', 'c', 0664, 200);

echo shmop_read($resource, 0, 200) . PHP_EOL;
