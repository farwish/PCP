<?php

// 继承 Thread 的类具有创建线程的能力
class Request extends Thread
{
    public $str;

    public $i;

    public function __construct($i)
    {
        $this->i = $i;
    }

    public function run()
    {
        if ($this->i == 3) {
            sleep(5);
        } else {
            sleep(1);
        }
        $this->str = $this->i;
    }
}

$arr = [];
for ($i = 0; $i <= 3; $i++) {
    $request = new Request($i);
    $arr[$i] = $request;
    // 创建新线程，随后线程会执行 run 方法
    if (! $request->start()) {
        die("Start thread failed\n");
    }
    echo "Thread({$i}) started\n";
}


for ($i = 0; $i <= 3; $i++) {
    // join 是阻塞的，所以脚本运行时间取决于耗时最长的线程
    if (! $arr[$i]->join()) {
        die("Join failed\n");
    }
    echo $arr[$i]->str . PHP_EOL;
}
