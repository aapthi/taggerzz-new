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

class CategoryLinksTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }

	public function addLinkMains( $category_id,$link,$linkValidityStatus )
    {	
		$data = array(
			'user_category_id' 	     => $category_id, 
			'link' 	     			 => $link, 	
			'link_validity_status' 	 => $linkValidityStatus, 	
			'create_date' 	     => date('Y-m-d H:i:s'), 	
			'status' 	         => "1"	
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }

	public function getCategoryLinks( $user_category_id )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->where('category_links.user_category_id="'. $user_category_id .'"');
		$select->where('category_links.link_validity_status="1"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function checkLinks($link)
	{
		$select = $this->tableGateway->getSql()->select();
		$select->where('category_links.link="'.$link.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->count();
	}
	
	public function getEditCategoryLinks( $user_category_id )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('link_details', 'category_links.category_link_id=link_details.link_id',array('title'),'left');
		$select->where('category_links.user_category_id="'. $user_category_id .'"');
		$select->where('category_links.link_validity_status="1"');
		$select->order('link_details.url_id ASC');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}

	public function getUserCategoryLinkIds( $user_category_id )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->where('category_links.user_category_id="'. $user_category_id .'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	public function deleteCategoryLinks( $userCategoryId )
    {	
		$deleteStatus=$this->tableGateway->delete(array('user_category_id' => $userCategoryId));
		return $deleteStatus;
	}
	
	public function getCategoryWiseLinksCount()
    {
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array('categoryId' => 'user_category_id','link_validity_status','categoryLinksCount' => new \Zend\Db\Sql\Expression('count(link)')));
		$select->where('link_validity_status="1"');
		$select->group('user_category_id');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	public function deleteCustomisedCategoryLink( $category_link_id )
    {	
		$deleteStatus=$this->tableGateway->delete(array('category_link_id' => $category_link_id));
		return $deleteStatus;
	}
	
	public function getDataboxTotalLinks( $user_category_id )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('link_details', 'category_links.category_link_id=link_details.link_id',array('title','image','url_id'),'left');
		$select->where('category_links.user_category_id="'. $user_category_id .'"');
		$select->where('category_links.link_validity_status="1"');
		$select->order('link_details.url_id ASC');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
}