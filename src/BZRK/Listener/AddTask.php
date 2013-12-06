<?php
namespace BZRK\Listener;

use BZRK\OnMessageListener;
use BZRK\Board;
use BZRK\Message;
use BZRK\Task;
use BZRK\TaskStack;
use Ratchet\ConnectionInterface;

class AddTask implements OnMessageListener
{
    private $board = null;
    private $stack = null;
    
    public function __construct(Board $board, TaskStack $stack)
    {
        $this->board = $board;
        $this->stack = $stack;
    }
    
    public function execute(ConnectionInterface $to, Message $message)
    { 
        if($message->getCommand() == 'AddTask'){
            $obj = $message->getData();
            $task= new Task();
            $task->setTitle($obj->title);
            $task->setStatus($obj->status);
            $task = $this->stack->addTask($task);
            $message->setData($task->toStdClass());
            $this->board->sendAll($message);
        }
    }
}
