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

class RelevanceWorthVoteTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	public function voteOnHighlight( $category_id,$userId,$type,$rw_th )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->where( 'category_id="'. $category_id .'"' );
		$resultSet = $this->tableGateway->selectWith($select);
		if($resultSet->count()!=0){
			$select = $this->tableGateway->getSql()->select();
			$select->where( 'category_id="'. $category_id .'"' );
			$select->where( 'user_id="'. $userId .'"' );
			$resultSet1 = $this->tableGateway->selectWith($select);
			if($resultSet1->count()==0){
				if($type==0){
					$voteDown=1;
					$voteUp=0;
				}else{
					$voteUp=1;
					$voteDown=0;
				}
				$data = array(
					'voteUp' 			=> $voteUp,
					'voteDown' 			=> $voteDown,
					'user_id' 			=> $userId,
					'category_id' 		=> $category_id,
					'rw_lh' 		    => $rw_th,
					'created_date'		=>	date('Y-m-d H:i:s'),
					'updated_date'		=>	date('Y-m-d H:i:s'), 
				);
				$this->tableGateway->insert($data);					
				return $this->tableGateway->lastInsertValue;	
			}else{
				if($type==0){
					$voteDown=1;
					$voteUp=0;
				}else{
					$voteUp=1;
					$voteDown=0;
				}
				$data = array(
					'voteUp' 			=> $voteUp,
					'voteDown' 			=> $voteDown,
					'updated_date'		=>	date('Y-m-d H:i:s'), 
				);	
				$row=$this->tableGateway->update($data, array('category_id' => $category_id,'user_id' => $userId));
				return $row;
			}
		}else{
			if($type==0){
				$voteDown=1;
				$voteUp=0;
			}else{
				$voteUp=1;
				$voteDown=0;
			}
			$data = array(
				'voteUp' 			=> $voteUp,
				'voteDown' 			=> $voteDown,
				'user_id' 			=> $userId,
				'rw_lh' 		    => $rw_th,
				'category_id' 		=> $category_id,
				'created_date'		=>	date('Y-m-d H:i:s'),
				'updated_date'		=>	date('Y-m-d H:i:s'), 
			);
			$this->tableGateway->insert($data);		
			return $this->tableGateway->lastInsertValue;			
		}
	}

	public function voteOnRelevance( $category_id,$userId,$type )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->where( 'category_id="'. $category_id .'"' );
		$resultSet = $this->tableGateway->selectWith($select);
		if($resultSet->count()!=0){
			$select = $this->tableGateway->getSql()->select();
			$select->where( 'category_id="'. $category_id .'"' );
			$select->where( 'user_id="'. $userId .'"' );
			$resultSet1 = $this->tableGateway->selectWith($select);
			if($resultSet1->count()==0){
				$data = array(
					'relevance' 		=> $type,
					'user_id' 			=> $userId,
					'category_id' 		=> $category_id,
					'created_date'		=>	date('Y-m-d H:i:s'),
					'updated_date'		=>	date('Y-m-d H:i:s'), 
				);
				$this->tableGateway->insert($data);		
				return $this->tableGateway->lastInsertValue;	
			}else{
				$data = array(
					'relevance' 		=> $type,
					'updated_date'		=>	date('Y-m-d H:i:s'), 
				);	
				$row=$this->tableGateway->update($data, array('category_id' => $category_id,'user_id' => $userId));
				return $row;
			}
		}else{
			$data = array(
				'relevance' 		=> $type,
				'user_id' 			=> $userId,
				'category_id' 		=> $category_id,
				'created_date'		=>	date('Y-m-d H:i:s'),
				'updated_date'		=>	date('Y-m-d H:i:s'), 
			);
			$this->tableGateway->insert($data);		
			return $this->tableGateway->lastInsertValue;			
		}
	}

	public function voteOnWorth( $category_id,$userId,$type )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->where( 'category_id="'. $category_id .'"' );
		$resultSet = $this->tableGateway->selectWith($select);
		if($resultSet->count()!=0){
			$select = $this->tableGateway->getSql()->select();
			$select->where( 'category_id="'. $category_id .'"' );
			$select->where( 'user_id="'. $userId .'"' );
			$resultSet1 = $this->tableGateway->selectWith($select);
			if($resultSet1->count()==0){
				$data = array(
					'worth'		 		=> $type,
					'user_id' 			=> $userId,
					'category_id' 		=> $category_id,
					'created_date'		=>	date('Y-m-d H:i:s'),
					'updated_date'		=>	date('Y-m-d H:i:s'), 
				);
				$this->tableGateway->insert($data);		
				return $this->tableGateway->lastInsertValue;	
			}else{
				$data = array(
					'worth'		 		=> $type,
					'updated_date'		=>	date('Y-m-d H:i:s'), 
				);	
				$row=$this->tableGateway->update($data, array('category_id' => $category_id,'user_id' => $userId));
				return $row;
			}
		}else{
			$data = array(
				'worth'		 		=> $type,
				'user_id' 			=> $userId,
				'category_id' 		=> $category_id,
				'created_date'		=>	date('Y-m-d H:i:s'),
				'updated_date'		=>	date('Y-m-d H:i:s'), 
			);
			$this->tableGateway->insert($data);		
			return $this->tableGateway->lastInsertValue;			
		}
	}

	public function getVoteUpDown( $category_id,$userId ){
		$select = $this->tableGateway->getSql()->select();
		$select->where( 'category_id="'. $category_id .'"' );
		$select->where( 'user_id="'. $userId .'"' );
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function getRelevanceCount( $categoryId,$value )
    {
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array('countRelevance'.$value => new \Zend\Db\Sql\Expression('count(relevance)')));
		$select->where('category_id="'.$categoryId.'"');
		$select->where('relevance="'.$value.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();
		return $row;
	}
	public function getWorthCount( $categoryId,$value )
    {
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array('countWorth'.$value => new \Zend\Db\Sql\Expression('count(worth)')));
		$select->where('category_id="'.$categoryId.'"');
		$select->where('worth="'.$value.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();
		return $row;
	}
	public function getVotesUpDownCount()
    {
		$userId=$_SESSION['usersinfo']->userId;
		
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array('countUp' => new \Zend\Db\Sql\Expression('sum(voteUp)'),'countDown' => new \Zend\Db\Sql\Expression('sum(voteDown)')));
		$select->where('user_id="'.$userId.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();
		return $row;
	}
	public function votesPercentageAndLD($cat_id){
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array('NetVotes1' => new Expression('COALESCE((SUM(voteUp)/SUM(rw_lh)*100),0)'),'vupCatId'=>'category_id','voteUp1'=>new Expression('COALESCE(SUM(voteUp),0)'),'rw_lh1'=>new Expression('COALESCE(SUM(rw_lh),0)')));
		$select->where('relevance_worth_vote.rw_lh="1"');
		$select->where('relevance_worth_vote.category_id="'.$cat_id.'"');
		$select->group('relevance_worth_vote.category_id');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->current();
	}
	public function userLikedDisLiked($cat_id){
		if(isset($_SESSION['usersinfo']->userId)){
			$userId=$_SESSION['usersinfo']->userId;
		}else{
			
			$userId=$_SERVER['REMOTE_ADDR'];
		}
		$select = $this->tableGateway->getSql()->select();
		$select->where('relevance_worth_vote.user_id="'.$userId.'"');
		$select->where('relevance_worth_vote.category_id="'.$cat_id.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->current();
	}
}