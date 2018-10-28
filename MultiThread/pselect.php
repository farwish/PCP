<?php

// 继承 Thread 的类具有创建线程的能力
class Request extends Thread
{
    private $sql;

    private $dsn;

    public function __construct($sql, $dsn)
    {
        $this->sql = $sql;
        $this->dsn = $dsn;
    }

    public function run()
    {
        $db = new PDO($this->dsn);

        $stat1 = $db->query($this->sql);

        $result = $stat1->fetchAll(PDO::FETCH_ASSOC);

        print_r($result);
    }
}

// 指定 driver 和 数据库
$dsn = 'sqlite:/tmp/pselect.db';
$db = new PDO($dsn);

// 创建表
$db->exec('create table users(id int, name varchar(255))');
$db->exec('create table books(id int, name varchar(255))');

// 插入测试数据
//$db->exec("insert into users(id, name) values(1, '张三')");
//$db->exec("insert into users(id, name) values(2, '李四')");

//$db->exec("insert into books(id, name) values(1, '三国')");
//$db->exec("insert into books(id, name) values(2, '水浒')");

// 要同时执行的sql
$sql = [
    'select * from users',
    'select * from books',
];

//$stat1 = $db->query($sql[0]);
//$stat2 = $db->query($sql[1]);

//$results1 = $stat1->fetchAll(PDO::FETCH_ASSOC);
//$results2 = $stat2->fetchAll(PDO::FETCH_ASSOC);

//print_r($results1);
//print_r($results2);


$arr = [];
for ($i = 0; $i < 2; $i++) {
    $request = new Request($sql[$i], $dsn);
    $arr[$i] = $request;
    // 创建新线程，随后线程会执行 run 方法
    if (! $request->start()) {
        die("Start thread failed\n");
    }
    echo "Thread({$i}) started\n";
}


for ($i = 0; $i < 2; $i++) {
    // join 是阻塞的，所以脚本运行时间取决于耗时最长的线程
    if (! $arr[$i]->join()) {
        die("Join failed\n");
    }
}
