<?php
namespace Databoxuser\Model;

class ActivityPoints
{
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}