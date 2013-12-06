<?php
require __DIR__ . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use BZRK\Board;
use BZRK\Logger;
use BZRK\Listener\AddTask;
use BZRK\Listener\SetStatus;
use BZRK\Listener\InitTasks;
use BZRK\TaskStack;

$logger = new Logger();
$board = new Board($logger);
$stack = new TaskStack();
$task = new \BZRK\Task();
$task->setTitle('title');
$task->setStatus('open');
$stack->addTask($task);

$task = new \BZRK\Task();
$task->setTitle('title1');
$task->setStatus('inProgress');
$stack->addTask($task);


$board->addOnMessageListener(new AddTask($board, $stack));
$board->addOnMessageListener(new SetStatus($board, $stack));
$board->addOnOpenListener(new InitTasks($board, $stack));

try{
    $server = IoServer::factory(
         new HttpServer(
            new WsServer(
                $board
            )
        ),
        8080
    );
    $logger->info('Server started on Port 8080');
    $server->run();
}catch(Exception $e){
    $logger->error($e->getMessage() . chr(10) . $e->getTraceAsString());
}
