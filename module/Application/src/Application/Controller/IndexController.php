<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use ScnSocialAuth\Options\ModuleOptions;

class IndexController extends AbstractActionController
{
    const ROUTE_LOGIN        = '/';

    protected  $options;

	protected  $userCategoriesTable;
	protected  $categoryLinksTable;
	protected  $adminReportsTable;
	protected  $userDetailsTable;
	protected  $linkDetailsTable;
	protected  $userTable;
	protected  $userpointsTable;
	protected  $rechargeOrders;
	protected  $rechargeOrdersTable;
	
	public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;

        return $this;
    }
	
	public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceLocator()->get('ScnSocialAuth-ModuleOptions'));
        }
        return $this->options;
    }
	public function introductionPageAction()
    {
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		return $viewModel = new ViewModel(
			array(
				'baseUrl'				 	=> $baseUrl,
				'basePath' 					=> $basePath
			)
		);
	}
	public function logindomAction()
    {
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];	
		return $this->layout()->setVariable(
			"loginarray",array(
				'options'	=>	$this->getOptions(),
			)
		);
	}
	public function browserDisplayAction()
    {
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		return $viewModel = new ViewModel(
			array(
				'baseUrl'				 	=> $baseUrl,
				'basePath' 					=> $basePath
			)
		);
	}
    public function indexAction()
    {
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$homeUsersCount = $this->getUserTable()->getUsersCount();
		$totalHotLinksCount = $this->getLinkDetailsTable()->gettotalHotLinksCount();
		//echo "<pre>";print_r($totalHotLinksCount);exit;
		/*$boxesPerPage = 10;
		$highlightsPerPage = 5;
		$montagesPerPage = 10;

		$publicBoxesRs = $this->getUserCategoriesTable()->getHomePublicBoxes( $boxesPerPage,0 );
		$highlightBoxesRs = $this->getUserCategoriesTable()->getHomeHighlightBoxes( $highlightsPerPage,0 );
		$catWiseLinksCountRs = $this->getCategoryLinksTable()->getCategoryWiseLinksCount();
		$homeMontageBoxesArray = $this->getUserDetailsTable()->getHomeMontageBoxes( $montagesPerPage,0 )->toArray();
		
		$homePublicBoxesArray=array();
		foreach( $publicBoxesRs as $currentBoxRow )
		{
			$homePublicBoxesArray[] = (array)$currentBoxRow;
		}
		$homehighlightBoxesArray=array();
		foreach( $highlightBoxesRs as $currentBoxRow )
		{
			$homehighlightBoxesArray[] = (array)$currentBoxRow;
		}
		$catWiseLinksCountArray=array();
		foreach( $catWiseLinksCountRs as $currentLinksCountRow )
		{
			$catWiseLinksCountArray[] = (array)$currentLinksCountRow;
		}
		$_SESSION["catWiseLinksCount"] = $catWiseLinksCountArray;
		foreach( $homePublicBoxesArray as $key=>$values )
		{
			$homePublicBoxesArray[$key]["categoryLinksCount"] = 0;
			foreach( $catWiseLinksCountArray as $countkey=>$countvalue )
			{
				if( $values['category_id'] == $countvalue['categoryId'] )
				{
					$homePublicBoxesArray[$key]["categoryLinksCount"] = $countvalue['categoryLinksCount'];
				}
			}
		}
		//echo "<pre>";print_r($homePublicBoxesArray);exit;
		foreach( $homehighlightBoxesArray as $key=>$values )
		{
			$homehighlightBoxesArray[$key]["categoryLinksCount"] = 0;
			foreach( $catWiseLinksCountArray as $countkey=>$countvalue )
			{ 
				if( $values['category_id'] == $countvalue['categoryId'] )
				{
					$homehighlightBoxesArray[$key]["categoryLinksCount"] = $countvalue['categoryLinksCount'];
				}
			}
		}
		if( $this->getRequest()->isPost() )
		{
			if (!$this->zfcUserAuthentication()->hasIdentity()) {
				return $this->redirect()->toRoute(static::ROUTE_LOGIN);
			}
			else
			{
				if(isset($_SESSION['sessionUrl'])){
					$sessionUrl=$_SESSION['sessionUrl'];
				}else{
					$sessionUrl='';
				}
				$userinfo=$this->zfcUserAuthentication()->getIdentity();
			}
			return new ViewModel();
		}
		else
		{
			$viewModel = new ViewModel(
				array(
					'baseUrl'				 	=> $baseUrl,
					'basePath' 					=> $basePath,
					'countt'				=>	0
			));
			$viewModel->setVariable('options', $this->getOptions());
			return $viewModel;
		}*/
		$viewModel = new ViewModel(
				array(
					'baseUrl'				 	=> $baseUrl,
					'basePath' 					=> $basePath,
					'homeUsersCount' 	    => $homeUsersCount,
					'totalHotLinksCount' 	    => $totalHotLinksCount
			));
			$viewModel->setVariable('options', $this->getOptions());
			return $viewModel;
    }
	public function mainpageAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];

		$boxesPerPage = 10;
		$highlightsPerPage = 5;
		$montagesPerPage = 10;
		$filterPerPage = 10;
		$relevanceWorthVoteTable=$this->getServiceLocator()->get('Databox\Model\RelevanceWorthVoteFactory');
		$publicBoxesRs = $this->getUserCategoriesTable()->getHomePublicBoxes( $boxesPerPage,0,0 );
		$highlightBoxesRs = $this->getUserCategoriesTable()->getHomeHighlightBoxes( $highlightsPerPage,0 );
		$catWiseLinksCountRs = $this->getCategoryLinksTable()->getCategoryWiseLinksCount();
		$homeMontageBoxesArray = $this->getUserDetailsTable()->getHomeMontageBoxes( $montagesPerPage,0 )->toArray();
		//newly added this for filtering
		$publicBoxesFilterNew = $this->getUserCategoriesTable()->getHomePublicBoxesForFilters( $filterPerPage,0,2);
		$publicBoxesFilterTrending = $this->getUserCategoriesTable()->getHomePublicBoxesForFilters( $filterPerPage,0,3);
		$publicBoxesFilterMyPosts = $this->getUserCategoriesTable()->getHomePublicBoxesForFilters( $filterPerPage,0,4);
		$homePublicBoxesNewArray=array();
		foreach( $publicBoxesFilterNew as $currentBoxRow )
		{
			$homePublicBoxesNewArray[] = (array)$currentBoxRow;
		}
		$homePublicBoxesTrendingArray=array();
		foreach( $publicBoxesFilterTrending as $currentBoxRow )
		{
			$homePublicBoxesTrendingArray[] = (array)$currentBoxRow;
		}
		$homePublicBoxesMyPostsArray=array();
		foreach( $publicBoxesFilterMyPosts as $currentBoxRow )
		{
			$homePublicBoxesMyPostsArray[] = (array)$currentBoxRow;
		}
		// end filter newly added code
		$homePublicBoxesArray=array();
		foreach( $publicBoxesRs as $currentBoxRow )
		{
			$homePublicBoxesArray[] = (array)$currentBoxRow;
		}
		$homehighlightBoxesArray=array();
		foreach( $highlightBoxesRs as $currentBoxRow )
		{
			$homehighlightBoxesArray[] = (array)$currentBoxRow;
		}
		$catWiseLinksCountArray=array();
		foreach( $catWiseLinksCountRs as $currentLinksCountRow )
		{
			$catWiseLinksCountArray[] = (array)$currentLinksCountRow;
		}
		$_SESSION["catWiseLinksCount"] = $catWiseLinksCountArray;
		//new added for filters
		foreach( $homePublicBoxesNewArray as $key=>$values )
		{
			$homePublicBoxesNewArray[$key]["categoryLinksCount"] = 0;
			foreach( $catWiseLinksCountArray as $countkey=>$countvalue )
			{
				if( $values['category_id'] == $countvalue['categoryId'] )
				{
					$homePublicBoxesNewArray[$key]["categoryLinksCount"] = $countvalue['categoryLinksCount'];
				}
			}
		}
		foreach( $homePublicBoxesTrendingArray as $key=>$values )
		{
			$homePublicBoxesTrendingArray[$key]["categoryLinksCount"] = 0;
			foreach( $catWiseLinksCountArray as $countkey=>$countvalue )
			{
				if( $values['category_id'] == $countvalue['categoryId'] )
				{
					$homePublicBoxesTrendingArray[$key]["categoryLinksCount"] = $countvalue['categoryLinksCount'];
				}
			}
		}
			//echo "<pre>";print_r($homePublicBoxesArray);exit;

		foreach( $homePublicBoxesMyPostsArray as $key=>$values )
		{
			$homePublicBoxesMyPostsArray[$key]["categoryLinksCount"] = 0;
			foreach( $catWiseLinksCountArray as $countkey=>$countvalue )
			{
				if( $values['category_id'] == $countvalue['categoryId'] )
				{
					$homePublicBoxesMyPostsArray[$key]["categoryLinksCount"] = $countvalue['categoryLinksCount'];
				}
			}
		}

		//End new added for filters
		foreach( $homePublicBoxesArray as $key=>$values )
		{
			$homePublicBoxesArray[$key]["categoryLinksCount"] = 0;
			foreach( $catWiseLinksCountArray as $countkey=>$countvalue )
			{
				if( $values['category_id'] == $countvalue['categoryId'] )
				{
					$homePublicBoxesArray[$key]["categoryLinksCount"] = $countvalue['categoryLinksCount'];
				}
			}
		}
	//echo "<pre>";print_r($homePublicBoxesArray);exit;
		foreach( $homehighlightBoxesArray as $key=>$values )
		{
			$homehighlightBoxesArray[$key]["categoryLinksCount"] = 0;
			foreach( $catWiseLinksCountArray as $countkey=>$countvalue )
			{ 
				if( $values['category_id'] == $countvalue['categoryId'] )
				{
					$homehighlightBoxesArray[$key]["categoryLinksCount"] = $countvalue['categoryLinksCount'];
				}
			}
		}
		//echo "<pre>";print_r($homehighlightBoxesArray);exit;
		if( $this->getRequest()->isPost() )
		{
			if (!$this->zfcUserAuthentication()->hasIdentity()) {
				return $this->redirect()->toRoute(static::ROUTE_LOGIN);
			}
			else
			{
				if(isset($_SESSION['sessionUrl'])){
					$sessionUrl=$_SESSION['sessionUrl'];
				}else{
					$sessionUrl='';
				}
				$userinfo=$this->zfcUserAuthentication()->getIdentity();
			}
			return new ViewModel();
		}
		else
		{
			$viewModel = new ViewModel(
				array(
					'baseUrl'				 			=> $baseUrl,
					'basePath' 							=> $basePath,
					'homePublicBoxesArray' 	    		=> $homePublicBoxesArray,
					'homePublicBoxesNewArray' 	    	=> $homePublicBoxesNewArray,
					'homePublicBoxesTrendingArray' 	   	=> $homePublicBoxesTrendingArray,
					'homePublicBoxesMyPostsArray' 	    => $homePublicBoxesMyPostsArray,
					'homehighlightBoxesArray' 			=> $homehighlightBoxesArray,
					'homeMontageBoxesArray' 			=> $homeMontageBoxesArray,
					'countt'							=>	0
			));
			$viewModel->setVariable('options', $this->getOptions());
			return $viewModel;
		}
		
	}

	public function logoutAction()
	{
		$userId = $_SESSION['usersinfo']->userId;
		$files = glob('./public/dashboard/'.$userId.'-'.'*');
		foreach( $files as $file )
		{
			if( is_file($file) )
			@unlink($file);
		}
		$userFileName = './public/databoxes/'.$userId.'.txt';
		if( is_file($userFileName) )
		@unlink($userFileName);

		unset($_SESSION['urls']);
		unset($_SESSION['catWiseLinksCount']);
		unset($_SESSION['userTotBoxes']);
		unset($_SESSION['montageLinks']);
		unset($_SESSION['userCollectionLinks']);
		unset($_SESSION['updateDatabox']);
		unset($_SESSION['deleteDatabox']);
		unset($_SESSION['usersinfo']);
		unset($_SESSION['Zend_Auth']);
		$_SESSION['logoutStatus']=1;
		$result = new JsonModel(array(
			'output' => 'success',
			'success'=>false,
		));
		return $result;
	}
	public function deactivateAction()
	{
		session_destroy();
		unset($_SESSION['Zend_Auth']);
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$viewModel = new ViewModel(
				array(
					'baseUrl' 	=> $baseUrl,
					'basePath' 	=> $basePath
					
			));
		
	}

	public function headerAction($params)
    {
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$userPoints ='0';
		if(isset($_SESSION['usersinfo']->userId) && $_SESSION['usersinfo']->userId!=""){
			$userpointsTable = $this->getUserPointsTable();
			$userPointss = $userpointsTable->loggedUserPoints($_SESSION['usersinfo']->userId);
			$userRechargeOrdersTable = $this->getUserRechargeOrdersTable();
			$userRecharge = $userRechargeOrdersTable->userRechargedPoints($_SESSION['usersinfo']->userId);
			if(count($userPointss)>0){
				$userPoints = (($userPointss->userPoints)-$userRecharge->userPointsminus);
			}
			$_SESSION['usersinfo']->rewardPoints=$userPoints;
		}else if(isset($_SESSION['Zend_Auth']->storage) && $_SESSION['Zend_Auth']->storage!=""){
			$userpointsTable = $this->getUserPointsTable();
			$userPointss = $userpointsTable->loggedUserPoints($_SESSION['Zend_Auth']->storage);
			$userRechargeOrdersTable = $this->getUserRechargeOrdersTable();
			$userRecharge = $userRechargeOrdersTable->userRechargedPoints($_SESSION['Zend_Auth']->storage);
			if(count($userPointss)>0){
				$userPoints = (($userPointss->userPoints)-$userRecharge->userPointsminus);
			}
			$_SESSION['usersinfo']->rewardPoints=$userPoints;
		}		
		return $this->layout()->setVariable(
			"headerarray",array(
				'baseUrl' 		=> 	$baseUrl,
				'basePath'		=>	$basePath,				
				'userPoints'    =>	$userPoints,				
			)
		);
	}
	
	public function getUserCategoriesTable()
    {
        if (!$this->userCategoriesTable) {				
            $sm = $this->getServiceLocator();
            $this->userCategoriesTable = $sm->get('Databox\Model\UserCategoriesFactory');			
        }
        return $this->userCategoriesTable;
    }

	public function getCategoryLinksTable()
    {
        if (!$this->categoryLinksTable) {				
            $sm = $this->getServiceLocator();
            $this->categoryLinksTable = $sm->get('Databox\Model\CategoryLinksFactory');			
        }
        return $this->categoryLinksTable;
    }
	public function getUserDetailsTable()
    {
        if (!$this->userDetailsTable) {				
            $sm = $this->getServiceLocator();
            $this->userDetailsTable = $sm->get('Databoxuser\Model\UserDetailsFactory');			
        }
        return $this->userDetailsTable;
    }
	
	public function memberCollectionsAction()
	{
		$baseUrls 	= $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl 	= $baseUrlArr['baseUrl'];
		$basePath 	= $baseUrlArr['basePath'];
		$relevanceWorthVoteTable=$this->getServiceLocator()->get('Databox\Model\RelevanceWorthVoteFactory');
		$getDataboxes = $this->getUserCategoriesTable()->getMemberCollections();
		$dashboard=array();
		$count=0;
		$privateCount=0;
		if($getDataboxes->count()!=0){
			foreach($getDataboxes as $databoxes){
				if(isset($_SESSION['usersinfo']->userId)){
					$user_id=$_SESSION['usersinfo']->userId;
				}else{
					$user_id=$_SERVER['REMOTE_ADDR'];
				}
				$getRelevanceWorth = $relevanceWorthVoteTable->getVoteUpDown( $databoxes->category_id,$user_id );
				$categoryRelevanceStatus = "";
				$categoryWorthStatus = "";
				if( $getRelevanceWorth->count()!= 0 )
				{
					$getRelevanceWorth->buffer();
					$categoryRelevanceStatus = $getRelevanceWorth->current()->relevance;
					if( $categoryRelevanceStatus == null )
					{
						$categoryRelevanceStatus = "2";
					}
					$categoryWorthStatus = $getRelevanceWorth->current()->worth;
					if( $categoryWorthStatus == null )
					{
						$categoryWorthStatus = "2";
					}
				}
				else
				{
					$categoryRelevanceStatus = "2";
					$categoryWorthStatus = "2";
				}
				if(array_key_exists($databoxes->category_id,$dashboard)){
						$count++;
						$dashboard[$databoxes->category_id]['category_title']=$databoxes->category_title;
						$dashboard[$databoxes->category_id]['user_hashname']=$databoxes->user_hashname;
						$dashboard[$databoxes->category_id]['links'][$count]=$databoxes->link.'***'.$databoxes->title.'***'.$databoxes->image;
						$dashboard[$databoxes->category_id]['totalLinks']=$count;
						$dashboard[$databoxes->category_id]['user_category_id']=$databoxes->user_category_id;
						$dashboard[$databoxes->category_id]['categoryRelevance']=$categoryRelevanceStatus;
						$dashboard[$databoxes->category_id]['categoryWorth']=$categoryWorthStatus;
						$dashboard[$databoxes->category_id]['user_id']=$databoxes->user_id;
				}else{
					$count=1;
					if($databoxes->category_type==0){
						$privateCount++;
					}
					$dashboard[$databoxes->category_id]['category_title']=$databoxes->category_title;
					$dashboard[$databoxes->category_id]['user_hashname']=$databoxes->user_hashname;
					$dashboard[$databoxes->category_id]['links'][$count]=$databoxes->link.'***'.$databoxes->title.'***'.$databoxes->image;
					$dashboard[$databoxes->category_id]['totalLinks']=$count;
					$dashboard[$databoxes->category_id]['user_category_id']=$databoxes->user_category_id;
					$dashboard[$databoxes->category_id]['categoryRelevance']=$categoryRelevanceStatus;
					$dashboard[$databoxes->category_id]['categoryWorth']=$categoryWorthStatus;
					$dashboard[$databoxes->category_id]['user_id']=$databoxes->user_id;
				}
			}
			$_SESSION['montageLinks']=$dashboard;
			return $view = new ViewModel(
			array(
				'baseUrl' 			=> 	$baseUrl,
				'basePath' 			=> 	$basePath,
				'dashboard'			=>	$dashboard,
				'totalDataboxes'	=>	count($dashboard),
				'privateCount'		=>	$privateCount,
				'options'			=>	$this->getOptions(),
			));
		}else{
			return $view = new ViewModel(
			array(
				'baseUrl' 	=> $baseUrl,
				'basePath' 	=> $basePath,
				'dashboard'	=>	$dashboard,
				'options'	=>	$this->getOptions(),
			));
		}
	}
	
	public function userCollectionBoxesAjaxAction()
	{
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];

		$montageBoxesPerPage = $_POST['montageBoxesPerPage'];
		$montageBoxesOffset = $_POST['montageBoxesOffset'];
		// echo $montageBoxesOffset;exit;
		$montageBoxesRs = $this->getUserDetailsTable()->getHomeMontageBoxes( $montageBoxesPerPage,$montageBoxesOffset );
		// echo "<pre>";print_r($montageBoxesRs->count());exit;
		//echo "<pre>"; print_r($montageBoxesRs->toArray()); exit;
		$montageBoxesHtml = "";
		$divCards = array();
		$montageBoxesAllLoaded = 0;
		if( $montageBoxesRs->count() == 0 )
		{
			$montageBoxesAllLoaded = 1;
			$result = new JsonModel(array(
				'montageBoxesAllLoaded' 	=>  $montageBoxesAllLoaded,
				'cards'						=>	json_encode($divCards)
			));	
			return $result;
		}
		//$montageBoxesHtml.='<div id="divDataboxWrapper" class="divCardsWrapper" data-columns ="4">';
		// $montageBoxesHtml.='<div class="left width25">';
		foreach( $montageBoxesRs as $currentBoxRow )
		{
			$montageBoxesHtml1 = "";
			$collectionCount = 0;
			if( $collectionCount == 0 )
			{
				//$linksCount = $this->getCategoryLinksTable()->getCategoryLinks( $currentBoxRow->category_id )->count();
			}
			$viewUrl = "user-collection";
			$displayUserCollectionUrl = $baseUrl;
			$displayUserCollectionUrl .= "/" . $viewUrl . "/" . $currentBoxRow->user_id;
			$montageBoxesHtml1.='<div class="publicsearchdatabox">';
			$montageBoxesHtml1.='<div id="divDatabox-1" class="divCard">';
            $montageBoxesHtml1.='<div id="divDatabox-1-imageWrapper" class="divCardImageWrapper">';
			$montageHashName = "";
			if( ! is_null($currentBoxRow->montage_hash_name) && $currentBoxRow->montage_hash_name != "" )
			{
				if( strlen($currentBoxRow->montage_hash_name)>25 )
				{
					$montageHashName = substr($currentBoxRow->montage_hash_name,0,25) . '...';
				}
				else
				{
					$montageHashName = $currentBoxRow->montage_hash_name;
				}
				//$montageBoxesHtml .= '<div class="hashtag_main_public_boxes hashtag_pos_ab"><p><a class="home_anchor_black" href="' . $displayUserCollectionUrl . '">'.$montageHashName.'</a></p></div>';
			}
			
			$memberDisplayImage = $basePath.'/images/project/montageImages/' . $currentBoxRow->montage_image;
			if( ! is_null($currentBoxRow->montage_main_image) && $currentBoxRow->montage_main_image != "" )
			{
				$memberDisplayImage = $basePath.'/images/project/montageMainImage/'.$currentBoxRow->montage_main_image;
			}
			$montageBoxesHtml1.='<img id="Databox-1-img" class="" alt="'.$montageHashName.'" src="'.$memberDisplayImage.' " />';
			$montageBoxesHtml1.='<div id="divDatabox-1-hashtag" class="divCardHashtag">'.$montageHashName.'</div>';
			$montageBoxesHtml1.='<div id="divDatabox-1-sharedLinks" class="divCardSharedLinks"><span class="fatFont"> '.$currentBoxRow->userWiseLinksCount .' HOT LINKS </span> collected and shared </div>';
		    $montageBoxesHtml1.='<div id="Card-1-user" class="divCardUser">';
				if($currentBoxRow->image!=""){
					$montageBoxesHtml1.='<img  src=" '.$currentBoxRow->image .'" class="imgUserImage" alt="" />';
				}else{ 
					$montageBoxesHtml1.='<img src="'.$basePath .'/img/userImage.png" class="imgUserImage" alt="" />';
				 }
				$montageBoxesHtml1.='<div class="divCardUserName">By: '. $currentBoxRow->display_name .' </div>';
			$montageBoxesHtml1.='</div>';
			$montageBoxesHtml1.='<div id="divDatabox-1-contentWrapper" class="divCardContentWrapper">';
             /*$montageBoxesHtml.='<div id="divDatabox-1-views" class="divCardViews">';
             $montageBoxesHtml1.='<img src="'.$basePath .'/img/views.png" alt="" /> 120 views </div>';
            $montageBoxesHtml1.='<div class="divCardLoveTrash">';
            $montageBoxesHtml1.=' <a href="#"><img src="'.$basePath .'/img/love.png" alt="" /></a>  or  <img src="'. $basePath .'/img/trash.png" alt="" />';
            $montageBoxesHtml1.='</div>';*/
            // $montageBoxesHtml1.= '<div id="divCardKeywords" class="divCardKeywords">Web Development, Javascript, JQuery, HTML</div>';
			/*$montageBoxesHtml .= '<div class="image_block_publick_databox"><a href="' . $displayUserCollectionUrl . '" onClick="Javascript:displayUserCollection( ' . $currentBoxRow->user_id . ' );"><img src= "'. $memberDisplayImage . '" width="234" height="302" /></a></div>';*/
			$montageBoxesHtml1 .='<div id="divDatabox-1-title" class="divCardTitle">';
			if( ! is_null($currentBoxRow->montage_title) && $currentBoxRow->montage_title != "" )
			{
				if(strlen($currentBoxRow->montage_title)>57)
				{
					$montageTitle = substr($currentBoxRow->montage_title,0,57) . '...';
				}
				else
				{
					$montageTitle = $currentBoxRow->montage_title;
				}
				
			}
				$montageTitle;
			$montageBoxesHtml1 .= '<h2 class="home_title_d">' . $montageTitle .'</h2></div>';
			 $montageBoxesHtml1 .='<div id="divDatabox-1-description" class="divCardDescription home_title_des_s">';
                                 $currentBoxRow->montage_paragraph;
                           $montageBoxesHtml1 .= '</div>';
                       $montageBoxesHtml1 .= '</div>';
                  $montageBoxesHtml1 .=  '</div>';
			$montageBoxesHtml1 .=  '</div>'; 
			   array_push($divCards, $montageBoxesHtml1);
			/*$montageBoxesHtml .= '<div class="home_pos_abso_left"><p>>> Listed Sources >> </p></div>';
			$montageBoxesHtml .= '<div class="home_pos_abso_right"><p>' . $currentBoxRow->userWiseLinksCount . '</p></div>';

			$montageBoxesHtml .= '</div></li>';*/
		}
		
		// echo "<pre>";print_r($divCards);
		// exit;
		
		 // $montageBoxesHtml .=  '</div>';
		 //echo "<pre>"; print_r($montageBoxesHtml); exit;
		if( $montageBoxesRs->count() < $montageBoxesPerPage )
		{
			$montageBoxesAllLoaded = 1;
		}
		$result = new JsonModel(array(					
			'montageBoxesAllLoaded' =>  $montageBoxesAllLoaded,
			'cards'					=>	json_encode($divCards)
			// 'montageBoxesHtml'		=>	$montageBoxesHtml
		));	
		return $result;
	}
	public function userCollectionAction()
	{
		$baseUrls 	= $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl 	= $baseUrlArr['baseUrl'];
		$basePath 	= $baseUrlArr['basePath'];

		$userId = 0;
		if($this->params()->fromRoute('id', 0)!="")
		{
			$userId=$this->params()->fromRoute('id', 0);
		}

		$ucUserMontageDetails = $this->getUserDetailsTable()->getUcUserMontageDetails( $userId );
		$montageHashName = $ucUserMontageDetails->montage_hash_name;
		$montageTitle = $ucUserMontageDetails->montage_title;
		$montageImage = $ucUserMontageDetails->montage_image;
		$montageMainImage = $ucUserMontageDetails->montage_main_image;
		$montageParagraph = $ucUserMontageDetails->montage_paragraph;

		$relevanceWorthVoteTable=$this->getServiceLocator()->get('Databox\Model\RelevanceWorthVoteFactory');
		$getDataboxes = $this->getUserCategoriesTable()->getHomeUserCollection( $userId );

		$dashboard=array();

		$count=0;

		if($getDataboxes->count()!=0){
			foreach($getDataboxes as $databoxes){
				if(isset($_SESSION['usersinfo']->userId)){
					$user_id=$_SESSION['usersinfo']->userId;
				}else{
					$user_id=$_SERVER['REMOTE_ADDR'];
				}
				$getRelevanceWorth = $relevanceWorthVoteTable->getVoteUpDown( $databoxes->category_id,$user_id );
				$categoryRelevanceStatus = "";
				$categoryWorthStatus = "";
				if( $getRelevanceWorth->count()!= 0 )
				{
					$getRelevanceWorth->buffer();
					$categoryRelevanceStatus = $getRelevanceWorth->current()->relevance;
					if( $categoryRelevanceStatus == null )
					{
						$categoryRelevanceStatus = "2";
					}
					$categoryWorthStatus = $getRelevanceWorth->current()->worth;
					if( $categoryWorthStatus == null )
					{
						$categoryWorthStatus = "2";
					}
				}
				else
				{
					$categoryRelevanceStatus = "2";
					$categoryWorthStatus = "2";
				}
				if(array_key_exists($databoxes->category_id,$dashboard)){
						$count++;
						$dashboard[$databoxes->category_id]['category_title']=$databoxes->category_title;
						$dashboard[$databoxes->category_id]['user_hashname']=$databoxes->user_hashname;
						$dashboard[$databoxes->category_id]['links'][$count]=$databoxes->link.'***'.$databoxes->title.'***'.$databoxes->image;
						$dashboard[$databoxes->category_id]['totalLinks']=$count;
						$dashboard[$databoxes->category_id]['user_category_id']=$databoxes->user_category_id;
						$dashboard[$databoxes->category_id]['categoryRelevance']=$categoryRelevanceStatus;
						$dashboard[$databoxes->category_id]['categoryWorth']=$categoryWorthStatus;
						$dashboard[$databoxes->category_id]['user_id']=$databoxes->user_id;
						$dashboard[$databoxes->category_id]['category_id']=$databoxes->category_id;
						$dashboard[$databoxes->category_id]['category_image']=$databoxes->category_image;
						$dashboard[$databoxes->category_id]['settingId']=$databoxes->setting_id;
				}else{
					$count=1;
					$dashboard[$databoxes->category_id]['category_title']=$databoxes->category_title;
					$dashboard[$databoxes->category_id]['user_hashname']=$databoxes->user_hashname;
					$dashboard[$databoxes->category_id]['links'][$count]=$databoxes->link.'***'.$databoxes->title.'***'.$databoxes->image;
					$dashboard[$databoxes->category_id]['totalLinks']=$count;
					$dashboard[$databoxes->category_id]['user_category_id']=$databoxes->user_category_id;
					$dashboard[$databoxes->category_id]['categoryRelevance']=$categoryRelevanceStatus;
					$dashboard[$databoxes->category_id]['categoryWorth']=$categoryWorthStatus;
					$dashboard[$databoxes->category_id]['user_id']=$databoxes->user_id;
					$dashboard[$databoxes->category_id]['category_id']=$databoxes->category_id;
					$dashboard[$databoxes->category_id]['category_image']=$databoxes->category_image;
					$dashboard[$databoxes->category_id]['settingId']=$databoxes->setting_id;
				}
			}
			$_SESSION['userCollectionLinks']=$dashboard;
			return $view = new ViewModel(
			array(
				'baseUrl' 			=> 	$baseUrl,
				'basePath' 			=> 	$basePath,
				'dashboard'			=>	$dashboard,
				'totalDataboxes'	=>	count($dashboard),
				'options'			=>	$this->getOptions(),
				'montageHashName'	=>	$montageHashName,
				'montageTitle'		=>	$montageTitle,
				'montageImage'		=>	$montageImage,
				'montageMainImage'	=>	$montageMainImage,
				'montageParagraph'	=>	$montageParagraph,
				'ucUserId'			=>	$userId,
			));
		}else{
			return $view = new ViewModel(
			array(
				'baseUrl' 	=> $baseUrl,
				'basePath' 	=> $basePath,
				'dashboard'	=>	$dashboard,
				'options'	=>	$this->getOptions(),
				'montageHashName'	=>	$montageHashName,
				'montageTitle'		=>	$montageTitle,
				'montageImage'		=>	$montageImage,
				'montageMainImage'	=>	$montageMainImage,
				'montageParagraph'	=>	$montageParagraph,
				'ucUserId'			=>	$userId,
			));
		}
	}

	public function reportsAction(){
		$baseUrls 	= $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl 	= $baseUrlArr['baseUrl'];
		$basePath 	= $baseUrlArr['basePath'];
		if(isset($_SESSION['usersinfo']->userId)){
			$user_id=$_SESSION['usersinfo']->userId;
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		if($_POST['option']=='update'){
			$getReports = $this->getAdminReportsTable()->updateReport($_POST);
			$getReportsId=$_POST['reportId'];
		}else{
			$getReports = $this->getAdminReportsTable()->addReport($user_id,$_POST,$ip);
			$getReportsId=$getReports;
		}
		if($getReports!=0){
			return $view = new JsonModel(
			array(
				'baseUrl' 	=> 	$baseUrl,
				'basePath' 	=> 	$basePath,
				'output'	=>	1,
				'reportId'	=>	$getReportsId,
			));
		}
	}

	public function userCollectionLinksAction(){
		$baseUrls 	= $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl 	= $baseUrlArr['baseUrl'];
		$basePath 	= $baseUrlArr['basePath'];
		$result= new ViewModel(array(
				'dashboard'		=>	$_SESSION['userCollectionLinks'],
				'start'			=>	$_POST['start'],
				'end'			=>	$_POST['end'],
				'catId'			=>	$_POST['catId'],
				'baseUrl'		=>  $baseUrl,
				'basePath'		=>  $basePath,
			));
			$result->setTerminal(true);
			return $result;
	}

	
	public function searchHashNamesAction()
	{
		$hashNames="";
		$hashNameIds="";
		$count="";
		
		$getsearchHashNames = $this->getUserCategoriesTable()->getsearchHashNames($_POST['value'])->toArray();

		if( count($getsearchHashNames) > 0 )
		{
			$searchedHashNames = array();
			
			foreach( $getsearchHashNames as $key=>$searchArr)
			{
				array_push ( $searchedHashNames,$searchArr["user_hashname"] );
			}		

			$hashNameWiseCountsArr = array_count_values( $searchedHashNames );

			$partsVal = 1;
			$combinedCountsArr = array();

			foreach( $hashNameWiseCountsArr as $currHashName => $currHasCount )
			{
				$currRefVal = "";
				if( $currHasCount > 1 )
				{
					$currRefVal = $currHashName.'('.$currHasCount.')';
				}
				else
				{
					$currRefVal = $currHashName;
				}
				
				$currHashArr = array( "ref" => $currRefVal,"part" => $partsVal++ );
				array_push ( $combinedCountsArr,$currHashArr );
			}

			return $view = new JsonModel(
			array(
				'output'			=>	1,
				'searchHashNames'	=>	$combinedCountsArr,
				'countt'				=>	$count,
			));
		}else{
			return $view = new JsonModel(
			array(
				'output'			=>	0,
			));
		}
	}

	
	public function termsAction()
	{
		$baseUrls 	= $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		return $viewModel = new ViewModel(
				array(
					'baseUrl' 	=> $baseUrl,
					'basePath' 	=> $basePath,
					'options'	=> $this->getOptions(),
					
			));
	}
	public function privacyPolicyAction()
	{
		$baseUrls 	= $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		return $viewModel = new ViewModel(
				array(
					'baseUrl' 	=> $baseUrl,
					'basePath' 	=> $basePath,
					'options'	=> $this->getOptions(),
					
			));
	}
	//Sign Up Function
	
	public function signupAction(){
		$baseUrls 	= $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
			return $viewModel = new ViewModel(
				array(
					'baseUrl' 	=> $baseUrl,
					'basePath' 	=> $basePath,
				)
			);
	}
	public function getAdminReportsTable(){
		if (!$this->adminReportsTable) {				
			$sm = $this->getServiceLocator();
			$this->adminReportsTable = $sm->get('ZfcAdmin\Model\AdminReportsFactory');			
		}
		return $this->adminReportsTable;
	}
	public function getUserTable()
    {
        if (!$this->userTable) {				
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Databoxuser\Model\UserFactory');			
        }
        return $this->userTable;
    }
	//link details table
	public function getLinkDetailsTable()
    {
        if (!$this->linkDetailsTable) {				
            $sm = $this->getServiceLocator();
            $this->linkDetailsTable = $sm->get('Databox\Model\LinkDetailsFactory');			
        }
        return $this->linkDetailsTable;
    }
	public function getUserPointsTable()
    {
        if (!$this->userpointsTable) {				
            $sm = $this->getServiceLocator();
            $this->userpointsTable = $sm->get('Databoxuser\Model\UserPointsFactory');			
        }
        return $this->userpointsTable;
    }
	public function getUserRechargeOrdersTable()
    {
        if (!$this->rechargeOrdersTable) {				
            $sm = $this->getServiceLocator();
            $this->rechargeOrdersTable = $sm->get('Databoxuser\Model\rechargeOrdersFactory');			
        }
        return $this->rechargeOrdersTable;
    }
}

