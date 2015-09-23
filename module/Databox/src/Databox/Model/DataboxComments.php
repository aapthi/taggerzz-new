<?php
namespace Databox\Model;

class DataboxComments
{
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	
}