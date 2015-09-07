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

class DataboxViewsTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	public function addUserCategoryId($category_id)
    {	
		$data = array(
			'category_id' 	     	=> $category_id, 
			'views_count' 	     	=> 0, 
			'created_date' 	 	    => date('Y-m-d H:i:s'), 	
			'last_updated_date'     => date('Y-m-d H:i:s'), 	
			'status'                => 1, 	
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }
	public function updateViewsCount( $category_id)
	{
		$sql = "UPDATE databox_views SET views_count= views_count+1, last_updated_date=now() WHERE category_id= '".$category_id."'";
		$updateStatus=$this->tableGateway->getAdapter()->driver->getConnection()->execute($sql);
		return $updateStatus;
	}
	public function insertDataboxUserId($category_id)
    {	
		$data = array(
			'category_id' 	     	=> $category_id, 
			'views_count' 	     	=> 1,
			'created_date' 	 	    => date('Y-m-d H:i:s'), 	
			'last_updated_date'     => date('Y-m-d H:i:s'), 	
			'status'                => 1, 	
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }
}