<?php
require_once(__DIR__ . '/TinyRedisClient.php');

$server = 'localhost:6379';
$db = 0;
$prefix = 'resque';

$redis = new TinyRedisClient($server);
$redis->select($db);

$redis->multi();
$redis->smembers("$prefix:queues");
$redis->smembers("$prefix:workers");
$redis->get("$prefix:stat:processed");
$redis->get("$prefix:stat:failed");
$data = $redis->exec();
$queueIdList = (array) $data[0];
$workerIdList = (array) $data[1];
$stat = ['processed' => (int) $data[2], 'failed' => (int) $data[3]];

$queues = [];
foreach ($queueIdList as $queueId) {
    $nextJob = (array) $redis->lrange("$prefix:queue:$queueId", 0, 0);
    $queues[] = [
        'id' => $queueId,
        'size' => $redis->llen("$prefix:queue:$queueId"),
        'next_job' => $nextJob ? $nextJob[0] : null,
    ];
}

$workers = [];
foreach ($workerIdList as $workerId) {
    $currentJob = $redis->get("$prefix:worker:$workerId");
    $workers[] = [
        'id' => $workerId,
        'started' => $redis->get("$prefix:worker:$workerId:started"),
        'current_job' => $currentJob ? : null,
        'processed' => (int) $redis->get("$prefix:stat:processed:$workerId"),
        'failed' => (int) $redis->get("$prefix:stat:failed:$workerId"),
    ];
}

$failLog = (array) $redis->lrange("$prefix:failed", -5, -1);

echo "SERVER: $server  DB: $db  PROCESSED: $stat[processed]  FAILED: $stat[failed]" . PHP_EOL . PHP_EOL;
echo "QUEUES (" . count($queues) . ")" . PHP_EOL . PHP_EOL;
for ($i = 0, $il = count($queues); $i < $il; $i++) {
    echo ($i + 1) . '. NAME: ' . $queues[$i]['id'];
    echo '  SIZE: ' . $queues[$i]['size'];
    echo '  NEXT JOB: ' . $queues[$i]['next_job'];
    echo PHP_EOL;
}
echo PHP_EOL;
echo "WORKERS (" . count($workers) . ")" . PHP_EOL . PHP_EOL;
for ($i = 0, $il = count($workers); $i < $il; $i++) {
    echo ($i + 1) . '. ID: ' . $workers[$i]['id'];
    echo '  STARTED: ' . $workers[$i]['started'];
    echo '  PROCESSED: ' . $workers[$i]['processed'];
    echo '  FAILED: ' . $workers[$i]['failed'];
    echo PHP_EOL . '   CURRENT JOB: ' . $workers[$i]['current_job'];
    echo PHP_EOL;
}
echo PHP_EOL;
echo 'LAST 5 FAILS:' . PHP_EOL . PHP_EOL;
foreach ($failLog as $failItem) {
    $decoded = json_decode($failItem);
    echo $decoded->failed_at . ' ' . $decoded->payload->class . ' ' . $decoded->error . ' ' . json_encode(
        $decoded->payload->args
    ) . PHP_EOL;
}


