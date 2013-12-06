<?php
namespace BZRK\Listener;

use BZRK\OnMessageListener;
use BZRK\Board;
use BZRK\Message;
use BZRK\Task;
use BZRK\TaskStack;
use Ratchet\ConnectionInterface;

class SetStatus implements OnMessageListener
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
        if($message->getCommand() == 'SetStatus'){
            
            $obj = $message->getData();
            
            $task = $this->stack->getTask($obj->key);
            $task->setStatus($obj->status);
              
            $message->setData($task->toStdClass());
            $message->setCommand('UpdateTask');
            $this->board->sendAll($message);
        }
    }
}
