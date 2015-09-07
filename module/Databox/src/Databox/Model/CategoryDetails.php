<?php
namespace Products\Model;

class Productsnew
{
	public $product_id;
	public $category_id;
	public $brandcode;
	public $genericname;
	public $fuction;
	public $manufacturer;
	
	public function exchangeArray($data){
	
		$this->product_id  = (isset($data['product_id'])) ? $data['product_id']	  : null;
		
		$this->category_id = (isset($data['category_id'])) ? $data['category_id']	: null;
		
		$this->brandcode   = (isset($data['brandcode']))  ? $data['brandcode']	  : null;
		
		$this->genericname = (isset($data['genericname'])) ? $data['genericname'] : null;
		
		$this->fuction     = (isset($data['fuction']))     ? $data['fuction']      : null;
		
		$this->manufacturer= (isset($data['manufacturer'])) ? $data['manufacturer']: null;
	}

	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}