<?php
namespace Databox\Model;

class SettingFlexibleType
{
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}