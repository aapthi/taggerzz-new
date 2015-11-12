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

class UserPointsTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	public function addUserPoints( $uid,$aid )
    {	
		$data = array(
			'user_id' 	         => $uid, 
			'activity_id' 	     => $aid, 
			'activity_dt' 	     => date('Y-m-d H:i:s'), 	
			'up_status' 	     => "1"	
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