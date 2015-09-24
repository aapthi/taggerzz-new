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

class DataboxCommentsTable
{
    protected $tableGateway;
	protected $select;
	
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	public function addComment($comment){
		$data = array(
				'comment_user_id'  => $_SESSION['usersinfo']->userId,
				'databox_comment'  => $comment['comment'], 
				'created_date'     => date('Y-m-d H:i:s'), 
				'updated_date'     => date('Y-m-d H:i:s'), 
				'comment_status'   => 1,
		);	
		$resultset=$this->tableGateway->insert($data);
		return $resultset;
	}
	public function getDataboxComments(){
		$select = $this->tableGateway->getSql()->select();
		$select->join('user', 'databox_comments.comment_user_id=user.user_id',array('*'),'left');
		$select->order('databox_comments.databox_comment_id DESC');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function updateCommentId( $commentId,$comment){
		$data = array(
			'databox_comment' =>$comment
		);	
		$row=$this->tableGateway->update($data, array('databox_comment_id' => $commentId));
		return $row;
	}
	public function deleteCommentId( $Id )
    {	
		$deleteCommentId=$this->tableGateway->delete(array('databox_comment_id' => $Id));
		return $deleteCommentId;
	}
	
}