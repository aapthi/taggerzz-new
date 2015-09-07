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

class UserCollectionsTable 
{
    protected $tableGateway;
	protected $select;
	
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	
	public function addUserCollection( $userCollectionDetails )
    {
		$userId = 0;
		$ip=$_SERVER['REMOTE_ADDR'];
		if( isset($_SESSION['usersinfo']) )
		{
			$userId = $_SESSION['usersinfo']->userId;
		}
		else
		{
			$userId = $ip;
		}

		$data = array(
			'collector_id'   => $userId, 
			'category_link_id' 	 => $userCollectionDetails["catLinkId"], 
			'category_user_id' 	 => $userCollectionDetails["currBoxUserId"], 
			'status' 	     => "1",
			'created_date' 	 => date('Y-m-d H:i:s'),
			'updated_date' 	 => date('Y-m-d H:i:s')
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }
	
	public function getUserCollectedLinks()
	{
		$userId = 0;
		$ip=$_SERVER['REMOTE_ADDR'];
		if( isset($_SESSION['usersinfo']) )
		{
			$userId = $_SESSION['usersinfo']->userId;
		}
		else
		{
			$userId = $ip;
		}

		$select = $this->tableGateway->getSql()->select();
		$select->join('category_links', 'user_collections.category_link_id=category_links.category_link_id',array('*'),'left');	
		$select->join('link_details', 'user_collections.category_link_id=link_details.link_id',array('*'),'left');	
		$select->where('user_collections.collector_id="'.$userId.'"');
		$select->order('user_collections.collection_id');
		$resultSet = $this->tableGateway->selectWith($select);
		// echo "<pre>";print_r($resultSet);exit;
		return $resultSet;
	}
	
	public function deleteCollectedLink( $collectionId )
    {	
		$deleteStatus=$this->tableGateway->delete(array('collection_id' => $collectionId));
		return $deleteStatus;
	}
	public function getCollectedLinksCount( $userId )
    {	
		$select = $this->tableGateway->getSql()->select();
		$select->join('category_links', 'user_collections.category_link_id=category_links.category_link_id',array('*'),'left');	
		$select->where('user_collections.collector_id="'.$userId.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
}