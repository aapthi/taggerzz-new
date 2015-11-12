<?php
namespace Databoxuser\Model;

class Invitations
{
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}