<?php
namespace BZRK;

class Task
{
    private $data;
    
    public function __construct()
    {
        $this->data = (object)array(
            'id' => null,
            'title' => null,
            'status' => null,
        );
    }
    
    
    public function getId()
    {
        return $this->data->id;
    }

    public function setId($id)
    {
        $this->data->id = $id;
    }
    
    public function getTitle()
    {
        return $this->data->title;
    }

    public function getStatus()
    {
        return $this->data->status;
    }

    public function setTitle($title)
    {
        $this->data->title = $title;
    }

    public function setStatus($status)
    {
        $this->data->status = $status;
    }
    
    public function toStdClass()
    {
        return $this->data;
    }
}
