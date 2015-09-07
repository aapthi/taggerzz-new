<?php
namespace Databox\Model;

class UserCollections
{
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	
}