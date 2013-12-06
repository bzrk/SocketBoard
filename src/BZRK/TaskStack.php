<?php
namespace BZRK;

class TaskStack
{
    private $data = array();
    
    public function addTask(Task $task)
    { 
        if(empty($task->getId())){
            $task->setId(sha1(microtime()));
        }
        $this->data[$task->getId()] = $task;
       
        return $task;
    }
    
    public function getTask($key)
    {
        if(isset($this->data[$key])){
            return $this->data[$key];
        }
        return null;
    }
    
    public function getTasks()
    {
        return $this->data;
    }
}
