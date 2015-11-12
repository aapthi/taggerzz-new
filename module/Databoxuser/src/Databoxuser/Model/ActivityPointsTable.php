<?php
namespace Databoxuser\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;

class ActivityPointsTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	public function addUser( $userInfo )
    {	
		$data = array(
			'email' 	         => $userInfo['email'], 
			'password' 	         => $userInfo['password'], 
			'created_date' 	     => date('Y-m-d H:i:s'), 	
			'last_updated_date'  => date('Y-m-d H:i:s'), 	
			'status' 	         => "0"	,
			'type' 	         	 => "1"	,
			'display_name' 	     => $userInfo['displayname'] 
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }
	public function getActivityFresh($a_name){
		$select = $this->tableGateway->getSql()->select();
		$select->where('a_name="'.$a_name.'"');
		$select->where('a_status="1"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();		
		return $row;
	}
}