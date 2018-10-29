<?php

class Scheduler
{
    protected $list = [];

    public function addTask(Generator $gen)
    {
        array_push($this->list, $gen);
    }

    public function run()
    {
        // 队列出队和进队来交替执行任务
        while (!empty($this->list)) {
            $gen = array_shift($this->list);

            // 让生成器继续执行一次
            $gen->send(null);

            if ($gen->valid()) {
                array_push($this->list, $gen);
            }
        }
    }
}


function task1()
{
    for ($i = 1; $i <= 5; $i++) {
        echo "Task(1) {$i}\n";
        yield;
    }
}

function task2()
{
    for ($i = 1; $i <= 10; $i++) {
        echo "Task(2) {$i}\n";
        yield;
    }
}

$sc = new Scheduler();

$sc->addTask(task1());
$sc->addTask(task2());

$sc->run();
