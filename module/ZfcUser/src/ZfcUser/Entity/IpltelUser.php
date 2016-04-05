<?php
namespace Application\Model;

class IpltelUser
{
    public $email;
    public $areaprefix;
    public $phone_number;
    public $pin;
    public $status;
	
    public function exchangeArray($data)
    {
        $this->email     = (isset($data['email'])) ? $data['email'] : null;
        $this->areaprefix = (isset($data['areaprefix'])) ? $data['areaprefix'] : null;
        $this->pin  = (isset($data['pin'])) ? $data['pin'] : null;
        $this->phone_number  = (isset($data['phone_number'])) ? $data['phone_number'] : null;
        $this->status  = (isset($data['status'])) ? $data['status'] : null;
    }

	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
	
}
?>