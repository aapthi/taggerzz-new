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

class UserMessagesTable 
{
    protected $tableGateway;
	protected $select;
	
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	public function getUserMessages( $userId,$finalIds ){
		$select = $this->tableGateway->getSql()->select();
		$select->join('user', 'user_messages.msg_sender_id=user.user_id',array('*'),'left');
		$select->where('user_messages.msg_receiver_id="'.$userId.'"');
		if($finalIds!=""){
		$select->where('user_messages.msg_sender_id NOT IN ('.$finalIds.')');
		}		
		$select->order('user_messages.msg_id DESC');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function changeMsgStatus( $msgId ){
		$data = array(
			'msg_flag' =>'1'
		);	
		$row=$this->tableGateway->update($data, array('msg_id' => $msgId));
		return $row;
	}
	public function getMessageDetails( $msgId ){
		$select = $this->tableGateway->getSql()->select();
		$select->join('user', 'user_messages.msg_sender_id=user.user_id',array('*'),'left');	
		$select->where('user_messages.msg_id="'.$msgId.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function addMsg($msg){
		$data = array(
				'msg_sender_id'          => $_SESSION['usersinfo']->userId, 
				'msg_receiver_id' 	     => $msg['msg_receiver_id'], 
				'msg_subject'            => $msg['msg_subject'], 
				'message'                => $msg['message'], 
				'msg_flag'               => 0, 
				'msg_status'  	         => 1,
				'msg_sended_date' 	     => date('Y-m-d H:i:s'), 
		);	
		$resultset=$this->tableGateway->insert($data);
		return $resultset;
	}
	
}