<?php

function serven()
{
    yield 7;
}

/**
 * 基础用法
 */
function y()
{
    yield 123;

    yield 123 => 5;

    yield;

    yield from [4, 5, 6];

    yield from serven();

    yield from new ArrayIterator([8, 9]);
}

$gen = y();

foreach ($gen as $value) {
    echo $value . PHP_EOL;
}
