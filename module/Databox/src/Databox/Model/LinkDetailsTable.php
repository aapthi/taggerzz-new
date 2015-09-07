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

class LinkDetailsTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }

	public function addLinkDetails( $category_link_id,$title,$image,$description,$author,$keywords,$urlId,$isVideo,$iframeSrc )
    {	
		$data = array(
			'link_id' 	     	=> $category_link_id, 
			'title' 	     	=> strip_tags($title), 	
			'image' 	     	=> $image, 	
			'description' 	    => strip_tags($description), 	
			'web_author' 	    => $author, 	
			'article_author' 	=> $author, 	
			'meta_content' 	    => $keywords, 	
			'url_id'	 	    => $urlId, 	
			'is_video'	 	    => $isVideo, 	
			'iframe_src'	 	=> $iframeSrc, 	
			'created_date' 	 	=> date('Y-m-d H:i:s'), 	
			'last_updated_date' => date('Y-m-d H:i:s'), 	
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }

	public function getDataboxGrid( $catId )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('category_links', 'link_details.link_id=category_links.category_link_id',array('*'),'left');	
		$select->join('user_categories', 'category_links.user_category_id=user_categories.category_id',array('*','boxPrivacy'=>'category_type'),'left');	
		$select->join('category', 'user_categories.category_id=category.category_id',array('*'),'left');	
		$select->where('category.category_id="'.$catId.'"');
		$select->where('category_links.link_validity_status="1"');
		$select->order('link_details.url_id asc');
		$resultSet = $this->tableGateway->selectWith($select);
		// echo "<pre>";print_r($resultSet);exit;
		return $resultSet;
	}

	public function getCatLinksMainsDetails( $catId )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('category_links', 'link_details.link_id=category_links.category_link_id',array('link','link_validity_status'),'left');	
		$select->where('category_links.user_category_id="'.$catId.'"');
		$select->order('link_details.url_id asc');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}

	public function getCategoryLinkTitles( $catId )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('category_links', 'link_details.link_id=category_links.category_link_id',array('user_category_id'),'left');	
		$select->where('category_links.user_category_id="'.$catId.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function getParagraph( $ld_id )
	{
		$select = $this->tableGateway->getSql()->select();	
		$select->where('link_details_id="'.$ld_id.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->current();
	}
	public function deleteLinkDetail( $linkInfo )
    {	
		$deleteStatus=$this->tableGateway->delete(array('link_id' => $linkInfo));
		return $deleteStatus;
	}
	public function gettotalHotLinksCount()
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('category_links', 'link_details.link_id=category_links.category_link_id',array('*'),'left');	
		$select->join('user_categories', 'category_links.user_category_id=user_categories.category_id',array('*','boxPrivacy'=>'category_type'),'left');
		$select->where('category_links.link_validity_status="1"');
		$select->where('user_categories.status="1"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = count($resultSet);
		return $row;
	}
}