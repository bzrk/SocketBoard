<?php
namespace BZRK\Listener;

use Ratchet\ConnectionInterface;
use BZRK\OnOpenListener;
use BZRK\Board;
use BZRK\Message;
use BZRK\TaskStack;

class InitTasks implements OnOpenListener
{
    private $board = null;
    private $stack = null;
    
    public function __construct(Board $board, TaskStack $stack)
    {
        $this->board = $board;
        $this->stack = $stack;
    }
            
    public function execute(ConnectionInterface $to)
    {
        $message = new Message();
        $message->setCommand('init');
        $message->setData($this->getData());
        $this->board->send($to, $message);
    }
    
    private function getData()
    {
        $data = array();
        foreach($this->stack->getTasks() as $task){
            $data[] = $task->toStdClass();
        }
        return $data;
    }
}
