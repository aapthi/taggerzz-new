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

class UserCategoriesTable 
{
    protected $tableGateway;
	protected $select;

	protected $rwvTg;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
		$this->rwvTg = new TableGateway('relevance_worth_vote', $this->tableGateway->getAdapter());
    }

	public function addUserCategory( $userCatDetails,$categoryHighlight )
    {

		$userId = 0;
		if( isset($_SESSION['usersinfo']) )
		{
			$userId = $_SESSION['usersinfo']->userId;
		}
		if( $categoryHighlight == 1 )
		{
			$data = array(
				'category_id' 	 => $userCatDetails["category_id"], 
				'user_id' 	     => $userId, 
				'category_title' => $userCatDetails["categoryTitle"], 
				'user_classification'  => $userCatDetails["description123"], 
				'user_hashname'  => $userCatDetails["catHashTag"], 
				'category_type'  => $userCatDetails["categoryType"], 
				'secret_code'  	 => $userCatDetails["uniqueCode"], 
				'meta_tags' 	 => $userCatDetails["metaTags"], 
				'hash_note' 	 => $userCatDetails["hashNote"], 
				'setting_id' 	 => $userCatDetails["settingId"], 
				'created_date' 	 => date('Y-m-d H:i:s'), 	
				'updated_date' 	 => date('Y-m-d H:i:s'), 	
				'status' 	     => "1",
				'databoxes_prior_order' 		=> "1500",
				'mature_content' 				=> $userCatDetails["matureContent"], 
				'not_safe_for_work' 			=> $userCatDetails["notSafeForWork"],
				'link_post_formation' 			=> $userCatDetails["linkPostFormation"],
				'main_cat_tittle' 				=> $userCatDetails["main_category"],
				'sub_cat_tittle' 				=> $userCatDetails["sub_category"]
			);	
		}
		else
		{
			$data = array(
				'category_id' 	 => $userCatDetails["category_id"], 
				'user_id' 	     => $userId, 
				'category_title' => $userCatDetails["categoryTitle"],
				'user_classification'  => $userCatDetails["description123"],
				'user_hashname'  => $userCatDetails["catHashTag"], 
				'category_type'  => $userCatDetails["categoryType"], 
				'secret_code'  	 => $userCatDetails["uniqueCode"], 
				'meta_tags' 	 => $userCatDetails["metaTags"], 
				'hash_note' 	 => $userCatDetails["hashNote"], 
				'setting_id' 	 => $userCatDetails["settingId"], 
				'created_date' 	 => date('Y-m-d H:i:s'), 	
				'updated_date' 	 => date('Y-m-d H:i:s'), 	
				'status' 	     => "1",
				'highlights_prior_order' => "1500",
			);	
		}
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }

	public function checkPublicHashtag( $publicHashtag )
    {
		$catPrivacy = 1;
		$select = $this->tableGateway->getSql()->select();
		$select->where( 'user_hashname="'. $publicHashtag .'"' );
		$select->where( 'category_type="'. $catPrivacy .'"' );
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->count();
    }

	public function checkPrivateCode( $uniqueCode )
    {
		$ucStatus = 1;
		$select = $this->tableGateway->getSql()->select();
		$select->where( 'secret_code="'. $uniqueCode .'"' );
		$select->where( 'status="'. $ucStatus .'"' );
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
    }

	public function getDataboxes( $userId ){
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_name','category_image'),'left');
		$select->join('category_links', 'category.category_id=category_links.user_category_id',array('link','link_validity_status'),'left');
		$select->join('link_details', 'category_links.category_link_id=link_details.link_id',array('title','image','description','web_author','meta_content','url_id','is_video','iframe_src'),'left');
		$select->where('user_categories.user_id="'.$userId.'"');
		$select->where('category.category_highlight="1"');
		$select->where('user_categories.status="1"');
		$select->order('user_categories.category_id ASC');
		$select->order('link_details.url_id ASC');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function getPrivateDataboxCount($userId){
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_name','category_image'),'left');
		$select->where('user_categories.user_id="'.$userId.'"');
		$select->where('user_categories.category_type="0"');
		$select->where('user_categories.status="1"');
		$select->where('category.category_highlight="1"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function getPublicDataboxCount($userId){
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_name','category_image'),'left');
		$select->where('user_categories.user_id="'.$userId.'"');
		$select->where('user_categories.category_type="1"');
		$select->where('user_categories.status="1"');
		$select->where('category.category_highlight="1"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function getHighlightDataboxCount($userId){
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_name','category_image'),'left');
		$select->where('user_categories.user_id="'.$userId.'"');
		$select->where('user_categories.category_type="0"');
		$select->where('user_categories.status="1"');
		$select->where('category.category_highlight="2"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function getTotalLinks( $userId ){
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_name','category_image'),'left');
		$select->join('category_links', 'user_categories.category_id=category_links.user_category_id',array('link'),'left');
		$select->join('link_details', 'category_links.category_link_id=link_details.link_id',array('title','image'),'left');
		$select->where('category_links.link_validity_status="1"');
		$select->where('user_categories.user_id="'.$userId.'"');
		//$select->where('category.category_highlight="1"');
		$select->where('user_categories.status="1"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function updateInformation($data,$categoryId)
    {
		if(isset($data['private-'.$categoryId])){
			if($data['private-'.$categoryId]=='Public'){
				$value=1;
				$dataat = array(	
					'category_type' 	       	=> 	$value,
					'user_hashname' 	       	=> 	$data['user_hashname'.$categoryId],				
					'meta_tags'	   				=> 	$data['meta_tags'.$categoryId],			  	
					'category_title' 	       	=> 	$data['category_title'.$categoryId],		
					'status'					=>	1,
					'updated_date'				=>	date('Y-m-d H:i:s'), 
				);
			}else if($data['private-'.$categoryId]=='Private'){
				$value=0;
				$dataat = array(	
					'category_type' 	       	=> 	$value,
					'secret_code'	      	 	=> 	$data['secret_code'.$categoryId],
					'user_hashname' 	       	=> 	$data['user_hashname'.$categoryId],
					'meta_tags'	   				=> 	"",					
					'category_title' 	       	=> 	$data['category_title'.$categoryId],			
					'status'					=>	1,
					'updated_date'				=>	date('Y-m-d H:i:s'),
				);
			}
			$update=$this->tableGateway->update($dataat, array('user_category_id' => $data['user_category_id-'.$categoryId]));
		}else{
			if(isset($data['secret_code'.$categoryId])){
				$dataa = array(	
					'user_hashname' 	       	=> 	$data['user_hashname'.$categoryId],				
					'category_title' 	       	=> 	$data['category_title'.$categoryId],
					'secret_code'	      	 	=> 	$data['secret_code'.$categoryId],
					'meta_tags'	   				=> 	"",					
					'status'					=>	1,
					'updated_date'				=>	date('Y-m-d H:i:s'), 
				);
			}else{
				$dataa = array(	
					'user_hashname' 	       	=> 	$data['user_hashname'.$categoryId],				
					'meta_tags'	   				=> 	$data['meta_tags'.$categoryId],			  	
					'category_title' 	       	=> 	$data['category_title'.$categoryId],		
					'status'					=>	1,
					'updated_date'				=>	date('Y-m-d H:i:s'), 
				);
			}	
			$update=$this->tableGateway->update($dataa, array('user_category_id' => $data['user_category_id-'.$categoryId]));
		}
		return $update;
    }
	public function getMontages( $userId ){
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_name','category_image'),'left');
		$select->join('category_links', 'user_categories.category_id=category_links.user_category_id',array('link'),'left');
		$select->join('link_details', 'category_links.category_link_id=link_details.link_id',array('title','image','url_id'),'left');
		$select->where('user_categories.user_id="'.$userId.'"');
		$select->where('category.category_highlight="1"');
		$select->where('category_links.link_validity_status="1"');
		$select->where('user_categories.status="1"');
		$select->order('user_categories.category_id ASC');
		$select->order('link_details.url_id ASC');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	public function getUserHighlights( $userId )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('status'),'left');
		$select->join('relevance_worth_vote',new Expression( 'user_categories.category_id=relevance_worth_vote.category_id AND relevance_worth_vote.voteUp=1'),array('totalVoteUp'=>new Expression('COUNT(voteUp)')),'left');
		$select->where('user_categories.user_id="'. $userId .'"');
		$select->where('user_categories.status!="2"');
		$select->where('category.category_highlight="2"');
		$select->group('user_categories.category_id');
		$select->order('totalVoteUp DESC');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}

	
	
	public function deleteHighlight( $category_id )
	{
		$data = array(
			'status' => "2"
		);	
		$row=$this->tableGateway->update($data, array('category_id' => $category_id));
		return $row;
	}

	public function deleteDataboxe( $userCtegoryId ){
		$data = array(
			'status' => "2"
		);	
		$row=$this->tableGateway->update($data, array('user_category_id' => $userCtegoryId));
		return $row;
	}

	public function removeDatabox( $categoryId ){
		$data = array(
			'status' => "2"
		);	
		$row=$this->tableGateway->update($data, array('category_id' => $categoryId));
		return $row;
	}

	public function hideDatabox( $categoryId,$newStatus )
	{
		$data = array(
			'status' => $newStatus
		);	
		$row=$this->tableGateway->update($data, array('category_id' => $categoryId));
		return $row;
	}

	public function deleteDataboxeUser( $userId,$status ){
		$data = array(
			'status' => $status
		);	
		$row=$this->tableGateway->update($data, array('user_id' => $userId));
		return $row;
	}
	public function getEditHighlight( $category_id )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('status','category_image','category_name'),'left');
		$select->where('category.category_id="'. $category_id .'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->current();
	}
	
	public function updateHighlight( $userCatDetails )
	{
		$data = array(
			'category_title' => $userCatDetails["categoryTitle"], 
			'user_hashname'  => $userCatDetails["catHashTag"], 
			'meta_tags' 	 => $userCatDetails["metaTags"], 
			'setting_id' 	 => $userCatDetails["settingId"], 
			'updated_date' 	 => date('Y-m-d H:i:s')
		);	
		$result=$this->tableGateway->update($data, array('category_id' => $userCatDetails['categoryId']));
		return 	$result;
	}
	
	public function getAllKeywords()
    {
		$userId=$_SESSION['usersinfo']->userId;
		$categoryType = 1;
		
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_image','category_highlight'),'left');
		$select->columns(array('userId' => 'user_id','category_type' => 'category_type','status'=>'status','allUserMetaTags' => new \Zend\Db\Sql\Expression('group_concat(meta_tags)')));
		$select->where('user_categories.status="1"');
		$select->where('category.category_highlight="1"');
		$select->group('user_categories.user_id');
		$select->group('user_categories.category_type');
		$select->having('user_categories.user_id="'.$userId.'"');
		$select->having('user_categories.category_type="'.$categoryType.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet;
		return $row;
	}

	public function getHomePublicBoxes( $boxesPerPage,$offset,$filterType)
	{
		$categoryType = 1;
		$rw_lh = 1;
		$notmature_safe = 0;
		if(isset($_SESSION['usersinfo']->userId)){
			$user_id=$_SESSION['usersinfo']->userId;
		}else{
			$user_id=$_SERVER['REMOTE_ADDR'];
		}
		$votesGroupSubQuerySelect = $this->rwvTg->getSql()->select();
		$votesGroupSubQuerySelect->columns(array('NetVotes1' => new Expression('COALESCE((SUM(voteUp)/SUM(rw_lh)*100),0)'),'vupCatId'=>'category_id','voteUp1'=>new Expression('COALESCE(SUM(voteUp),0)'),'voteDownn1'=>new Expression('COALESCE(SUM(voteDown),0)'),'rw_lh1'=>new Expression('COALESCE(SUM(rw_lh),0)')));
		$votesGroupSubQuerySelect->group('vupCatId');
		$votesGroupSubQuerySelect->where('relevance_worth_vote.rw_lh="1"');
		$votesGroupSubQuerySelect2 = $this->rwvTg->getSql()->select();
		$votesGroupSubQuerySelect2->columns(array('userVoteUp1' =>'voteUp','uservupCatId'=>'category_id','uservoteDown1'=>'voteDown','userVoteId1'=>'user_id'));
		$votesGroupSubQuerySelect2->where('relevance_worth_vote.rw_lh="1"');
		$votesGroupSubQuerySelect2->where('relevance_worth_vote.user_id="'.$user_id.'"');
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_image','category_highlight','settingId'=>'category_type'),'left');
		//newly added
		//$select->join('user', 'user_categories.user_id=user.user_id',array('ustatus'=>'status'),'left');
		$select->join('user', 'user_categories.user_id=user.user_id',array('display_name','ustatus'=>'status'),'left');
		$select->join('user_details', 'user_details.user_id=user.user_id',array('image','montage_image'),'left');
		$select->join('databox_views', 'user_categories.category_id=databox_views.category_id',array('views_count'),'left');
		$select->join(array('rwvlf' => $votesGroupSubQuerySelect), 'user_categories.category_id=vupCatId',array('likes' => new Expression('COALESCE(NetVotes1,0)'),'vupCatId1'=>'vupCatId','voteUp'=>new Expression('COALESCE(voteUp1,0)'),'voteDown'=>new Expression('COALESCE(voteDownn1,0)'),'rw_lh'=>new Expression('COALESCE(rw_lh1,0)')),'left');
		$select->join(array('rwvlf1' => $votesGroupSubQuerySelect2), 'user_categories.category_id=uservupCatId',array('userVoteUp' => 'userVoteUp1','uservupCatId1'=>'uservupCatId','uservoteDown'=>'uservoteDown1','userVoteId'=>'userVoteId1'),'left');
		// newly added end
		$select->where( 'user_categories.category_type="'. $categoryType .'"'  );
		$select->where('user_categories.status="1"');
		$select->where('category.category_highlight="1"');
		$select->where('user.status="1"');
		if($filterType==4){
			$select->where('user.user_id="'.$user_id.'"');
		}
		if($filterType==2){
		$date = date('Y-m-d H:i:s');			
		$newdate = strtotime ( '-3 day' , strtotime ( $date ) ) ;
		$newdate = date ( 'Y-m-d H:i:s' , $newdate );
			$select->where('user_categories.created_date >= "'.$newdate.'"');
		}
		$select->limit(intval($boxesPerPage));
		$select->offset(intval($offset));
		if($filterType==3){
			$select->order('views_count DESC');
			$select->order('likes DESC');
		}
		$select->order('user_categories.databoxes_prior_order ASC');
		$select->order('category.category_id DESC');
		$resultSet = $this->tableGateway->selectWith($select);

		return $resultSet;
	}
	//newly added code for filteration
	public function getHomePublicBoxesForFilters( $boxesPerPage,$offset,$filterType )
	{
		$categoryType = 1;
		$rw_lh = 1;
		$notmature_safe = 0;
		if(isset($_SESSION['usersinfo']->userId)){
			$user_id=$_SESSION['usersinfo']->userId;
		}else{
			$user_id=$_SERVER['REMOTE_ADDR'];
		}
		$votesGroupSubQuerySelect = $this->rwvTg->getSql()->select();
		$votesGroupSubQuerySelect->columns(array('NetVotes1' => new Expression('COALESCE((SUM(voteUp)/SUM(rw_lh)*100),0)'),'vupCatId'=>'category_id','voteUp1'=>new Expression('COALESCE(SUM(voteUp),0)'),'voteDownn1'=>new Expression('COALESCE(SUM(voteDown),0)'),'rw_lh1'=>new Expression('COALESCE(SUM(rw_lh),0)')));
		$votesGroupSubQuerySelect->group('vupCatId');
		$votesGroupSubQuerySelect->where('relevance_worth_vote.rw_lh="1"');
		$votesGroupSubQuerySelect2 = $this->rwvTg->getSql()->select();
		$votesGroupSubQuerySelect2->columns(array('userVoteUp1' =>'voteUp','uservupCatId'=>'category_id','uservoteDown1'=>'voteDown','userVoteId1'=>'user_id'));
		$votesGroupSubQuerySelect2->where('relevance_worth_vote.rw_lh="1"');
		$votesGroupSubQuerySelect2->where('relevance_worth_vote.user_id="'.$user_id.'"');
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_image','category_highlight','settingId'=>'category_type'),'left');
		//newly added
		//$select->join('user', 'user_categories.user_id=user.user_id',array('ustatus'=>'status'),'left');
		$select->join('user', 'user_categories.user_id=user.user_id',array('display_name','ustatus'=>'status'),'left');
		$select->join('user_details', 'user_details.user_id=user.user_id',array('image','montage_image'),'left');
		$select->join('databox_views', 'user_categories.category_id=databox_views.category_id',array('views_count'),'left');
		$select->join(array('rwvlf' => $votesGroupSubQuerySelect), 'user_categories.category_id=vupCatId',array('likes' => new Expression('COALESCE(NetVotes1,0)'),'vupCatId1'=>'vupCatId','voteUp'=>new Expression('COALESCE(voteUp1,0)'),'voteDown'=>new Expression('COALESCE(voteDownn1,0)'),'rw_lh'=>new Expression('COALESCE(rw_lh1,0)')),'left');
		$select->join(array('rwvlf1' => $votesGroupSubQuerySelect2), 'user_categories.category_id=uservupCatId',array('userVoteUp' => 'userVoteUp1','uservupCatId1'=>'uservupCatId','uservoteDown'=>'uservoteDown1','userVoteId'=>'userVoteId1'),'left');
		// newly added end
		$select->where( 'user_categories.category_type="'. $categoryType .'"'  );
		$select->where('user_categories.status="1"');
		$select->where('category.category_highlight="1"');
		$select->where('user.status="1"');
		if($filterType==4){
			$select->where('user.user_id="'.$user_id.'"');
		}
		if($filterType==2){
		$date = date('Y-m-d H:i:s');			
		$newdate = strtotime ( '-3 day' , strtotime ( $date ) ) ;
		$newdate = date ( 'Y-m-d H:i:s' , $newdate );
			$select->where('user_categories.created_date >= "'.$newdate.'"');
		}
		$select->limit(intval($boxesPerPage));
		$select->offset(intval($offset));
		if($filterType==3){
			$select->order('views_count DESC');
			$select->order('likes DESC');
		}
		$select->order('user_categories.databoxes_prior_order ASC');
		$select->order('category.category_id DESC');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}

	//End
	public function getHomeHighlightBoxes( $boxesPerPage,$offset )
	{
		$categoryHighlight = 2;
		if(isset($_SESSION['usersinfo']->userId)){
			$user_id=$_SESSION['usersinfo']->userId;
		}else{
			$user_id=$_SERVER['REMOTE_ADDR'];
		}
		
		// $rwvTg = new TableGateway('relevance_worth_vote', $this->tableGateway->getAdapter());
		
		/*$votesGroupSubQuerySelect = $this->rwvTg->getSql()->select();
		$votesGroupSubQuerySelect->columns(array('NetVotes1' => new Expression('COALESCE(SUM(voteUp)-SUM(voteDown),0)'),'vupCatId'=>'category_id'));
		$votesGroupSubQuerySelect->group('vupCatId');*/
		$votesGroupSubQuerySelect = $this->rwvTg->getSql()->select();
		$votesGroupSubQuerySelect->columns(array('NetVotes1' => new Expression('COALESCE((SUM(voteUp)/SUM(rw_lh)*100),0)'),'vupCatId'=>'category_id','voteUp1'=>new Expression('COALESCE(SUM(voteUp),0)'),'voteDown1'=>new Expression('COALESCE(SUM(voteDown),0)'),'rw_lh1'=>new Expression('COALESCE(SUM(rw_lh),0)')));
		$votesGroupSubQuerySelect->group('vupCatId');
		$votesGroupSubQuerySelect->where('relevance_worth_vote.rw_lh="1"');
		$votesGroupSubQuerySelect2 = $this->rwvTg->getSql()->select();
		$votesGroupSubQuerySelect2->columns(array('userVoteUp1' =>'voteUp','uservupCatId'=>'category_id','uservoteDown1'=>'voteDown','userVoteId1'=>'user_id'));
		$votesGroupSubQuerySelect2->where('relevance_worth_vote.rw_lh="1"');
		$votesGroupSubQuerySelect2->where('relevance_worth_vote.user_id="'.$user_id.'"');
		
		// echo "<pre>";print_r($votesGroupSubQuerySelect2);exit;
		// $resultSet1 = $rwvTg->selectWith($votesGroupSubQuerySelect);
		// echo "<pre>";print_r($resultSet1);exit;
	
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_image','category_highlight','settingId'=>'category_type'),'left');
		$select->join(array('rwvlf' => $votesGroupSubQuerySelect), 'user_categories.category_id=vupCatId',array('NetVotes' => new Expression('COALESCE(NetVotes1,0)'),'vupCatId1'=>'vupCatId','voteUp'=>'voteUp1','voteDown'=>'voteDown1','rw_lh'=>'rw_lh1'),'left');
		$select->join(array('rwvlf1' => $votesGroupSubQuerySelect2), 'user_categories.category_id=uservupCatId',array('userVoteUp' => 'userVoteUp1','uservupCatId1'=>'uservupCatId','uservoteDown'=>'uservoteDown1','userVoteId'=>'userVoteId1'),'left');
		//newly added query
		//$select->join('user', 'user_categories.user_id=user.user_id',array('ustatus'=>'status'),'left');
		$select->join('user', 'user_categories.user_id=user.user_id',array('display_name','ustatus'=>'status'),'left');
		$select->join('user_details', 'user_details.user_id=user.user_id',array('image','montage_image'),'left');
		$select->join('databox_views', 'user_categories.category_id=databox_views.category_id',array('views_count'),'left');
		//end the query
		$select->where( 'category.category_highlight="'. $categoryHighlight .'"' );
		$select->where('user_categories.status="1"');
		$select->where('user.status="1"');
		// $select->limit(intval($boxesPerPage));
		// $select->offset(intval($offset));
		$select->order('views_count DESC');
		$select->order('highlights_prior_order asc');
		$select->order('NetVotes desc');
		$select->order('category.category_id desc');
		$resultSet = $this->tableGateway->selectWith($select);
		// echo "<pre>";print_r($resultSet);exit;
		return $resultSet;
	}

	public function getSearchPublicBoxes( $searchTermHolder )
	{
		$categoryType = 1;
		$notmature_safe = 0;
		$hashTerm = "#";
		if(isset($_SESSION['usersinfo']->userId)){
			$user_id=$_SESSION['usersinfo']->userId;
		}else{
			$user_id=$_SERVER['REMOTE_ADDR'];
		}
		//newly added
		$votesGroupSubQuerySelect = $this->rwvTg->getSql()->select();
		$votesGroupSubQuerySelect->columns(array('NetVotes1' => new Expression('COALESCE((SUM(voteUp)/SUM(rw_lh)*100),0)'),'vupCatId'=>'category_id','voteUp1'=>'voteUp','rw_lh1'=>'rw_lh'));
		$votesGroupSubQuerySelect->group('vupCatId');
		$votesGroupSubQuerySelect->where('relevance_worth_vote.rw_lh="1"');
		$votesGroupSubQuerySelect2 = $this->rwvTg->getSql()->select();
		$votesGroupSubQuerySelect2->columns(array('userVoteUp1' =>'voteUp','uservupCatId'=>'category_id','uservoteDown1'=>'voteDown','userVoteId1'=>'user_id'));
		$votesGroupSubQuerySelect2->where('relevance_worth_vote.rw_lh="1"');
		$votesGroupSubQuerySelect2->where('relevance_worth_vote.user_id="'.$user_id.'"');
		//end
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_image','category_highlight','settingId'=>'category_type'),'left');
		//$select->join('user', 'user_categories.user_id=user.user_id',array('ustatus'=>'status'),'left');
		//NEWLY ADDED
		$select->join('user', 'user_categories.user_id=user.user_id',array('display_name','ustatus'=>'status'),'left');
		$select->join('user_details', 'user_details.user_id=user.user_id',array('image','montage_image'),'left');
		$select->join('databox_views', 'user_categories.category_id=databox_views.category_id',array('views_count'),'left');
		$select->join(array('rwvlf' => $votesGroupSubQuerySelect), 'user_categories.category_id=vupCatId',array('likes' => new Expression('COALESCE(NetVotes1,0)'),'vupCatId1'=>'vupCatId','voteUp'=>new Expression('COALESCE(voteUp1,0)'),'rw_lh'=>new Expression('COALESCE(rw_lh1,0)')),'left');
		$select->join(array('rwvlf1' => $votesGroupSubQuerySelect2), 'user_categories.category_id=uservupCatId',array('userVoteUp' => 'userVoteUp1','uservupCatId1'=>'uservupCatId','uservoteDown'=>'uservoteDown1','userVoteId'=>'userVoteId1'),'left');
		//END
		$select->where( 'user_categories.category_type="'. $categoryType .'"'  );
		$select->where('user_categories.status="1"');
		$select->where('category.category_highlight="1"');
		$select->where('user.status="1"');
		$select->where->like( 'user_categories.user_hashname', '%' . $searchTermHolder . '%' );
		$select->where->like( 'user_categories.user_hashname', $hashTerm . '%' );
		$resultSet = $this->tableGateway->selectWith($select);
		if( $resultSet->count() > 0 )
		{
			return $resultSet;
		}
		else
		{
			$newSearchTerm = substr($searchTermHolder,1,(strlen($searchTermHolder)-1));
			$select1 = $this->tableGateway->getSql()->select();
			$select1->join('category', 'user_categories.category_id=category.category_id',array('category_image','category_highlight','settingId'=>'category_type'),'left');
			$select1->join('user', 'user_categories.user_id=user.user_id',array('ustatus'=>'status'),'left');
			$select1->where( 'user_categories.category_type="'. $categoryType .'"'  );
			$select1->where('user_categories.status="1"');
			$select->where('category.category_highlight="1"');
			$select1->where('user.status="1"');
			$select1->where->like( 'user_categories.user_hashname', '%' . $newSearchTerm . '%' );
			$resultSet1 = $this->tableGateway->selectWith($select1);
			return $resultSet1;
		}
	}

	public function getSearchPrivateBox( $searchTermHolder,$pvtUniqueCodeHolder )
	{
		$notmature_safe = 0;
		if(isset($_SESSION['usersinfo']->userId)){
			$user_id=$_SESSION['usersinfo']->userId;
		}else{
			$user_id=$_SERVER['REMOTE_ADDR'];
		}
		//newly added
		$votesGroupSubQuerySelect = $this->rwvTg->getSql()->select();
		$votesGroupSubQuerySelect->columns(array('NetVotes1' => new Expression('COALESCE((SUM(voteUp)/SUM(rw_lh)*100),0)'),'vupCatId'=>'category_id','voteUp1'=>'voteUp','rw_lh1'=>'rw_lh'));
		$votesGroupSubQuerySelect->group('vupCatId');
		$votesGroupSubQuerySelect->where('relevance_worth_vote.rw_lh="1"');
		$votesGroupSubQuerySelect2 = $this->rwvTg->getSql()->select();
		$votesGroupSubQuerySelect2->columns(array('userVoteUp1' =>'voteUp','uservupCatId'=>'category_id','uservoteDown1'=>'voteDown','userVoteId1'=>'user_id'));
		$votesGroupSubQuerySelect2->where('relevance_worth_vote.rw_lh="1"');
		$votesGroupSubQuerySelect2->where('relevance_worth_vote.user_id="'.$user_id.'"');
		//end
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_image','category_highlight','settingId'=>'category_type'),'left');
		$select->join(array('rwvlf' => $votesGroupSubQuerySelect), 'user_categories.category_id=vupCatId',array('likes' => new Expression('COALESCE(NetVotes1,0)'),'vupCatId1'=>'vupCatId','voteUp'=>new Expression('COALESCE(voteUp1,0)'),'rw_lh'=>new Expression('COALESCE(rw_lh1,0)')),'left');
		$select->join(array('rwvlf1' => $votesGroupSubQuerySelect2), 'user_categories.category_id=uservupCatId',array('userVoteUp' => 'userVoteUp1','uservupCatId1'=>'uservupCatId','uservoteDown'=>'uservoteDown1','userVoteId'=>'userVoteId1'),'left');
		//$select->join('user', 'user_categories.user_id=user.user_id',array('ustatus'=>'status'),'left');
		//NEWLY ADDED
		$select->join('user', 'user_categories.user_id=user.user_id',array('display_name','ustatus'=>'status'),'left');
		$select->join('user_details', 'user_details.user_id=user.user_id',array('image','montage_image'),'left');
		$select->join('databox_views', 'user_categories.category_id=databox_views.category_id',array('views_count'),'left');
		$select->where('user_categories.status="1"');
		$select->where('user.status="1"');
		$select->where( 'user_categories.user_hashname="'. $searchTermHolder .'"'  );
		$select->where( 'user_categories.secret_code="'. $pvtUniqueCodeHolder .'"'  );
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	public function getDataboxAndHighlights( $userId )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_name','category_highlight','settingId'=>'category_type'),'left');
		$select->where('user_categories.user_id="'.$userId.'"');
		$select->where('user_categories.status!="2"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}

	public function getUserDataboxesCount( $userId )
	{
		$categoryHighlight = 1;
	
		$select = $this->tableGateway->getSql()->select();
		$select->columns( array( 'userDataboxesCount' => new Expression('COUNT(user_id)') ) );
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_name','category_highlight'),'left');
		$select->where('user_categories.user_id="' . $userId . '"');
		$select->where('user_categories.status="1"');
		$select->where('category.category_highlight="' . $categoryHighlight . '"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->current();
	}	
	public function getMemberCollections(){
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_name','category_image'),'left');
		$select->join('category_links', 'user_categories.category_id=category_links.user_category_id',array('link'),'left');
		$select->join('link_details', 'category_links.category_link_id=link_details.link_id',array('title','image'),'left');
		$select->join('user', 'user_categories.user_id=user.user_id',array('ustatus'=>'status'),'left');
		$select->where('category.category_highlight="1"');
		$select->where('user_categories.status="1"');
		$select->where('user_categories.category_type="1"');
		$select->where('user.status="1"');
		$select->order('user_categories.category_id asc');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	public function getAllUsersHighlights()
	{
		$categoryHighlight = 2;
		
		$rwvTg = new TableGateway('relevance_worth_vote', $this->tableGateway->getAdapter());
		
		$votesGroupSubQuerySelect = $rwvTg->getSql()->select();
		$votesGroupSubQuerySelect->columns(array('NetVotes1' => new Expression('COALESCE(SUM(voteUp)-SUM(voteDown),0)'),'vupCatId'=>'category_id'));
		$votesGroupSubQuerySelect->group('vupCatId');
		// echo "<pre>";print_r($votesGroupSubQuerySelect);exit;
		// $resultSet1 = $rwvTg->selectWith($votesGroupSubQuerySelect);
		// echo "<pre>";print_r($resultSet1);exit;
	
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_image','category_highlight'),'left');
		$select->join(array('rwvlf' => $votesGroupSubQuerySelect), 'user_categories.category_id=vupCatId',array('NetVotes' => new Expression('COALESCE(NetVotes1,0)'),'vupCatId1'=>'vupCatId'),'left');
		$select->join('user', 'user_categories.user_id=user.user_id',array('ustatus'=>'status'),'left');
		$select->where( 'category.category_highlight="'. $categoryHighlight .'"' );
		$select->where('user_categories.status="1"');
		$select->where('user.status="1"');
		$select->order('highlights_prior_order asc');
		$select->order('NetVotes desc');
		$select->order('category.category_id desc');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}

	public function getSearchedHighlights( $hgltsSearchTerm )
	{
		$hashTerm = "#";

		$categoryHighlight = 2;
		
		$votesGroupSubQuerySelect = $this->rwvTg->getSql()->select();
		$votesGroupSubQuerySelect->columns(array('NetVotes1' => new Expression('COALESCE(SUM(voteUp)-SUM(voteDown),0)'),'vupCatId'=>'category_id'));
		$votesGroupSubQuerySelect->group('vupCatId');
	
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_image','category_highlight'),'left');
		$select->join(array('rwvlf' => $votesGroupSubQuerySelect), 'user_categories.category_id=vupCatId',array('NetVotes' => new Expression('COALESCE(NetVotes1,0)'),'vupCatId1'=>'vupCatId'),'left');
		$select->join('user', 'user_categories.user_id=user.user_id',array('ustatus'=>'status'),'left');
		$select->where( 'category.category_highlight="'. $categoryHighlight .'"' );
		$select->where('user_categories.status="1"');
		$select->where('user.status="1"');
		$select->where->like( 'user_categories.user_hashname', '%' . $hgltsSearchTerm . '%' );
		$select->where->like( 'user_categories.user_hashname', $hashTerm . '%' );
		$select->order('highlights_prior_order asc');
		$select->order('NetVotes desc');
		$select->order('category.category_id desc');
		$resultSet = $this->tableGateway->selectWith($select);
		if( $resultSet->count() > 0 )
		{
			return $resultSet;
		}
		else
		{
			$newSearchTerm = substr($hgltsSearchTerm,1,(strlen($hgltsSearchTerm)-1));
			$select1 = $this->tableGateway->getSql()->select();
			$select1->join('category', 'user_categories.category_id=category.category_id',array('category_image','category_highlight'),'left');
			$select1->join(array('rwvlf' => $votesGroupSubQuerySelect), 'user_categories.category_id=vupCatId',array('NetVotes' => new Expression('COALESCE(NetVotes1,0)'),'vupCatId1'=>'vupCatId'),'left');
			$select1->join('user', 'user_categories.user_id=user.user_id',array('ustatus'=>'status'),'left');
			$select1->where( 'category.category_highlight="'. $categoryHighlight .'"' );
			$select1->where('user_categories.status="1"');
			$select1->where('user.status="1"');
			$select1->where->like( 'user_categories.user_hashname', '%' . $newSearchTerm . '%' );
			$select1->order('highlights_prior_order asc');
			$select1->order('NetVotes desc');
			$select1->order('category.category_id desc');
			$resultSet1 = $this->tableGateway->selectWith($select1);
			return $resultSet1;
		}
	}

	public function getsearchHashNames( $value )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_highlight'),'left');
		$select->join('user', 'user_categories.user_id=user.user_id',array('status'),'left');
		$select->where('user_categories.category_type="1"');
		$select->where('user_categories.status="1"');
		$select->where('user_categories.mature_content="0"');
		$select->where('user_categories.not_safe_for_work="0"');
		$select->where('category.category_highlight="1"');
		$select->where('user.status="1"');
		$select->where->like( 'user_categories.user_hashname', '%' . $value . '%' );
		$select->where->like( 'user_categories.user_hashname', '#%' );
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function getsearchHighHashNames( $value )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_highlight'),'left');
		$select->join('user', 'user_categories.user_id=user.user_id',array('status'),'left');
		$select->where('user_categories.status="1"');
		$select->where('category.category_highlight="2"');
		$select->where('user.status="1"');
		$select->where->like( 'user_categories.user_hashname', '%' . $value . '%' );
		$select->where->like( 'user_categories.user_hashname', '#%' );
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}

	public function setDataboxBoxPriorOrder( $category_id,$boxPriorOrder,$carryingCatId )
	{
		if( $carryingCatId > 0 )
		{
			$data1 = array(
				'databoxes_prior_order' => 1500,
				'updated_date' 	 		=> date('Y-m-d H:i:s') 	
			);	
			$row1=$this->tableGateway->update($data1, array('category_id' => $carryingCatId));
		}

		$data = array(
			'databoxes_prior_order' => $boxPriorOrder,
			'updated_date' 	 		=> date('Y-m-d H:i:s') 	
		);	
		$row=$this->tableGateway->update($data, array('category_id' => $category_id));
		return $row;
	}

	public function setHighlightBoxPriorOrder( $category_id,$boxPriorOrder,$carryingCatId )
	{
		if( $carryingCatId > 0 )
		{
			$data1 = array(
				'highlights_prior_order' => 1500,
				'updated_date' 	 		=> date('Y-m-d H:i:s') 	
			);	
			$row1=$this->tableGateway->update($data1, array('category_id' => $carryingCatId));
		}

		$data = array(
			'highlights_prior_order' => $boxPriorOrder,
			'updated_date' 	 		=> date('Y-m-d H:i:s') 	
		);	
		$row=$this->tableGateway->update($data, array('category_id' => $category_id));
		return $row;
	}

	public function checkPriorOrderExists( $categoryId,$boxPriorOrder,$boxType )
    {
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_name'),'left');
		$select->where('category.category_highlight="' . $boxType . '"');
		if( $boxType == 1 )
		{
			$select->where( 'databoxes_prior_order="'. $boxPriorOrder .'"' );
		}
		else
		{
			$select->where( 'highlights_prior_order="'. $boxPriorOrder .'"' );
		}
		$select->where( 'user_categories.category_id!="'. $categoryId .'"' );
		$resultSet = $this->tableGateway->selectWith($select);
		// echo "<pre>";print_r($resultSet);exit;
		return $resultSet;
    }

	public function getHomeUserCollection( $userId )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_name','category_image'),'left');
		$select->join('category_links', 'user_categories.category_id=category_links.user_category_id',array('link'),'left');
		$select->join('link_details', 'category_links.category_link_id=link_details.link_id',array('title','image'),'left');
		$select->join('user', 'user_categories.user_id=user.user_id',array('ustatus'=>'status'),'left');
		$select->where('category.category_highlight="1"');
		$select->where('category_links.link_validity_status="1"');
		$select->where('user_categories.user_id="' . $userId . '"');
		$select->where('user_categories.status="1"');
		$select->where('user_categories.category_type="1"');
		if( ! isset($_SESSION['usersinfo']->userId) )
		{
			$select->where('user_categories.mature_content="0"');
			$select->where('user_categories.not_safe_for_work="0"');
		}
		$select->where('user.status="1"');
		$select->order('user_categories.category_id asc');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}

	public function updateCatDescription( $category_id,$catDesc )
	{
		$data = array(
			'category_description' => $catDesc,
			'updated_date' 	 		=> date('Y-m-d H:i:s') 	
		);	
		$row=$this->tableGateway->update($data, array('category_id' => $category_id));
		return $row;
	}
	//new added 
	public function allDataboxes( $userId )
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_name','category_highlight','settingId'=>'category_type'),'left');
		$select->where('user_categories.user_id="'.$userId.'"');
		$select->where('user_categories.status="1"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	//end new added 

	public function getBoxLinks( $catId ){
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_name','category_image'),'left');
		$select->join('category_links', 'user_categories.category_id=category_links.user_category_id',array('link','link_validity_status'),'left');
		$select->join('link_details', 'category_links.category_link_id=link_details.link_id',array('title','image','description','web_author','meta_content','url_id','is_video','iframe_src'),'left');
		$select->where('category_links.link_validity_status="1"');
		$select->where('user_categories.category_id="'.$catId.'"');
		$select->where('user_categories.status="1"');
		$select->order('link_details.url_id ASC');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}

	public function updateEditedBoxDts( $userCatDetails )
	{
		$data = array(
			'user_hashname'  => $userCatDetails["catHashTag"], 
			'category_title' => $userCatDetails["categoryTitle"], 
			'secret_code'    => $userCatDetails["uniqueCode"], 
			'user_classification' => $userCatDetails["user_classification"], 
			'mature_content' 	 => $userCatDetails["matureContent"], 
			'not_safe_for_work'  => $userCatDetails["notSafeForWork"], 
			'meta_tags' 	 => $userCatDetails["metaTags"], 
			'setting_id' 	 => $userCatDetails["settingId"], 
			'updated_date' 	 => date('Y-m-d H:i:s')
		);	
		$result=$this->tableGateway->update($data, array('category_id' => $userCatDetails['category_id']));
		return 	$result;
	}
	public function getCommentsAllDataboxes()
    {
		$select = $this->tableGateway->getSql()->select();
		$select->join('category', 'user_categories.category_id=category.category_id',array('category_name','category_highlight','settingId'=>'category_type'),'left');
		$select->where('user_categories.status="1"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function getSearchDataboxComments($search)
    {
		$select = $this->tableGateway->getSql()->select();
		$select->where->like( 'user_hashname', '%' . $search . '%' );
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet;
		return $row;
	}
	public function updateDataboxHashNames( $userCatDetails )
	{	
		$data = array(
			'user_hashname'  => "#".$userCatDetails["text"], 
			'updated_date' 	 => date('Y-m-d H:i:s')
		);	
		$result=$this->tableGateway->update($data, array('category_id' => $userCatDetails['categoryId']));
		return 	$result;
	}
	public function updateDataboxHashTitle( $userCatDetails )
	{	
		$data = array(
			'category_title' => $userCatDetails["text"], 
			'updated_date' 	 => date('Y-m-d H:i:s')
		);	
		$result=$this->tableGateway->update($data, array('category_id' => $userCatDetails['categoryId']));
		return 	$result;
	}
}