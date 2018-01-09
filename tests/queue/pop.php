<?php
require __DIR__.'/bootstrap.php';


$message = $queue->pop();

$message->ack(false);


//print_r($queue->pop());




