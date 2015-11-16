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

class InvitationsTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	public function insertInivite( $emailId,$comment,$uid )
    {	
		$data = array(
			'inivit_email' 	   => $emailId, 
			'comment' 	       => $comment, 
			'user_id' 	       => $uid, 
			'inivit_date_time' => date('Y-m-d H:i:s'), 	
			'in_status' 	   => "1"	
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }
	public function getInfo($emailid,$userid){
		$select = $this->tableGateway->getSql()->select();
		$select->where('inivit_email="'.$emailid.'"');
		$select->where('user_id="'.$userid.'"');
		$select->where('in_status="1"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->count();
		return $row;
	}
	public function inivtedEmailCheck($emailid){
		$select = $this->tableGateway->getSql()->select();
		$select->where('inivit_email="'.$emailid.'"');
		$select->where('in_status="1"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();
		return $row;
	}
	public function updateStatus($inivit_id){
		$data = array(
			'in_status' 	      => "0",
		);	
		$row=$this->tableGateway->update($data, array('inivit_id' => $inivit_id));
		return $row;
	}
}