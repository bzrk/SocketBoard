<?php
namespace BZRK;

class Message
{
    private $command = null;
    private $data = null;
    
    public static function createInstance($value)
    {
        $message = new Message();
        $obj = json_decode($value);
        if(is_object($obj)){
            $message->setCommand($obj->command);
            $message->setData($obj->data);
        }
        return $message;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setCommand($command)
    {
        $this->command = $command;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function __toString()
    {
        return json_encode((object)array(
            'command' => $this->command,
            'data'    => $this->data
        ));
    }
}
