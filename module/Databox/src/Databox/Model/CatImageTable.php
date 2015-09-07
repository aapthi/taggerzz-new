<?php
namespace Databox\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;

class CatImageTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }

	public function addImage()
    {	
		$data = array( 
			'create_date' 	        => date('Y-m-d H:i:s'),			
			'update_date' 	        => date('Y-m-d H:i:s'), 	
			'status' 	            => "1",	
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }
	public function deleteImage( $id )
    {	
		$deleteStatus=$this->tableGateway->delete(array('image_id' => $id));
		return $deleteStatus;
	}
}