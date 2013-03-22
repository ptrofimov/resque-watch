<?php
require_once(__DIR__ . '/vendor/autoload.php');

$i = 1;
while (true) {
    Resque::enqueue('good', 'Good_Job', [$i], true);
    Resque::enqueue('bad', 'Bad_Job', [$i], true);
    sleep(1);
    $i++;
}
