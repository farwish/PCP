<?php

print_r("Content before fastcgi_finish_request");

fastcgi_finish_request();

while (true) {
}
