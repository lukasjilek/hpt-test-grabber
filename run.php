<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

// code here
$input = fopen("vstup.txt", "r");

$dispatcher = new \HPT\Dispatcher($input, new \HPT\CZCGrabber(), new \HPT\ProductOutput());

echo $dispatcher->run() . PHP_EOL;;
