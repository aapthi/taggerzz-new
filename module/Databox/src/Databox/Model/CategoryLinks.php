<?php
namespace Databox\Model;

class CategoryLinks
{
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}