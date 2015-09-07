<?php
namespace Databox\Model;

class SearchCategoriesList
{
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}