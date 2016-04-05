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

class BlockUserTable 
{
    protected $tableGateway;
	protected $select;
	
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	public function insertBlockedUser($user){
		$data = array(
				'block_by_uid'           => $_SESSION['usersinfo']->userId, 
				'blocked_to_uid' 	     => $user['blocked_to_uid'], 
				'status'                 => 0, 
				'added_at' 	             => date('Y-m-d H:i:s'), 
				'updated_at' 	         => date('Y-m-d H:i:s'), 
		);	
		$resultset=$this->tableGateway->insert($data);
		return $resultset;
	}
	public function checkBlockedToUser( $blockedId ){
		$userId=$_SESSION['usersinfo']->userId;
		$select = $this->tableGateway->getSql()->select();
		$select->where('block_user.blocked_to_uid="'.$blockedId.'"');
		$select->where('block_user.block_by_uid="'.$userId.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function BlockedUser( $blockedId,$userId ){
		//$userId=$_SESSION['usersinfo']->userId;
		$select = $this->tableGateway->getSql()->select();
		$select->where('block_user.block_by_uid="'.$blockedId.'"');
		$select->where('block_user.blocked_to_uid="'.$userId.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function getBlockedIds(){
		$select = $this->tableGateway->getSql()->select();
		$userId=$_SESSION['usersinfo']->userId;
		$select->where(array('status' => 0));
		$select->where
		  ->NEST->
				equalTo('block_user.block_by_uid',$userId)
					->OR->
				equalTo('block_user.blocked_to_uid',$userId)
		  ->UNNEST;
	    $resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
}