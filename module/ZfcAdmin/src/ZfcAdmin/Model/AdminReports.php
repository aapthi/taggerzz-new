<?php
namespace ZfcAdmin\Model;

class AdminReports
{
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}