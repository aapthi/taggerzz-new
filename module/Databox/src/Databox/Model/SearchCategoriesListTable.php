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

class SearchCategoriesListTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }

	public function addSearchCategory($s_cat_name)
    {	
		$data = array(
			'search_cat_name' 	=> $s_cat_name, 
			's_cat_status' 	    => 1			
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }
	public function listSearchCategory(){
		$select = $this->tableGateway->getSql()->select();
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;	
	}
}