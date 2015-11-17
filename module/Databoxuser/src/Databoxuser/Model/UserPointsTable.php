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
	public function lastActivity($uid){
		$select = $this->tableGateway->getSql()->select();
		$select->where('user_id="'.$uid.'"');
		$select->where('up_status="1"');
		$select->order('up_id DESC');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();
		return $row;
	}
	public function loggedUserPoints($uid){
		$select = $this->tableGateway->getSql()->select();
		$select->join('activity_points', 'activity_points.activity_id=user_points.activity_id',array('userPoints' => new Expression('COALESCE((SUM(activity_points.a_points)),0)')),'left');	
		$select->where('user_points.user_id="'.$uid.'"');
		$select->where('user_points.up_status="1"');
		$select->group('user_points.user_id');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->current();
	}
}