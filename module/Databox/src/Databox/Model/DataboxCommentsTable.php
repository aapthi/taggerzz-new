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
				'parent_comment_id'=> 0, 
				'created_date'     => date('Y-m-d H:i:s'), 
				'updated_date'     => date('Y-m-d H:i:s'), 
				'comment_status'   => 1,
				'databox_id'  => $comment['category_id'],
		);	
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
	}
	public function addReplyComment($comment){
		$data = array(
			'comment_user_id'  => $_SESSION['usersinfo']->userId,
			'databox_comment'  => $comment['comment'], 
			'parent_comment_id'=> $comment['parent_comt_id'], 
			'created_date'     => date('Y-m-d H:i:s'), 
			'updated_date'     => date('Y-m-d H:i:s'), 
			'comment_status'   => 1,
			'databox_id'  => $comment['category_id'],
		);	
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
	}
	public function getDataboxComments($categoryId){
		$select = $this->tableGateway->getSql()->select();
		$select->join('user', 'databox_comments.comment_user_id=user.user_id',array('*'),'left');
		$select->join('user_details', 'user_details.user_id=user.user_id',array('*'),'left');
		$select->where('databox_comments.databox_id="'.$categoryId.'"');
		$select->order('databox_comments.databox_comment_id ASC');
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
	public function deleteCommentId( $Id,$type )
    {	
		if($type=='comment'){
			$deleId = $this->tableGateway->delete(array('(parent_comment_id IN ('.$Id.'))'));
			$deleteCommentId=$this->tableGateway->delete(array('databox_comment_id' => $Id));
			return $deleteCommentId;
		}else{
			$deleteCommentId=$this->tableGateway->delete(array('databox_comment_id' => $Id));
			return $deleteCommentId;
		}
	}
	public function totalCommentsOfDataBox($catid){
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'databox_comments.databox_id=category.category_id',array('*'),'left');
		$select->where('category.fresh_databox="1"');
		$select->where('databox_comments.databox_id="'. $catid .'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->count();
	}
}