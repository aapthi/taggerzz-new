<?php
namespace Slave\Model;
class Slave
{
    public $id;
    public $name;
    public $status;
    public function exchangeArray($data)
    {
        $this->id     	= (isset($data['id']))     	? $data['id']     	: null;
        $this->name 	= (isset($data['name'])) 	? $data['name'] 	: null;
        $this->status  	= (isset($data['status']))  ? $data['status']  	: null;
    }
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }  
}