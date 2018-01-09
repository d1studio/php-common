<?php
include __DIR__.'/../../vendor/autoload.php';
$queue = \d1studio\common\queue\Queue::getQueue('send_coupon_test',[
    'drive'  => 'Mysql',
    'option' => [
        'database_name' => 'queue',
        'server'        => '127.0.0.1',
        'username'      => 'root',
        'password'      => '123456',

    ],
]);
//$queue = \d1studio\php_queue\Queue::getQueue('send_coupon',[
//    'drive'  => 'Redis',
//    'option' => [
//        'host'   => 'redis',
//        'port'   => '6379',
//        'db'     => 0,
//        'prefix' => 'queue',
//    ],
//]);