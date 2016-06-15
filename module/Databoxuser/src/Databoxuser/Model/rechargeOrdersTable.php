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

class rechargeOrdersTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	public function addRechargeDetails($recharge_mobile,$amt,$recharge_rp_id,$recharge_agent_id,$recharge_op_id,$recharge_msg,$recharge_usage_points,$recharge_status)
    {	
	
		$recharge_user_id=$_SESSION['usersinfo']->userId;
		$data = array(
			'recharge_user_id' 	         => $recharge_user_id, 
			'recharge_mobile' 	         => $recharge_mobile, 
			'recharge_amount' 	         => $amt, 
			'recharge_rp_id' 	         => $recharge_rp_id, 
			'recharge_agent_id' 	     => $recharge_agent_id, 
			'recharge_op_id' 	         => $recharge_op_id, 
			'recharge_msg' 	             => $recharge_msg, 
			'recharge_usage_points' 	 => $recharge_usage_points, 
			'recharge_status' 	         => $recharge_status, 
			'recharge_date' 	         => date('Y-m-d H:i:s') 	
		);
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }
	public function userRechargedPoints($uid){
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array('userPointsminus' => new Expression('COALESCE((SUM(recharge_orders.recharge_usage_points)),0)')));
		$select->where('recharge_user_id="'.$uid.'"');
		$select->where('recharge_status="1"');
		$select->group('recharge_user_id');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->current();
	}
}