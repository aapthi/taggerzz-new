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

class UserDetailsTable
{
    protected $tableGateway;
	protected $select;

	protected $userCategoriesTg;
	protected $categoryLinksTg;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
		$this->userCategoriesTg = new TableGateway('user_categories', $this->tableGateway->getAdapter());
		$this->categoryLinksTg = new TableGateway('category_links', $this->tableGateway->getAdapter());
    }

	public function addUserDetails($user_id)
    {	
		$data = array(
			'user_id' 	     => $user_id, 
			'details_set' 	 => "1", 
			'status' 	     => "1",
			'montage_image'	 => $_SESSION['Zend_Auth']->photoURL
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }

	public function addEmailLogin( $user_id,$userDetails )
    {	
		$data = array(
			'user_id' 	     	=> $user_id, 
			'montage_image' 	=> $userDetails['image'],
			'details_set' 	 	=> "1", 
			'status' 	     	=> "1"	
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }

	public function checkDetailsRecorded($user_id)
    {
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array('countUser' => new \Zend\Db\Sql\Expression('COUNT(*)')));
		$select->where('user_id="'.$user_id.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();
		return $row;
	}
	
	public function checkDetailsSetStatus($user_id)
    {
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array( 'details_set' ));
		$select->where('user_id="'.$user_id.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();
		return $row;
	}

	public function updateDetails( $userDetails,$userId )
    {	
		$data = array(
			'mobile' 	          	=> $userDetails['mobile'],
			'montage_image' 		=> $userDetails['image'],
			'details_set' 	     	=> "1"
		);	
		$row=$this->tableGateway->update($data, array('user_id' => $userId));
		return $row;
	}
	public function addText( $userId,$montage )
    {
		if($montage['type']=='hash'){
			$data = array(
				'montage_hash_name' => $montage['text'],
			);	
		}else if($montage['type']=='title'){
			$data = array(
				'montage_title' => $montage['text'],
			);
		}else{
			$data = array(
				'montage_paragraph' => $montage['text'],
			);
		}
		$row=$this->tableGateway->update($data, array('user_id' => $userId));
		return $row;
	}
	public function addImage( $userId,$image )
    {
		$data = array(
			'montage_image' => $image,
		);
		$row=$this->tableGateway->update($data, array('user_id' => $userId));
		return $row;
	}
	public function addMontageMainImage( $userId,$image )
    {
		$data = array(
			'montage_main_image' => $image,
		);
		$row=$this->tableGateway->update($data, array('user_id' => $userId));
		return $row;
	}
	public function getHomeMontageBoxes( $boxesPerPage,$offset )
	{
		$catWiseLinksCountSqSelect = $this->categoryLinksTg->getSql()->select();
		$catWiseLinksCountSqSelect->columns(array('catWiseLinksCount1' => new Expression('COUNT(link)'),'clUserCatId1'=>'user_category_id','clLvs1'=>'link_validity_status'));
		$catWiseLinksCountSqSelect->group('clUserCatId1');
		$catWiseLinksCountSqSelect->group('clLvs1');
		$catWiseLinksCountSqSelect->having('clLvs1="1"');

		$userWisePdbListSqSelect = $this->userCategoriesTg->getSql()->select();
		$userWisePdbListSqSelect->columns(array('ucUserId1'=>'user_id','ucCatType'=>'category_type','ucCatId'=>'category_id','ucStatus'=>'STATUS'));
		$userWisePdbListSqSelect->join(array('catWiseLinksCountRj' => $catWiseLinksCountSqSelect), 'clUserCatId1=category_id',array('userWiseLinksCount1' => new Expression('SUM(catWiseLinksCount1)'),'clUserCatId'=>'clUserCatId1'),'right');
		$userWisePdbListSqSelect->group('ucUserId1');
		$userWisePdbListSqSelect->group('ucCatType');
		$userWisePdbListSqSelect->group('ucStatus');
		$userWisePdbListSqSelect->having('ucCatType="1"');
		$userWisePdbListSqSelect->having('ucStatus="1"');
		// $resultSet1 = $this->userCategoriesTg->selectWith($userWisePdbListSqSelect);
		// echo "<pre>;";print_r($resultSet1);exit;

		$select = $this->tableGateway->getSql()->select();
		$select->join('user', 'user_details.user_id=user.user_id',array('display_name','ustatus'=>'status','montageOrder'=>'montage_prior_order'),'left');
		$select->join(array('userPublicBoxesRj' => $userWisePdbListSqSelect), 'user_details.user_id=ucUserId1',array('userWiseLinksCount' => 'userWiseLinksCount1','ucUserId'=>'ucUserId1'),'right');
		//newly added query
		$select->where('user.status="1"');
		$select->limit(intval($boxesPerPage));
		$select->offset(intval($offset));
		$select->order('montageOrder ASC');
		$select->order('user_details.user_id DESC');
		$resultSet = $this->tableGateway->selectWith($select);
		// echo "<pre>";print_r($resultSet);exit;
		return $resultSet;
	}

	public function getUcUserMontageDetails( $userId )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->where('user_details.user_id="'. $userId .'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->current();
	}
	public function updateHintingState( $userId,$hintingNewVal )
    {	
		$data = array(
			'hinting_state' 	 => $hintingNewVal
		);	
		$row=$this->tableGateway->update($data, array('user_id' => $userId));
		return $row;
	}

}