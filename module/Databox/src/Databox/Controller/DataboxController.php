<?php
namespace Databox\Controller;

use ScnSocialAuth\Mapper\Exception as MapperException;
use ScnSocialAuth\Mapper\UserProviderInterface;
use ScnSocialAuth\Options\ModuleOptions;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

use LinkPreview;
use SetUp;

use Users\Form\UserForm; 
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

class DataboxController extends AbstractActionController
{
	protected  $categoryTable;
	protected  $searchCategoriesListTable;
	protected  $userCategoriesTable;
	protected  $categoryLinksTable;
	protected  $linkDetailsTable;
	protected  $linkSettingTable;
	protected  $settingFlexibleTypeTable;
	protected  $userHighlightsTable;
	protected  $catImageTable;
	protected  $jsPlumbGridTable;
	protected $userTable;
	protected $databoxViewsTable;
	protected $userCollectionsTable;
	protected $databoxCommentsTable;
	
	protected $jsonLinksArray=array();
	protected $jsonLinkNum = 0;
	protected $bakLinksArray=array();
	protected $bakLinkNum = 0;
	
	protected $fileExtension="";

	/**
     * @var UserProviderInterface
     */
    protected $mapper;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /*
     * @todo Make this dynamic / translation-friendly
     * @var string
     */  

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


	public function setTzBoxIdAction()
	{
		$tzBoxId = 0;
		if( isset($_POST) && isset($_POST['tzCreateEditBoxId']) )
		{
			$_SESSION["tzCreateEditBoxId"] = $_POST['tzCreateEditBoxId'];
			$tzBoxId = 1;
		}
		return new JsonModel(array(				
			'output' => $tzBoxId
		));
	}

	//Crete Hashtag
	
	public function createHashTagAction()
	{
		$params="";
		$formUseMode = "highlight";
		$editBoxId = 0;
		$catHashTag="";
		$categoryTitle="";
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];

		if($this->params()->fromRoute('id', 0)!="")
		{
			$params=$this->params()->fromRoute('id', 0);
			
			if( strtolower($params) == "privatedatabox" )
			{
				$formUseMode = "privatedatabox";
			}
			else if( strtolower($params) == "publicdatabox" )
			{
				$formUseMode = "publicdatabox";
			}
			else
			{
				$formUseMode = "highlight";
			}
		}
		if($this->params()->fromRoute('boxid', 0)!="")
		{
			$currBoxId=$this->params()->fromRoute('boxid', 0);

			$editBoxId = $currBoxId;
			if( isset($editBoxId) && trim($editBoxId) != "" && is_numeric($editBoxId) && $editBoxId )
			{
				if( isset($_SESSION["tzCreateEditBoxId"]) && $_SESSION["tzCreateEditBoxId"] != $editBoxId )
				{
					return $this->redirect()->toUrl($baseUrl.'/contentpage');
				}

				$catRow = $this->getUserCategoriesTable()->getEditHighlight( $editBoxId );
				if( isset($catRow->user_hashname) )
				{
					$catHashTag="#".substr($catRow->user_hashname,1);
					$categoryTitle=str_replace('-',' ',$catRow->category_title);
				}
			}
			else
			{
				if( isset($_SESSION["tzCreateEditBoxId"]) && $_SESSION["tzCreateEditBoxId"] != $editBoxId )
				{
					return $this->redirect()->toUrl($baseUrl.'/contentpage');
				}
			}
		}

		return new ViewModel(array(				
			'baseUrl' 	=> $baseUrl,
			'basePath' 	=> $basePath,
			'editBoxId' 	=> $editBoxId,
			'catHashTag' 	=> $catHashTag,
			'categoryTitle' => $categoryTitle,
			'formUseMode' 	=> $formUseMode
		));
	}
	//Upload Bookmarks Action
	public function bookmarksAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		unset($_SESSION['tzCreateEditBoxId']);
		unset($_SESSION['databox']);

		$databox_session = new Container('databox');
		if($_POST){
			$databox_session->hashtag=$_POST['hashtagHolder'];
			$databox_session->hashtitle=$_POST['hashtagTitle'];
		}

		$formUseMode = "highlight";
		$params="";
		$editBoxId = 0;

		if($this->params()->fromRoute('id', 0)!="")
		{
			$params=$this->params()->fromRoute('id', 0);
			if( strtolower($params) == "privatedatabox" )
			{
				$formUseMode = "privatedatabox";
			}
			else if( strtolower($params) == "publicdatabox" )
			{
				$formUseMode = "publicdatabox";
			}
			else
			{
				$formUseMode = "highlight";
			}
		}
		if($this->params()->fromRoute('boxid', 0)!="")
		{
			$currBoxId=$this->params()->fromRoute('boxid', 0);
			// echo "<pre>"; print_r($currBoxId);exit;

			$editBoxId = $currBoxId;
			if( isset($_SESSION["tzCreateEditBoxId"]) && $_SESSION["tzCreateEditBoxId"] != $editBoxId )
			{
				return $this->redirect()->toUrl($baseUrl.'/contentpage');
			}
		}
		
		return new ViewModel(array(				
			'baseUrl' 	=> $baseUrl,
			'basePath' 	=> $basePath,
			'editBoxId' 	=> $editBoxId,
			'formUseMode' 	=> $formUseMode
		));
	}
	
	//Create Highlights
	public function createhighlightsAction()
	{
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];

		$editBoxId = 0;
		$metaTags = "";
		$mature_content = 0;
		$not_safe_for_work = 0;
		$tzCatImage = "";
		$boxLinksArr= array();
		$c="00";
		$user_classification="";
		$userId1 = $_SESSION['usersinfo']->userId;
		@unlink( './public/databoxes/'.$userId1.'.txt' );
		if($this->params()->fromRoute('boxid', 0)!="")
		{
			
			$currBoxId=$this->params()->fromRoute('boxid', 0);
		// echo "<pre>"; print_r($currBoxId);exit;

			$editBoxId = $currBoxId;
			if( isset($editBoxId) && trim($editBoxId) != "" && is_numeric($editBoxId) && $editBoxId )
			{
				// echo $editBoxId;exit;

				if( isset($_SESSION["tzCreateEditBoxId"]) && $_SESSION["tzCreateEditBoxId"] != $editBoxId )
				{
					return $this->redirect()->toUrl($baseUrl.'/contentpage');
				}

				$catRow = $this->getUserCategoriesTable()->getEditHighlight( $editBoxId );
				if( isset($catRow->user_hashname) )
				{
					$metaTags=$catRow->meta_tags;
					$user_classification=$catRow->user_classification;
					if( isset($catRow->mature_content) && trim($catRow->mature_content) != "" && is_numeric($catRow->mature_content) )
					{
						$mature_content=$catRow->mature_content;
					}
					if( isset($catRow->not_safe_for_work) && trim($catRow->not_safe_for_work) != "" && is_numeric($catRow->not_safe_for_work) )
					{
						$not_safe_for_work=$catRow->not_safe_for_work;
					}
					if( isset($catRow->category_image) && trim($catRow->category_image) != "" )
					{
						$tzCatImage=$catRow->category_image;
					}
					
					$boxLinksArr = $this->getUserCategoriesTable()->getBoxLinks( $editBoxId )->toArray();

					$userId = $_SESSION['usersinfo']->userId;
					@unlink( './public/databoxes/'.$userId.'.txt' );
					
					$urlId = 1;
					foreach( $boxLinksArr as $key=>$currBoxLink )
					{
						$boxLinksArr[$key]["current_db_info"] = $currBoxLink["url_id"] . "\t" . $currBoxLink["link"] . "\t" . $currBoxLink["title"] . "\t" . $currBoxLink["image"] . "\t" . $currBoxLink["description"] . "\t" . $currBoxLink["web_author"] . "\t" . $currBoxLink["meta_content"] . "\t" . $currBoxLink["link_validity_status"] . "\t" . $currBoxLink["is_video"] . "\t" . $currBoxLink["iframe_src"];

						$url_id = 0;
						if( $currBoxLink["url_id"] == 0 )
						{
							$url_id = $urlId++;
						}
						else
						{
							$url_id = $currBoxLink["url_id"];
						}
						
						$currentLinkContent =  $boxLinksArr[$key]["current_db_info"] . PHP_EOL;
						$fileName = './public/databoxes/'.$userId.'.txt';
						$fp = fopen( $fileName, 'a' );
						fwrite( $fp,$currentLinkContent );
						fclose( $fp );
						$c++;
					}
					// echo "<pre>";print_r($boxLinksArr);exit;
				}
			}
			else
			{
				if( isset($_SESSION["tzCreateEditBoxId"]) && $_SESSION["tzCreateEditBoxId"] != $editBoxId )
				{
					return $this->redirect()->toUrl($baseUrl.'/contentpage');
				}
			}
		}
		$htmlBook = "";

		$links=array();
		
		if( isset($_FILES['file']['name']) )
		{
			$links=$this->getUrlsArray();
			$htmlBook=$this->bookMarksPopUp($links);
		}
		
		$fetchedLinksCount = count($links);
		
		return new ViewModel(array(				
			'baseUrl' 	=> $baseUrl,
			'basePath' 	=> $basePath,
			'htmlBook' 	=> $htmlBook,
			'editBoxId' 	=> $editBoxId,
			'mature_content' => $mature_content,
			'not_safe_for_work' => $not_safe_for_work,
			'metaTags' 	=> $metaTags,
			'tzCatImage' => $tzCatImage,
			'boxLinksArr' => $boxLinksArr,
			'fetchedLinksCount' => $fetchedLinksCount,
			'countOfLinks' => $c,
			'user_classification' => $user_classification
		));
	}
	// Select databox
	public function selectdataboxAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		return new ViewModel(array(				
			'baseUrl' 	=> $baseUrl,
			'basePath' 	=> $basePath,
		));
	}
	//Public databox
	
	public function publicdataboxAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$editBoxId = 0;
		$metaTags = "";
		$mature_content = 0;
		$not_safe_for_work = 0;
		$tzCatImage = "";
		$boxLinksArr= array();
		$c="00";
		$user_classification="";
		$userId1 = $_SESSION['usersinfo']->userId;
		@unlink( './public/databoxes/'.$userId1.'.txt' );
					
		if($this->params()->fromRoute('boxid', 0)!="")
		{
			$currBoxId=$this->params()->fromRoute('boxid', 0);
			// echo "<pre>"; print_r($currBoxId);exit;

			$editBoxId = $currBoxId;
			if( isset($editBoxId) && trim($editBoxId) != "" && is_numeric($editBoxId) && $editBoxId )
			{
				// echo $editBoxId;exit;
				if( isset($_SESSION["tzCreateEditBoxId"]) && $_SESSION["tzCreateEditBoxId"] != $editBoxId )
				{
					return $this->redirect()->toUrl($baseUrl.'/contentpage');
				}

				$catRow = $this->getUserCategoriesTable()->getEditHighlight( $editBoxId );
				if( isset($catRow->user_hashname) )
				{
					$metaTags=$catRow->meta_tags;
					$user_classification=$catRow->user_classification;
					if( isset($catRow->mature_content) && trim($catRow->mature_content) != "" && is_numeric($catRow->mature_content) )
					{
						$mature_content=$catRow->mature_content;
					}
					if( isset($catRow->not_safe_for_work) && trim($catRow->not_safe_for_work) != "" && is_numeric($catRow->not_safe_for_work) )
					{
						$not_safe_for_work=$catRow->not_safe_for_work;
					}
					if( isset($catRow->category_image) && trim($catRow->category_image) != "" )
					{
						$tzCatImage=$catRow->category_image;
					}
					
					$boxLinksArr = $this->getUserCategoriesTable()->getBoxLinks( $editBoxId )->toArray();
					$userId = $_SESSION['usersinfo']->userId;
					@unlink( './public/databoxes/'.$userId.'.txt' );
					
					$urlId = 1;
					foreach( $boxLinksArr as $key=>$currBoxLink )
					{

						$boxLinksArr[$key]["current_db_info"] = $currBoxLink["url_id"] . "\t" . $currBoxLink["link"] . "\t" . $currBoxLink["title"] . "\t" . $currBoxLink["image"] . "\t" . $currBoxLink["description"] . "\t" . $currBoxLink["web_author"] . "\t" . $currBoxLink["meta_content"] . "\t" . $currBoxLink["link_validity_status"] . "\t" . $currBoxLink["is_video"] . "\t" . $currBoxLink["iframe_src"];

						$url_id = 0;
						if( $currBoxLink["url_id"] == 0 )
						{
							$url_id = $urlId++;
						}
						else
						{
							$url_id = $currBoxLink["url_id"];
						}
						
						$currentLinkContent =  $boxLinksArr[$key]["current_db_info"] . PHP_EOL;
						$fileName = './public/databoxes/'.$userId.'.txt';
						$fp = fopen( $fileName, 'a' );
						fwrite( $fp,$currentLinkContent );
						fclose( $fp );
						$c++;
					}
					 //echo "<pre>";print_r($c);exit;
				}
			}
			else
			{
				if( isset($_SESSION["tzCreateEditBoxId"]) && $_SESSION["tzCreateEditBoxId"] != $editBoxId )
				{
					return $this->redirect()->toUrl($baseUrl.'/contentpage');
				}
			}
		}

		$htmlBook = "";

		$links=array();
		
		if( isset($_FILES['file']['name']) )
		{
			$links=$this->getUrlsArray();
			$htmlBook=$this->bookMarksPopUp($links);
		}
		
		$fetchedLinksCount = count($links);
		
		return new ViewModel(array(				
			'baseUrl' 	=> $baseUrl,
			'basePath' 	=> $basePath,
			'htmlBook' 	=> $htmlBook,
			'editBoxId' 	=> $editBoxId,
			'mature_content' => $mature_content,
			'not_safe_for_work' => $not_safe_for_work,
			'metaTags' 	=> $metaTags,
			'tzCatImage' => $tzCatImage,
			'boxLinksArr' => $boxLinksArr,
			'fetchedLinksCount' => $fetchedLinksCount,
			'countOfLinks' => $c,
			'user_classification' => $user_classification
		));
	}
	
	//Private databox
	public function privatedataboxAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];

		$editBoxId = 0;
		$metaTags = "";
		$mature_content = 0;
		$not_safe_for_work = 0;
		$secret_code="";
		$tzCatImage = "";
		$boxLinksArr= array();
		$c="00";
		$user_classification="";
		$userId1 = $_SESSION['usersinfo']->userId;
		@unlink( './public/databoxes/'.$userId1.'.txt' );
		if($this->params()->fromRoute('boxid', 0)!="")
		{
			$currBoxId=$this->params()->fromRoute('boxid', 0);
			// echo "<pre>"; print_r($currBoxId);exit;

			$editBoxId = $currBoxId;
			if( isset($editBoxId) && trim($editBoxId) != "" && is_numeric($editBoxId) && $editBoxId )
			{
				// echo $editBoxId;exit;
				if( isset($_SESSION["tzCreateEditBoxId"]) && $_SESSION["tzCreateEditBoxId"] != $editBoxId )
				{
					return $this->redirect()->toUrl($baseUrl.'/contentpage');
				}

				$catRow = $this->getUserCategoriesTable()->getEditHighlight( $editBoxId );
				if( isset($catRow->user_hashname) )
				{
					$secret_code=$catRow->secret_code;
					$user_classification=$catRow->user_classification;
					$metaTags=$catRow->meta_tags;
					if( isset($catRow->mature_content) && trim($catRow->mature_content) != "" && is_numeric($catRow->mature_content) )
					{
						$mature_content=$catRow->mature_content;
					}
					if( isset($catRow->not_safe_for_work) && trim($catRow->not_safe_for_work) != "" && is_numeric($catRow->not_safe_for_work) )
					{
						$not_safe_for_work=$catRow->not_safe_for_work;
					}
					if( isset($catRow->category_image) && trim($catRow->category_image) != "" )
					{
						$tzCatImage=$catRow->category_image;
					}
					
					$boxLinksArr = $this->getUserCategoriesTable()->getBoxLinks( $editBoxId )->toArray();

					$userId = $_SESSION['usersinfo']->userId;
					@unlink( './public/databoxes/'.$userId.'.txt' );
					
					$urlId = 1;
					foreach( $boxLinksArr as $key=>$currBoxLink )
					{
						$boxLinksArr[$key]["current_db_info"] = $currBoxLink["url_id"] . "\t" . $currBoxLink["link"] . "\t" . $currBoxLink["title"] . "\t" . $currBoxLink["image"] . "\t" . $currBoxLink["description"] . "\t" . $currBoxLink["web_author"] . "\t" . $currBoxLink["meta_content"] . "\t" . $currBoxLink["link_validity_status"] . "\t" . $currBoxLink["is_video"] . "\t" . $currBoxLink["iframe_src"];

						$url_id = 0;
						if( $currBoxLink["url_id"] == 0 )
						{
							$url_id = $urlId++;
						}
						else
						{
							$url_id = $currBoxLink["url_id"];
						}
						
						$currentLinkContent =  $boxLinksArr[$key]["current_db_info"] . PHP_EOL;
						$fileName = './public/databoxes/'.$userId.'.txt';
						$fp = fopen( $fileName, 'a' );
						fwrite( $fp,$currentLinkContent );
						fclose( $fp );
						$c++;
					}
					// echo "<pre>";print_r($boxLinksArr);exit;
				}
			}
			else
			{
				if( isset($_SESSION["tzCreateEditBoxId"]) && $_SESSION["tzCreateEditBoxId"] != $editBoxId )
				{
					return $this->redirect()->toUrl($baseUrl.'/contentpage');
				}
			}
		}

		$htmlBook = "";

		$links=array();
		
		if( isset($_FILES['file']['name']) )
		{
			$links=$this->getUrlsArray();
			$htmlBook=$this->bookMarksPopUp($links);
		}
		
		$fetchedLinksCount = count($links);
		
		return new ViewModel(array(				
			'baseUrl' 	=> $baseUrl,
			'basePath' 	=> $basePath,
			'htmlBook' 	=> $htmlBook,
			'editBoxId' 	=> $editBoxId,
			'secret_code' 	=> $secret_code,
			'mature_content' => $mature_content,
			'not_safe_for_work' => $not_safe_for_work,
			'metaTags' 	=> $metaTags,
			'tzCatImage' => $tzCatImage,
			'boxLinksArr' => $boxLinksArr,
			'fetchedLinksCount' => $fetchedLinksCount,
			'countOfLinks' => $c,
			'user_classification' => $user_classification
		));
	}
	 public function indexAction()
	{
		// echo "indexAction";exit;
	}

    public function categoryChoiceAction()
	{
		$hashNames="";
		$listSearchCategories = $this->getSearchCategoriesListTable()->listSearchCategory();
		if($listSearchCategories->count()!=0){
			foreach($listSearchCategories as $key=>$search){
				$hashNames[$key]=$search->search_cat_name;
			}		
		}
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		return new ViewModel(array(				
			'baseUrl' 	=> $baseUrl,
			'basePath' 	=> $basePath,
			'listSearchCategories' 	=> $hashNames,
		));
	}
	public function getSearchCategoriesListTable()
    {
        if (!$this->searchCategoriesListTable) {				
            $sm = $this->getServiceLocator();
            $this->searchCategoriesListTable = $sm->get('Databox\Model\SearchCategoriesListFactory');			
        }
        return $this->searchCategoriesListTable;
    }
	/*****
		**** This code has been used Add List Of Categories to database ****
	**/
	/*
	public function fileUploadedAction(){
		$cat_file_name='public/Xlsx/Categoriesoftaggerzz.csv';
		if(file_exists($cat_file_name)){
			   $cat_data = fopen($cat_file_name, "r");
		} else{
			   echo "file not exists";exit;
		}
		$data = fgetcsv($cat_data, 1000, ",");	   
		$count=0;
		$result=array();
		$addSearchList='';
		while (($data = fgetcsv($cat_data, 1000, ",")) !== FALSE){			
			$result[$count]['searchCatName ']=$data[0];	
			$addSearchList = $this->getSearchCategoriesListTable()->addSearchCategory( $result[$count]['searchCatName '] );
			$count++;
		}	
		echo "<pre>";print_r($addSearchList);exit;	
	}
	*/
   public function displayAscendingAction()
	{
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		if( isset($_POST) )
		{
			$categoryType = $_POST['categoryType'];
			$categoryImage = "";
			if($_POST['typeImageCrop']==0){
				$categoryImage = "no_img_uploaded_home.jpg";
			}else{
				$categoryImage = $_POST['imageId'];
			}
			$category=array();
			$category["categoryName"] = $_POST['categoryName'];
			$category["categoryType"] = $_POST['settingId'];
			$category["categoryImage"] = $categoryImage;
			$category["categoryHighlight"] = $_POST['categoryHighlight'];
			//Added By dileep for Fresh Links
			if(isset($_POST['links_cnt']) && $_POST['links_cnt']>=50){
				$category["fresh_databox"] = '1';
				$category["cron_checking"] = '1';
			}else{
				$category["fresh_databox"] = '0';
				$category["cron_checking"] = '0';
			}
			// echo "<pre>";print_r($category);exit;
			$userCatDetails=array();
			$userCatDetails["categoryTitle"] = $_POST['categoryTitle'];
			$userCatDetails["categoryVoteStatus"] = 2;
			$userCatDetails["catHashTag"] = $_POST['catHashTag'];
			$userCatDetails["uniqueCode"] = $_POST['uniqueCode'];
			$userCatDetails["categoryType"] = $_POST['categoryType'];
			$userCatDetails["metaTags"] = $_POST['metaTags'];
			$userCatDetails["hashNote"] = "";
			$userCatDetails["settingId"] = $_POST['settingId'];
			$userCatDetails["matureContent"] = $_POST['matureContent'];
			$userCatDetails["notSafeForWork"] = $_POST['notSafeForWork'];
			$userCatDetails["linkPostFormation"] = $_POST['linkPostFormation'];
			$userCatDetails["user_classification"] = $_POST['description123'];
			

			$userCatDetails["main_category"] = "";
			$userCatDetails["sub_category"] = "";
			if( isset($_POST['main_category']) )
			{
				$userCatDetails["main_category"] = $_POST['main_category'];
			}
			if( isset($_POST['sub_category']) )
			{
				$userCatDetails["sub_category"] = "";
			}

			$userId = $_SESSION['usersinfo']->userId;
			if(isset($_SESSION['usersinfo']->rewardPoints)){
				$rewardMoney=number_format(($_SESSION['usersinfo']->rewardPoints)*0.10, 2) ; 
			}else{ 
				$rewardMoney="00";
			}
			if( isset($_POST['editBoxId']) && is_numeric($_POST['editBoxId']) && $_POST['editBoxId'] > 0 )
			{

				$category_id = $_POST['editBoxId'];
				
				$category["categoryId"] = $_POST['editBoxId'];
				$userCatDetails["category_id"] = $_POST['editBoxId'];

				$updatedCatRs = $this->getCategoryTable()->updateEditedBoxMain( $category,$_POST['typeImageCrop'] );
				$updatedCatDetailsRow = $this->getUserCategoriesTable()->updateEditedBoxDts( $userCatDetails );

				$linkIdsArray=array();
				$categoryLinksRs = $this->getCategoryLinksTable()->getCategoryLinks( $_POST['editBoxId'] );
				foreach( $categoryLinksRs as $currentLinkRow )
				{
					$linkIdsArray[] = $currentLinkRow->category_link_id;
				}
				// echo "<pre>";print_r($linkIdsArray);exit;
				
				foreach( $linkIdsArray as $linkNum=>$linkInfo )
				{
					$linkDetailsDeleteStatus = $this->getLinkDetailsTable()->deleteLinkDetail( $linkInfo );
				}
				
				$categoryLinksDeleteStatus = $this->getCategoryLinksTable()->deleteCategoryLinks( $_POST['editBoxId'] );
				
				$jsPlumbGridDeleteStatus = $this->getJsPlumbGridTable()->deleteCategoryGrid( $_POST['editBoxId'] );
				
				$fhandle = fopen( './public/databoxes/'.$userId.'.txt',"r" );
				if( $fhandle )
				{
					$allUrlsString = stream_get_contents( $fhandle );
					fclose($fhandle);
					$fileUrlsArray = explode( PHP_EOL,$allUrlsString );
					for( $urlNum = 0; $urlNum < count($fileUrlsArray); $urlNum++ )
					{
						if( $fileUrlsArray[$urlNum] != "" )
						{
							$fileCurrentUrlArray = explode( "\t",$fileUrlsArray[$urlNum] );

							$urlId = $fileCurrentUrlArray[0];
							$currentUrl = $fileCurrentUrlArray[1];
							$title = $fileCurrentUrlArray[2];
							$image = $fileCurrentUrlArray[3];
							$description = $fileCurrentUrlArray[4];
							$author = $fileCurrentUrlArray[5];
							$keywords = $fileCurrentUrlArray[6];
							$linkValidityStatus = $fileCurrentUrlArray[7];
							$isVideo = $fileCurrentUrlArray[8];
							$iframeSrc = $fileCurrentUrlArray[9];

							$category_link_id = $this->getCategoryLinksTable()->addLinkMains( $category_id,$currentUrl,$linkValidityStatus );
							$link_details_id = $this->getLinkDetailsTable()->addLinkDetails( $category_link_id,$title,$image,$description,$author,$keywords,$urlId,$isVideo,$iframeSrc );
						}
					}
					@unlink( './public/databoxes/'.$userId.'.txt' );
					// echo "<pre>";print_r( $fileUrlsArray );
				}
				else
				{
					fclose($fhandle);
				}
			}
			else
			{

				$category_id = $this->getCategoryTable()->addCategory( $category );
				$userCatDetails["category_id"] = $category_id;
				$user_category_id = $this->getUserCategoriesTable()->addUserCategory( $userCatDetails,$_POST['categoryHighlight'] );
				$databoxViewsTable=	$this->getDataboxViewsTable()->addUserCategoryId( $category_id );

				$fhandle = fopen( './public/databoxes/'.$userId.'.txt',"r" );
				if( $fhandle )
				{
					$allUrlsString = stream_get_contents( $fhandle );
					fclose($fhandle);
					$fileUrlsArray = explode( PHP_EOL,$allUrlsString );
					for( $urlNum = 0; $urlNum < count($fileUrlsArray); $urlNum++ )
					{
						if( $fileUrlsArray[$urlNum] != "" )
						{
							$fileCurrentUrlArray = explode( "\t",$fileUrlsArray[$urlNum] );

							$urlId = $fileCurrentUrlArray[0];
							$currentUrl = $fileCurrentUrlArray[1];
							$title = $fileCurrentUrlArray[2];
							$image = $fileCurrentUrlArray[3];
							$description = $fileCurrentUrlArray[4];
							$author = $fileCurrentUrlArray[5];
							$keywords = $fileCurrentUrlArray[6];
							$linkValidityStatus = $fileCurrentUrlArray[7];
							$isVideo = $fileCurrentUrlArray[8];
							$iframeSrc = $fileCurrentUrlArray[9];

							$category_link_id = $this->getCategoryLinksTable()->addLinkMains( $category_id,$currentUrl,$linkValidityStatus );
							$link_details_id = $this->getLinkDetailsTable()->addLinkDetails( $category_link_id,$title,$image,$description,$author,$keywords,$urlId,$isVideo,$iframeSrc );
						}
					}
					//echo "<pre>";print_r( $fileUrlsArray );exit;

					@unlink( './public/databoxes/'.$userId.'.txt' );
					// echo "<pre>";print_r( $fileUrlsArray );
				}
				else
				{
					fclose($fhandle);
				}
			}
				if( isset($_POST['email']) )
				{
					if(isset($_SESSION["userTotBoxes"])){
						$dxCount = $_SESSION["userTotBoxes"] + 1;
						$databoxesCount = 10 - $dxCount;
					}
					else
					{
						$databoxesCount=0;
					}
					
					global $databoxCreatedSubject;
					global $databoxCreatedMessage;
					$databoxCreatedMessage = str_replace("<FULLNAME>",$_SESSION['usersinfo']->displayName, $databoxCreatedMessage);
					$databoxCreatedMessage = str_replace("<MESSAGE>",$databoxesCount, $databoxCreatedMessage);
					$databoxCreatedMessage = str_replace("<CATEGORYNAME>",$_POST['categoryName'], $databoxCreatedMessage);
					$databoxCreatedMessage = str_replace("<HASHNAME>",$_POST['catHashTag'], $databoxCreatedMessage);
					$databoxCreatedMessage = str_replace("<LINKS>",$_SESSION['usersinfo']->getTotalLinks, $databoxCreatedMessage);
					$databoxCreatedMessage = str_replace("<KEYWORDS>",$_POST['metaTags'], $databoxCreatedMessage);
					$databoxCreatedMessage = str_replace("<POINTS>",$_SESSION['usersinfo']->rewardPoints, $databoxCreatedMessage);
					$databoxCreatedMessage = str_replace("<AMOUNT>",$rewardMoney, $databoxCreatedMessage);
					if( $_POST['categoryType'] == 0 )
					{
						$databoxCreatedMessage = str_replace("<SECURITYCODE>",'Your Unique Code :&nbsp;&nbsp;<b style="font-size:1.50em;">'.$_POST['uniqueCode'].'</b>', $databoxCreatedMessage);
					}
					if($_POST['email']!=""){
						$to=$_POST['email'];
					}else{
						$to=$_SESSION['usersinfo']->email;
					}
					$sentMail=sendMail($to,$databoxCreatedSubject,$databoxCreatedMessage);
				}
			
			
			$catHashTag=substr( $_POST['catHashTag'], 1 );
			$categoryTitles=str_replace('-','~',$_POST['categoryTitle']);
			$categoryTitle=str_replace(' ','-',$categoryTitles);
			$imageNumberr=explode('.',$categoryImage);
			$imageNumber=$imageNumberr[0];
			if($_POST['settingId']==3){
				$routeUrl='post-vertical';
			}else{
				$routeUrl='post-horizontal';
			}
			return $this->redirect()->toUrl($routeUrl.'/'.$category_id.'+'.$imageNumber.'+'.$catHashTag.'+'.$categoryTitle);
		}
	}

    public function viewAscendingAction()
	{
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$databoxLinksGrid=array();
		$categoryId = 0;
		$hashName="";
		$categoryTitle="";
		if($this->params()->fromRoute('id', 0)!="")
		{
			$params=$this->params()->fromRoute('id', 0);
			$paramss=explode("-",$params);
			$categoryId=$paramss[0];
			if( isset($paramss[1]) )
			{
				$hashName='#'.$paramss[1];
				$categoryTitle=str_replace('~','-',$paramss[2]);
			}
			else
			{
				$catRow = $this->getUserCategoriesTable()->getEditHighlight( $categoryId );
				$hashName="#".substr($catRow->user_hashname,1);
				$categoryTitle=$catRow->category_title;
			}
		}

		$databoxLinksGridRs = $this->getLinkDetailsTable()->getDataboxGrid( $categoryId );
		foreach( $databoxLinksGridRs as $currentLinkRow )
		{
			$databoxLinksGrid[] = (array)$currentLinkRow;
		}
		//echo '<pre>'; print_r($databoxLinksGrid['link']); exit;
		return new ViewModel(array(				
			'baseUrl' 			=> 	$baseUrl,
			'basePath' 			=> 	$basePath,
			'options'			=>	$this->getOptions(),
			'databoxLinksGrid' 	=> 	$databoxLinksGrid,
			'hashName' 			=> 	$hashName,
			'categoryTitle' 	=> 	$categoryTitle
		));
	}

    public function highlightAscendingAction()
	{
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];

		if( isset($_POST) )
		{
			// echo "<pre>";print_r($_POST);exit;
			if($_POST['typeImageCrop']==0){
				move_uploaded_file( $_FILES['highlightImageFile']['tmp_name'],'./public/images/project/categoryImages/'.$_FILES['highlightImageFile']['name'] );
				$highlightImage = $_FILES['highlightImageFile']['name'];
			}else{
				$highlightImage = $_POST['imageId'];
			}
			$category=array();
			$category["categoryName"] = "";
			$category["categoryType"] = $_POST['settingId'];
			$category["categoryImage"] = $highlightImage;
			$category["categoryHighlight"] = 2;
			//echo "<pre>";print_r($category);exit;
			$userCatDetails=array();
			$userCatDetails["categoryTitle"] = $_POST['highlightTitle'];
			$userCatDetails["categoryVoteStatus"] = 2;
			$userCatDetails["catHashTag"] = $_POST['highlightHashTag'];
			$userCatDetails["uniqueCode"] = "";
			$userCatDetails["categoryType"] = "";
			$userCatDetails["metaTags"] = $_POST['highlightKeywords'];
			$userCatDetails["hashNote"] = "";
			$userCatDetails["settingId"] = $_POST['settingId'];

			$category_id = $this->getCategoryTable()->addCategory( $category );
			$userCatDetails["category_id"] = $category_id;
			$user_category_id = $this->getUserCategoriesTable()->addUserCategory( $userCatDetails );

			$userId = $_SESSION['usersinfo']->userId;
			$fhandle = fopen( './public/databoxes/'.$userId.'.txt',"r" );
			if( $fhandle )
			{
				$allUrlsString = stream_get_contents( $fhandle );
				fclose($fhandle);
				$fileUrlsArray = explode( PHP_EOL,$allUrlsString );
				for( $urlNum = 0; $urlNum < count($fileUrlsArray); $urlNum++ )
				{
					if( $fileUrlsArray[$urlNum] != "" )
					{
						$fileCurrentUrlArray = explode( "\t",$fileUrlsArray[$urlNum] );

						$urlId = $fileCurrentUrlArray[0];
						$currentUrl = $fileCurrentUrlArray[1];
						$title = $fileCurrentUrlArray[2];
						$image = $fileCurrentUrlArray[3];
						$description = $fileCurrentUrlArray[4];
						$author = $fileCurrentUrlArray[5];
						$keywords = $fileCurrentUrlArray[6];
						$linkValidityStatus = $fileCurrentUrlArray[7];
						$isVideo = $fileCurrentUrlArray[8];
						$iframeSrc = $fileCurrentUrlArray[9];

						$category_link_id = $this->getCategoryLinksTable()->addLinkMains( $category_id,$currentUrl,$linkValidityStatus );
						$link_details_id = $this->getLinkDetailsTable()->addLinkDetails( $category_link_id,$title,$image,$description,$author,$keywords,$urlId,$isVideo,$iframeSrc );
					}
				}
				@unlink( './public/databoxes/'.$userId.'.txt' );
				// echo "<pre>";print_r( $fileUrlsArray );
			}
			else
			{
				fclose($fhandle);
			}

			$highlightHashTag=substr( $_POST['highlightHashTag'], 1 );
			$categoryTitles=str_replace('-','~',$_POST['highlightTitle']);
			$categoryTitle=str_replace(' ','-',$categoryTitles);
			$imageNumberr=explode('.',$highlightImage);
			$imageNumber=$imageNumberr[0];
			if($_POST['settingId']==3){
				$routeUrl='post-vertical';
			}else{
				$routeUrl='post-horizontal';
			}
			return $this->redirect()->toUrl($routeUrl.'/'.$category_id.'+'.$imageNumber.'+'.$highlightHashTag.'+'.$categoryTitle);
		}
	}

    public function mailAccessingDetailsAction()
	{
		// echo "mailAccessingDetailsAction";exit;
		
		if( isset($_POST) )
		{
			global $accessDetailsSubject;                                
			global $accessDetailsMessage;

			$accessDetailsMessage = str_replace("<FULLNAME>",$_SESSION['usersinfo']->displayName, $accessDetailsMessage);
			$accessDetailsMessage = str_replace("<MESSAGE>",'Below are your Private Data box Accessing Details :', $accessDetailsMessage);
			$accessDetailsMessage = str_replace("<CATEGORYNAME>",$_POST['categoryName'], $accessDetailsMessage);
			$accessDetailsMessage = str_replace("<SECURITYCODE>",$_POST['uniqueCode'], $accessDetailsMessage);
			$accessDetailsMessage = str_replace("<HASHNAME>",$_POST['hashTag'], $accessDetailsMessage);
			$to=$_POST['mailDetailsTo'];       
			$sentMail=sendMail($to,$accessDetailsSubject,$accessDetailsMessage);
			echo "1";exit;
		}
		else
		{
			echo "0";exit;
		}
		
	}

    public function editHighlightAscendingAction()
	{
		// echo "editHighlightAscendingAction";exit;
		// echo "<pre>";print_r($_SESSION['urls']);exit;

		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];

		if( isset($_POST) )
		{
			if($_POST['imageId']!=0){
				unlink('./public/images/project/categoryImages/'.$_POST['imageIdOnload']);
			}
			$category=array();
			$category["categoryId"] = $_POST['categoryId'];
			$category["categoryType"] = $_POST['settingId'];
			// echo "<pre>";print_r($category);exit;

			if( isset($_POST['hltImageSelected']) && $_POST['hltImageSelected'] == 1 )
			{
				if($_POST['typeImageCrop']==0){
					move_uploaded_file( $_FILES['highlightImageFile']['tmp_name'],'./public/images/project/categoryImages/'.$_FILES['highlightImageFile']['name'] );
					$highlightImage = $_FILES['highlightImageFile']['name'];
					$category["categoryImage"] = $highlightImage;
				}else{
					$highlightImage = $_POST['imageId'];
					$category["categoryImage"] = $_POST['imageId'];
				}
			}else{
				$highlightImage = $_POST['imageIdd'];
			}

			$userCatDetails=array();
			$userCatDetails["categoryId"] = $_POST['categoryId'];
			$userCatDetails["categoryTitle"] = $_POST['highlightTitle'];
			$userCatDetails["catHashTag"] = $_POST['highlightHashTag'];
			$userCatDetails["metaTags"] = $_POST['highlightKeywords'];
			$userCatDetails["settingId"] = $_POST['settingId'];
			
			$updatedCatRs = $this->getCategoryTable()->updateHighlight( $category );
			//echo "<pre>";print_r($updatedCatRs);exit;
			$updatedCatDetailsRow = $this->getUserCategoriesTable()->updateHighlight( $userCatDetails );
			
			if( isset( $_POST['editHighLinksChanged'] ) && $_POST['editHighLinksChanged'] == 1 )
			{
				$linkIdsArray=array();
				$categoryLinksRs = $this->getCategoryLinksTable()->getCategoryLinks( $_POST['categoryId'] );
				foreach( $categoryLinksRs as $currentLinkRow )
				{
					$linkIdsArray[] = $currentLinkRow->category_link_id;
				}
				// echo "<pre>";print_r($linkIdsArray);exit;
				
				foreach( $linkIdsArray as $linkNum=>$linkInfo )
				{
					$linkDetailsDeleteStatus = $this->getLinkDetailsTable()->deleteLinkDetail( $linkInfo );
				}
				
				$categoryLinksDeleteStatus = $this->getCategoryLinksTable()->deleteCategoryLinks( $_POST['categoryId'] );
				
				$jsPlumbGridDeleteStatus = $this->getJsPlumbGridTable()->deleteCategoryGrid( $_POST['categoryId'] );

				$userId = $_SESSION['usersinfo']->userId;
				$fhandle = fopen( './public/databoxes/'.$userId.'.txt',"r" );
				if( $fhandle )
				{
					$allUrlsString = stream_get_contents( $fhandle );
					fclose($fhandle);
					$fileUrlsArray = explode( PHP_EOL,$allUrlsString );
					for( $urlNum = 0; $urlNum < count($fileUrlsArray); $urlNum++ )
					{
						if( $fileUrlsArray[$urlNum] != "" )
						{
							$fileCurrentUrlArray = explode( "\t",$fileUrlsArray[$urlNum] );

							$urlId = $fileCurrentUrlArray[0];
							$currentUrl = $fileCurrentUrlArray[1];
							$title = $fileCurrentUrlArray[2];
							$image = $fileCurrentUrlArray[3];
							$description = $fileCurrentUrlArray[4];
							$author = $fileCurrentUrlArray[5];
							$keywords = $fileCurrentUrlArray[6];
							$linkValidityStatus = $fileCurrentUrlArray[7];
							$isVideo = $fileCurrentUrlArray[8];
							$iframeSrc = $fileCurrentUrlArray[9];

							$category_link_id = $this->getCategoryLinksTable()->addLinkMains( $_POST['categoryId'],$currentUrl,$linkValidityStatus );
							$link_details_id = $this->getLinkDetailsTable()->addLinkDetails( $category_link_id,$title,$image,$description,$author,$keywords,$urlId,$isVideo,$iframeSrc );
						}
					}
					@unlink( './public/databoxes/'.$userId.'.txt' );
					// echo "<pre>";print_r( $fileUrlsArray );
				}
				else
				{
					fclose($fhandle);
				}
			}
			
			$highlightHashTag=substr( $_POST['highlightHashTag'], 1 );
			$categoryTitles=str_replace('-','~',$_POST['highlightTitle']);
			$categoryTitle=str_replace(' ','-',$categoryTitles);
			$imageNumberr=explode('.',$highlightImage);
			$imageNumber=$imageNumberr[0];
			if($_POST['settingId']==3){
				$routeUrl='post-vertical';
			}else{
				$routeUrl='post-horizontal';
			}
			return $this->redirect()->toUrl($routeUrl.'/'.$_POST['categoryId'].'+'.$imageNumber.'+'.$highlightHashTag.'+'.$categoryTitle);
		}
	}

    public function predefinedBothAction()
	{
		// echo "predefinedBothAction";exit;
		
		$userId = $_SESSION['usersinfo']->userId;
		@unlink( './public/databoxes/'.$userId.'.txt' );
		
		if( isset($_POST) )
		{
			// echo "<pre>";print_r($_POST);exit;
		}

        $categoryName="";
		if( isset($_POST['categoryName']) && $_POST['categoryName'] !="" )
		{
			$categoryName=$_POST['categoryName'];
		}

		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		return new ViewModel(array(				
			'baseUrl' 	=> $baseUrl,
			'basePath' 	=> $basePath,
			'categoryName' 	=> $categoryName,
		));
	}

    public function userdefinedBothAction()
	{
		// echo "userdefinedBothAction";exit;
		
		$userId = $_SESSION['usersinfo']->userId;
		@unlink( './public/databoxes/'.$userId.'.txt' );
		
		if( isset($_POST) )
		{
			// echo "<pre>";print_r($_POST);exit;
		}

        $userCatName="";
		if( isset($_POST['userCatName']) && $_POST['userCatName'] !="" )
		{
			$userCatName=$_POST['userCatName'];
		}
        $hashTag="";
		if( isset($_POST['hashTag']) && $_POST['hashTag'] !="" )
		{
			$hashTag=$_POST['hashTag'];
		}
		$main_category="";
		if( isset($_POST['main_category']) && $_POST['main_category'] !="" )
		{
			$main_category=$_POST['main_category'];
		}
		$sub_category="";
		if( isset($_POST['sub_category']) && $_POST['sub_category'] !="" )
		{
			$sub_category=$_POST['sub_category'];
		}
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		return new ViewModel(array(				
			'baseUrl' 			=> $baseUrl,
			'basePath' 			=> $basePath,
			'userCatName' 		=> $userCatName,
			'hashTag' 			=> $hashTag,
			'main_category' 	=> $main_category,
			'sub_category' 		=> $sub_category
		));
	}

    public function userdefinedBookmarksAction()
	{
		// echo "userdefinedBookmarksAction";exit;
		
		$userId = $_SESSION['usersinfo']->userId;
		@unlink( './public/databoxes/'.$userId.'.txt' );
		
		$links=array();
        $userCatName="";
		$hashTag="";
		$main_category="";
		$sub_category="";
		if( isset($_POST) )
		{
			// echo "<pre>";print_r($_POST);exit;
			if( isset($_POST['userCatName']) && $_POST['userCatName'] !="" )
			{
				$userCatName=$_POST['userCatName'];
			}
			if( isset($_POST['hashTag']) && $_POST['hashTag'] !="" )
			{
				$hashTag=$_POST['hashTag'];
			}
			if( isset($_POST['main_category']) && $_POST['main_category'] !="" )
			{
				$main_category=$_POST['main_category'];
			}
			if( isset($_POST['sub_category']) && $_POST['sub_category'] !="" )
			{
				$sub_category=$_POST['sub_category'];
			}
			$links=$this->getUrlsArray();
			//echo "<pre>";print_r($links);exit;
		}
		$htmlBook=$this->bookMarksPopUp($links);
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		return new ViewModel(array(				
			'baseUrl' 		=> $baseUrl,
			'basePath' 		=> $basePath,
			'userCatName' 	=> $userCatName,
			'hashTag' 		=> $hashTag,
			//'links' 		=> $links,
			'htmlBook' 		=> $htmlBook,
			'main_category' 	=> $main_category,
			'sub_category' 		=> $sub_category
		));
	}

	public function checkDataboxesCountAction()
	{
		// echo checkDataboxesCountAction;exit;
		$userId = 0;
		if( isset($_SESSION['usersinfo']) )
		{
			$userId = $_SESSION['usersinfo']->userId;
		}

		$databoxesCountRow = $this->getUserCategoriesTable()->getUserDataboxesCount( $userId );
		
		$_SESSION["userTotBoxes"] = $databoxesCountRow->userDataboxesCount;
		$databoxesLimitReached = 0;
		if( $databoxesCountRow->userDataboxesCount >= 10 )
		{
			$databoxesLimitReached = 1;
		}
		if( $_SESSION['usersinfo']->email == "taggerzz.com@gmail.com" )
		{
			$databoxesLimitReached = 0;
		}
		
		$result = new JsonModel(array(
			'databoxesLimitReached' 	=>  $databoxesLimitReached,
		));	
		return $result;
	}
 
 public function highlightsBothAction()
	{
		// echo "highlightsBothAction";exit;
		
		$userId = $_SESSION['usersinfo']->userId;
		@unlink( './public/databoxes/'.$userId.'.txt' );
		
		if( isset($_POST) )
		{
			// echo "<pre>";print_r($_POST);exit;
		}

		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		return new ViewModel(array(				
			'baseUrl' 	=> $baseUrl,
			'basePath' 	=> $basePath,
		));
	}

    public function enlistHighlightsAction()
	{
		$relevanceWorthVoteTable=$this->getServiceLocator()->get('Databox\Model\RelevanceWorthVoteFactory');
		$userHighlightsArray=array();
		if( isset($_SESSION['usersinfo']->userId) )
		{
			$userId = $_SESSION['usersinfo']->userId;
		}
		else
		{
			$userId = $_SERVER['REMOTE_ADDR'];
		}
		
		$userHighlightsRs = $this->getUserCategoriesTable()->getAllUsersHighlights();

		foreach( $userHighlightsRs as $currentHighlightRow )
		{
			$userHighlightsArray[] = (array)$currentHighlightRow;
		}
		foreach( $userHighlightsArray as $key=>$values )
		{
			$linkArray = array();	
			$linkTitlesArray = array();	
	
			$categoryLinksRs = $this->getCategoryLinksTable()->getEditCategoryLinks( $values['category_id'] );

			$linkTitlesArray = $this->getLinkDetailsTable()->getCategoryLinkTitles( $values['category_id'] )->toArray();

			$getVoteUpDown = $relevanceWorthVoteTable->getVoteUpDown( $values['category_id'],$userId );
			if($getVoteUpDown->count()!=0){
				$userHighlightsArray[$key]["voteUp"] = $getVoteUpDown->current()->voteUp;
			}else{
				$userHighlightsArray[$key]["voteUp"] = '2';
			}

			foreach( $categoryLinksRs as $currentLinkRow )
			{
				$linkArray[] = (array)$currentLinkRow;
			}
			$userHighlightsArray[$key]["links"] = $linkArray;
			$userHighlightsArray[$key]["linkTitles"] = $linkTitlesArray;
		}

		//echo '<pre>'; print_r($userHighlightsArray); exit;

		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];

		return new ViewModel(array(				
			'baseUrl' 				=> $baseUrl,
			'basePath' 				=> $basePath,
			'options'				=>	$this->getOptions(),
			'userHighlightsArray' 	=> $userHighlightsArray,
		));
	}

    public function highlightsSearchAjaxAction()
	{
		if( isset($_POST) )
		{
			$hgltsSearchTerm = "";
			if( isset($_POST['hgltsSearchTerm']) && $_POST['hgltsSearchTerm'] !="" )
			{
				$hgltsSearchTerm=$_POST['hgltsSearchTerm'];
				
				$relevanceWorthVoteTable=$this->getServiceLocator()->get('Databox\Model\RelevanceWorthVoteFactory');
				$userHighlightsArray=array();
				if( isset($_SESSION['usersinfo']->userId) )
				{
					$userId = $_SESSION['usersinfo']->userId;
				}
				else
				{
					$userId = $_SERVER['REMOTE_ADDR'];
				}
			
				$userHighlightsRs = $this->getUserCategoriesTable()->getSearchedHighlights( $hgltsSearchTerm );
				foreach( $userHighlightsRs as $currentHighlightRow )
				{
					$userHighlightsArray[] = (array)$currentHighlightRow;
				}
				foreach( $userHighlightsArray as $key=>$values )
				{
					$linkArray = array();	

					$categoryLinksRs = $this->getCategoryLinksTable()->getCategoryLinks( $values['category_id'] );

					$linkTitlesArray = $this->getLinkDetailsTable()->getCategoryLinkTitles( $values['category_id'] )->toArray();

					$getVoteUpDown = $relevanceWorthVoteTable->getVoteUpDown( $values['category_id'],$userId );
					if($getVoteUpDown->count()!=0){
						$userHighlightsArray[$key]["voteUp"] = $getVoteUpDown->current()->voteUp;
					}else{
						$userHighlightsArray[$key]["voteUp"] = '2';
					}
					foreach( $categoryLinksRs as $currentLinkRow )
					{
						$linkArray[] = (array)$currentLinkRow;
					}

					$userHighlightsArray[$key]["links"] = $linkArray;
					$userHighlightsArray[$key]["linkTitles"] = $linkTitlesArray;
				}
				
			}

			//echo '<pre>'; print_r($userHighlightsArray); exit;

			$baseUrls = $this->getServiceLocator()->get('config');
			$baseUrlArr = $baseUrls['urls'];
			$baseUrl = $baseUrlArr['baseUrl'];
			$basePath = $baseUrlArr['basePath'];

			$view = new ViewModel(array(				
				'baseUrl' 				=> $baseUrl,
				'basePath' 				=> $basePath,
				'userHighlightsArray' 	=> $userHighlightsArray,
			));
			return $view->setTerminal(true);
		}
	}

    public function voteOnHighlightAction()
	{
		if( isset($_POST['categoryId']) && $_POST['categoryId']!='')
		{
			$relevanceWorthVoteTable=$this->getServiceLocator()->get('Databox\Model\RelevanceWorthVoteFactory');	
			$type = $_POST['type'];
			$categoryId = $_POST['categoryId'];
			$rw_th = $_POST['rw_th'];
			if(isset($_SESSION['usersinfo']->userId)){
				$userId=$_SESSION['usersinfo']->userId;
			}else{
				
				$userId=$_SERVER['REMOTE_ADDR'];
			}
			$checkInsert=$relevanceWorthVoteTable->checkUserLikeAndDislike( $categoryId,$userId);
			if($checkInsert!=""){
				
				$LikeValue=$checkInsert->voteUp;
				$disLikeValue=$checkInsert->voteDown;
				if($type==1){
					if($LikeValue==1){
						$voteUp   = 0;
						$voteDown = 0;
					}else if($LikeValue==0){
						$voteUp   = 1;
						$voteDown = 0;
					}
				}else if($type==0){
					if($disLikeValue==1){
						$voteUp   = 0;
						$voteDown = 0;
					}else if($disLikeValue==0){
						$voteUp   = 0;
						$voteDown = 1;
					}
				}
				
				$updatedRecord = $relevanceWorthVoteTable->updateLikeAndDislike( $categoryId,$userId,$voteUp,$voteDown);
			}else{
				if($type==1){
					$voteUp=1;
					$voteDown=0;
				}else if($type==0){
					$voteUp=0;
					$voteDown=1;
				}
				
				$updatedRow = $relevanceWorthVoteTable->voteOnHighlight( $categoryId,$userId,$type,$rw_th);
			}
			$votePerLikeDisCount = $relevanceWorthVoteTable->getDataboxVotesUpDownCount($categoryId);
			if(count($votePerLikeDisCount)>0){
				$result = new JsonModel(array(	
					'output'        => '1',
					'countUp' 	    =>  $votePerLikeDisCount->countUp,
					'countDown'     =>  $votePerLikeDisCount->countDown,
					'voteUp'        =>  $voteUp,
					'voteDown'      =>  $voteDown
				));	
				return $result;
			}
		}
	}

    public function voteOnRelevanceAction()
	{
		if( isset($_POST['categoryId']) )
		{
			$relevanceWorthVoteTable=$this->getServiceLocator()->get('Databox\Model\RelevanceWorthVoteFactory');	
			$type = $_POST['newVoteValue'];
			$categoryId = $_POST['categoryId'];
			if(isset($_SESSION['usersinfo']->userId)){
				$userId=$_SESSION['usersinfo']->userId;
			}else{
				$userId=$_SERVER['REMOTE_ADDR'];
			}
			$updatedRow = $relevanceWorthVoteTable->voteOnRelevance( $categoryId,$userId,$type );
		}
		exit;
	}

    public function voteOnWorthAction()
	{
		if( isset($_POST['categoryId']) )
		{
			$relevanceWorthVoteTable=$this->getServiceLocator()->get('Databox\Model\RelevanceWorthVoteFactory');	
			$type = $_POST['newVoteValue'];
			$categoryId = $_POST['categoryId'];
			if(isset($_SESSION['usersinfo']->userId)){
				$userId=$_SESSION['usersinfo']->userId;
			}else{
				
				$userId=$_SERVER['REMOTE_ADDR'];
			}
			$updatedRow = $relevanceWorthVoteTable->voteOnWorth( $categoryId,$userId,$type );
		}
		exit;
	}

    public function deleteHighlightAction()
	{
		// echo "deleteHighlightAction";exit;

		if( isset($_POST['categoryId']) )
		{
			$categoryId = $_POST['categoryId'];

			$updatedRow = $this->getUserCategoriesTable()->deleteHighlight( $categoryId );
		}
		exit;
	}

    public function editHighlightAction()
	{
		// echo "editHighlightAction";exit;

		$userId = $_SESSION['usersinfo']->userId;
		@unlink( './public/databoxes/'.$userId.'.txt' );

		if( isset($_POST['categoryId']) )
		{
			$categoryId = $_POST['categoryId'];

			$editHighlightRow = $this->getUserCategoriesTable()->getEditHighlight( $categoryId );

			$linksArray = array();
			$linksArray = $this->getLinkDetailsTable()->getCatLinksMainsDetails( $editHighlightRow->category_id )->toArray();

			$urlId = 1;
			foreach( $linksArray as $key=>$currentLink )
			{
				// echo "<pre>";print_r( $key );echo "#";print_r( $currentLink );
				$url_id = 0;
				if( $currentLink["url_id"] == 0 )
				{
					$url_id = $urlId++;
				}
				else
				{
					$url_id = $currentLink["url_id"];
				}
				
				$currentLinkContent =  $url_id . "\t" . $currentLink["link"] . "\t" . $currentLink["title"] . "\t" . $currentLink["image"] . "\t" . $currentLink["description"] . "\t" . $currentLink["web_author"] . "\t" . $currentLink["meta_content"] . "\t" . $currentLink["link_validity_status"] . "\t" . $currentLink["is_video"] . "\t" . $currentLink["iframe_src"] . PHP_EOL;
				$fileName = './public/databoxes/'.$userId.'.txt';
				$fp = fopen( $fileName, 'a' );
				fwrite( $fp,$currentLinkContent );
				fclose( $fp );
			}
			
			$baseUrls = $this->getServiceLocator()->get('config');
			$baseUrlArr = $baseUrls['urls'];
			$baseUrl = $baseUrlArr['baseUrl'];
			$basePath = $baseUrlArr['basePath'];
			return new ViewModel(array(				
				'baseUrl' 			=> $baseUrl,
				'basePath' 			=> $basePath,
				'editHighlightRow' 	=> $editHighlightRow,
				'linksArray'		=> $linksArray,
			));
		}
		exit;
	}

    public function checkUrlAttributesAction()
	{
		// echo "checkUrlAttributesAction";exit;

		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];

		$currentUrl = "";
		$urlId = 1;
		if( isset($_POST['currentUrl']) )
		{
			$currentUrl = $_POST['currentUrl'];
		}
		if( isset($_POST['urlId']) )
		{
			$urlId = $_POST['urlId'];
		}
	
		$request_url = $currentUrl;
		// echo $request_url; exit;
		

		SetUp::init();

		$text = $request_url;
		$imageQuantity = -1;
		$text = " " . str_replace("\n", " ", $text);
		$header = "";

		$linkPreview = new LinkPreview();
		$answer = $linkPreview->crawl($text, $imageQuantity, $header);

		SetUp::finish();
		
		$title = $answer["title"];
		$description = $answer["description"];
		$image1 = $answer["images"];
						//echo "<pre>";print_r($image1);exit;

		$imageArray=explode('|',$image1);
		$image=$imageArray[0];

		$site_image = "";
		
		$isVideo = $answer["video"];
		$iframeSrc = $answer["videoIframe"];

		if( $image == "" )
		{
			$site_image = "no Image";
			$image = $basePath . "/images/social_media/customization_no_img.jpg";
		}
		$otherLogin = false;
		
		$extensionsImgFlag = false;
		if( trim($title) != "" && trim($description) != "" )
		{
			$extensionsImgFlag = true;
		}
		
		if( $description == "" )
		{
			$description = "no Description";
		}
		$description = str_replace( PHP_EOL,"",$description );
		
		if( $image == "pdf"  )
		{
			$otherLogin = true;
			if( $extensionsImgFlag )
			{
				$image = $basePath . "/images/social_media/customization_no_img.jpg";
			}
			else
			{
				$image = $basePath . "/images/montage_default_images/pdf_file.png";
			}
		}
		else if( $image == "doc"  )
		{
			$otherLogin = true;
			if( $extensionsImgFlag )
			{
				$image = $basePath . "/images/social_media/customization_no_img.jpg";
			}
			else
			{
				$image = $basePath . "/images/montage_default_images/doc_file.png";
			}
		}
		else if( $image == "txt"  )
		{
			$otherLogin = true;
			if( $extensionsImgFlag )
			{
				$image = $basePath . "/images/social_media/customization_no_img.jpg";
			}
			else
			{
				$image = $basePath . "/images/montage_default_images/text_file.png";
			}
		}
		else if( $image == "xls"  )
		{
			$otherLogin = true;
			if( $extensionsImgFlag )
			{
				$image = $basePath . "/images/social_media/customization_no_img.jpg";
			}
			else
			{
				$image = $basePath . "/images/montage_default_images/xls_file.png";
			}
		}
		else if( $image == "ppt"  )
		{
			$otherLogin = true;
			if( $extensionsImgFlag )
			{
				$image = $basePath . "/images/social_media/customization_no_img.jpg";
			}
			else
			{
				$image = $basePath . "/images/montage_default_images/ppt_file.png";
			}
		}
		else if( $image == "zip"  )
		{
			$otherLogin = true;
			if( $extensionsImgFlag )
			{
				$image = $basePath . "/images/social_media/customization_no_img.jpg";
			}
			else
			{
				$image = $basePath . "/images/montage_default_images/zip_file.png";
			}
		}
		else if( $image == "torrent"  )
		{
			$otherLogin = true;
			if( $extensionsImgFlag )
			{
				$image = $basePath . "/images/social_media/customization_no_img.jpg";
			}
			else
			{
				$image = $basePath . "/images/montage_default_images/utorrent_file.png";
			}
		}
		else if( $image == "css"  )
		{
			$otherLogin = true;
			if( $extensionsImgFlag )
			{
				$image = $basePath . "/images/social_media/customization_no_img.jpg";
			}
			else
			{
				$image = $basePath . "/images/montage_default_images/css_file.png";
			}
		}
		else if( $image == "esp"  )
		{
			$otherLogin = true;
			if( $extensionsImgFlag )
			{
				$image = $basePath . "/images/social_media/customization_no_img.jpg";
			}
			else
			{
				$image = $basePath . "/images/montage_default_images/eps_file.png";
			}
		}
		else if( $image == "cdr"  )
		{
			$otherLogin = true;
			if( $extensionsImgFlag )
			{
				$image = $basePath . "/images/social_media/customization_no_img.jpg";
			}
			else
			{
				$image = $basePath . "/images/montage_default_images/cdr_file.png";
			}
		}
		else if( $image == "ai"  )
		{
			$otherLogin = true;
			if( $extensionsImgFlag )
			{
				$image = $basePath . "/images/social_media/customization_no_img.jpg";
			}
			else
			{
				$image = $basePath . "/images/montage_default_images/adobe_Illustrator_file.png";
			}
		}
		else 
		{
			$imageType ='';		
$urlsArrayy = array('2ch.net','4shared.com','6pm.com','9gag.com','39.net','163.com','360.cn','about.com','abs-cbnnews.com',
'accuweather.com','adcash.com','addthis.com','adf.ly','adobe.com','alegro.pl','alibaba.com','aliexpress.com','alipay.com',
'allrecipes.com','amazon.ca','amazon.cn','amazon.co.jp','amazon.co.uk','amazon.com','amazon.de','amazon.es','amazon.fr',
'amazon.in','amazon.it','amazonaws.com','ameblo.jp','americanexpress.com','answers.com','aol.com','apple.com','archive.org',
'ask.com','ask.fm','asos.com','att.com','avg.com','avito.ru','babytree.com','badoo.com','baidu.com','bankofamerica.com',
'battle.net','bbc.co.uk','bbc.com','bestbuy.com','bet365.com','bhaskar.com','bild.de','billibilli.com','bing.com','Bitauto.com',
'bleacherreport.com','blog.livedoor.com','blogger.com','blogspot.com','blogspot.in','blogtamsu.vn','bloomberg.com','bongacams.com',
'booking.com','businessinsider.com','buzzfeed.com','caijing.com.cn','capitalone.com','cbssports.com','chase.com','china.com',
'china.com.cn','chinadaily.com','chip.de','citi.com','clickadu.com','cnet.com','cnn.com','cnnic.cn','cntv.cn','cnzz.com',
'comcast.net','costco.com','craiglist.org','dailymail.co.uk','dailymotion.com','daum.net','dell.com','detik.com','devinatart.com',
'diply.com','directrev.com','dmm.co.jp','dmm.com','douban.com','dropbox.com','ebates.com','ebay.co.uk','ebay.com','ebay.com.au',
'ebay.de','ebay.in','ebay.it','Ebay-kleinanzeigen.de','eksisozluk.com','elmundo.es','elpais.com','engadget.com','espn.go.com',
'espncricinfo.com','etsy.com','evernote.com','exoclick.com','extratorrent.cc','facebook.com','fbcdn.net','fc2.com','fedex.com',
'feedly.com','files.wordpress.com','fiverr.com','flickr.com','flipkart.com','flirchi.com','foodnetwork.com','forbes.com',
'foxnews.com','free.fr','gamefaqs.com','gameforge.com','gamer.com.tw','gap.com','gearbest.com','gfycat.com','github.com',
'github.io','global.alipay.com','globo.com','gmw.cn','gmx.net','go.com','goal.com','godaddy.com','goo.ne.jp','goodreads.com',
'google.ae','google.at','google.az','google.be','google.ca','google.ch','google.cl','google.cn','google.co.id','google.co.il',
'google.co.in','google.co.jp','google.co.kr','google.co.th','google.co.uk','google.co.ve','google.co.za','Google.com','google.com.ar',
'google.com.au','google.com.br','google.com.co','google.com.hd','google.com.hk','google.com.mx','google.com.ng','google.com.pe',
'google.com.ph','google.com.pk','google.com.sa','google.com.sg','google.com.tr','google.com.tw','google.com.ua','google.cz',
'google.de','google.dz','google.es','google.fr','google.gr','google.hu','google.it','google.nl','google.no','google.pl','google.pt',
'google.ro','google.ru','google.se','groupon.com','gsmarena.com','hao123.com','haosou.com','hclips.com','hdfcbank.com','hm.com',
'homedepot.com','hootsuite.com','hp.com','huanqiu.com','huffingtonpost.com','hulu.com','hurriyet.com.tr','icicibank.com','icloud.com',
'ifeng.com','ign.com','ikea.com','imdb.com','imgur.com','in.ign.com','indeed.com','independent.co.uk','indiatimes.com',
'infusionsoft.com','instagram.com','irctc.co.in','jabong.com','jcpenney.com','jd.com','kakaku.com','kat.cr','kickstarter.com',
'kinogo.co','kinopoisk.ru','kohls.com','kompas.com','kouclo.com','leboncoin.fr','lemonde.fr','lenovo.com','libero.it',
'lifehacker.com','likes.com','linkedin.com','liputan6.com','livedoor.jp','liveinternet.ru','livejasmin.com','livejournal.com',
'lowes.com','macys.com','mail.ru','mailchimp.com','marca.com','mashable.com','media.tumblr.com','mediafire.com','medium.com',
'mega.nz','mercadolivre.com.br','microsoft.com','milliyet.com.tr','mozilla.org','msn.com','mystart.com','nametests.com',
'naukri.com','naver.com','nba.com','nbcnews.com','ndtv.com','netflix.com','newegg.com','nfl.com','nicovideo.jp','nih.com',
'nike.com','nordstrom.com','nytimes.com','office.com','ok.ru','onclickads.net','onedio.com','oneindia.com','onet.pl',
'onlinesbi.com','oracle.com','orange.fr','outbrain.com','overstock.com','pandora.com','paypal.com','paytm.com',
'photobucket.com','Pinimg.com','pinterest.com','pixiv.net','pixnet.net','plarium.com','playstation.com','popads.net',
'pornhub.com','ppomppu.co.kr','putlocker.is','qq.com','quikr.com','quora.com','rakuten.co.jp','rambler.ru','rbc.ru',
'reddit.com','rediff.com','redtube.com','reference.com','reimageplus.com','repubblica.it','reuters.com','rt.com',
'sahibinden.com','salesforce.com','samsung.com','savefrom.net','sberbank.ru','scribd.com','sears.com','seznam.cz',
'shop.nordstrom.com','shopclues.com','shopify.com','shutterstock.com','sina.com.cn','skype.com','slack.com','slickdeals.net',
'slideshare.net','smzdm.com','snapdeal.com','softonic.com','sogou.com','sohu.com','soso.com','soundcloud.com','souq.com',
'sourceforge.net','speedtest.net','spiegel.de','spotify.com','stackexchange.com','stackoverflow.com','steamcommunity.com',
'steampowered.com','stumbleupon.com','taboola.com','taobao.com','target.com','telegraph.co.uk','telegraphindia.com',
'thefreedictionary.com','theguardian.com','thehindu.com','theladbible.com','themeforest.net','thepiratebay.cr','thesaurus.com',
'tianya.cn','time.com','tmall.com','t-online.de','torrentz.eu','torrentz.in','toysrus.com','trello.com','tripadvisor.com',
'tube8.com','tudou.com','tumblr.com','twitch.tv','twitter.com','udn.com','uk.businessinsider.com','uol.com.br','uploaded.net',
'upornia.com','ups.com','uptodown.com','usatoday.com','usps.com','varzesh3.com','verizonwireless.com','vice.com','vid.me',
'vimeo.com','vk.com','w3schools.com','walmart.com','washingtonpost.com','weather.com','web.de','webmd.com','weebly.com',
'weibo.com','wellsfargo.com','whatsapp.com','wikia.com','wikihow.com','wikimedia.org','wikipedia.com','wix.com',
'wordpress.com','wordpress.org','wordreference.com','wp.pl','wsj.com','xfinity.com','xhamster.com','xinhuanet.com','xnxx.com',
'xuite.net','xvideos.com','yahoo.co.jp','yahoo.com','Yandex.ru','yandex.ua','yelp.com','youku.com','youm7.com','youporn.com',
'youth.cn','youtube.com','zendesk.com','zhihu.com','zippyshare.com','zol.com.cn','zollow.com','ssl.gstatic.com');
			foreach($urlsArrayy as $urlName){
				$otherLogin = true;
				$comDomain = explode('.',$urlName);
				$imageType = $comDomain[0];
				$strLo = strtolower($imageType);
				if( $image == $strLo ){
					$image = $basePath . "/images/montage_default_images/".$strLo.".png";
				}else if($image != $strLo){ 
					if(preg_match("/\.$strLo\.[A-Z]{2,4}/$image")) {  
						$image = $basePath . "/images/montage_default_images/".$strLo.".png";
					}
				}else{
					$image = $basePath . "/images/social_media/customization_no_img.jpg";
				}	
			}
		}
		$keywords = "";
		if( isset($answer["keywords"]) )
		{
			$keywords = $answer["keywords"];
		}
		$keywords = str_replace( PHP_EOL,"",$keywords );

		$author = "no Author";


		$linkValidityStatus = 0;
		
		// commented by siva reddy and bhargav if( ($title != "") || ($description != "no Description") || ($site_image != "no Image") )
		if( ($title != "") || ($description != "no Description") || ($site_image != "no Image") )
		{
			// echo "<pre>";print_r($_SESSION['urls']);exit;
			$linkValidityStatus = 1;
		}
		$currentUrlInfo = $urlId . "\t" . $request_url . "\t" . $title . "\t" . $image . "\t" . $description . "\t" . $author . "\t" . $keywords . "\t" . $linkValidityStatus . "\t" . $isVideo . "\t" . $iframeSrc . PHP_EOL;
		
		$userId = $_SESSION['usersinfo']->userId;
		$fileName = "";
		if( isset($_POST['categoryId']) )
		{
			$fileName = './public/dashboard/'.$userId.'-'.$_POST['categoryId'].'.txt';
		}
		else
		{
			$fileName = './public/databoxes/'.$userId.'.txt';
		}
		

		$fhandle = fopen( $fileName,"r" );
		if( $fhandle )
		{
			$urlFound = false;
			
			$allUrlsString = stream_get_contents( $fhandle );
			fclose($fhandle);
			$fileUrlsArray = explode( PHP_EOL,$allUrlsString );
			for( $urlNum = 0; $urlNum < count($fileUrlsArray); $urlNum++ )
			{
				if( $fileUrlsArray[$urlNum] != "" )
				{
					$fileCurrentUrlArray = explode( "\t",$fileUrlsArray[$urlNum] );
					if( $fileCurrentUrlArray[0] == $urlId )
					{
						$urlFound = true;
						break;
					}
				}
			}
			
			if( ! $urlFound )
			{
				$fp = fopen( $fileName, 'a' );
				fwrite( $fp,$currentUrlInfo );
				fclose( $fp );
			}
			else
			{
				$fileUrlsArray[$urlNum] = $urlId . "\t" . $request_url . "\t" . $title . "\t" . $image . "\t" . $description . "\t" . $author . "\t" . $keywords . "\t" . $linkValidityStatus . "\t" . $isVideo . "\t" . $iframeSrc;
				$newUrlsContent = implode( PHP_EOL,$fileUrlsArray );
				$fp = fopen( $fileName, 'w' );
				fwrite( $fp,$newUrlsContent );
				fclose( $fp );		
			}
		}
		else
		{
			fclose($fhandle);
			$fp = fopen( $fileName, 'a' );
			fwrite( $fp,$currentUrlInfo );
			fclose( $fp );		
		}

		
		// echo "<pre>";print_r($_SESSION['urls']);
		
		$result = new JsonModel(array(					
			'linkValidityStatus' 	=>  $linkValidityStatus
		));	
		return $result;
	}

    public function fetchUploadedUrlsAction()
	{
		// echo "fetchUploadedUrlsAction";exit;
		// echo "Upload: " . $_FILES['file']['tmp_name'];exit;
		// echo $_FILES['file']['name'];exit;
		
		$links=array();
		$links=$this->getUrlsArray();
		$htmlBook=$this->bookMarksPopUp($links);
		echo $htmlBook;exit;
		// echo '<pre>'; print_r($links);exit;
		//echo json_encode( $links );exit;
	}
	
    public function fetchCatImageAction()
	{
		// echo "fetchCatImageAction";exit;
		// echo "Upload: " . $_FILES['cifile']['tmp_name'];exit;
		// echo $_FILES['cifile']['name'];exit;
		
		if( move_uploaded_file( $_FILES['cifile']['tmp_name'],'./public/images/project/'.$_FILES['cifile']['name'] ) )
		{
			echo $_FILES['cifile']['name'];exit;
		}
		else
		{
			echo "Not Uploaded";exit;
		}
		
	}

    public function deleteRemovedUrlAction()
	{
		// echo "deleteRemovedUrlAction";exit;
		
		$indexToBeRemoved = -1;
		if( isset($_POST['urlId']) )
		{
			$urlId = $_POST['urlId'];

			$userId = $_SESSION['usersinfo']->userId;
			$fileName = "";
			if( isset($_POST['categoryId']) )
			{
				$fileName = './public/dashboard/'.$userId.'-'.$_POST['categoryId'].'.txt';
			}
			else
			{
				$fileName = './public/databoxes/'.$userId.'.txt';
			}
			
			$fhandle = fopen( $fileName,"r" );
			if( $fhandle )
			{
				$urlFound = false;
				
				$allUrlsString = stream_get_contents( $fhandle );
				fclose($fhandle);
				$fileUrlsArray = explode( PHP_EOL,$allUrlsString );
				for( $urlNum = 0; $urlNum < count($fileUrlsArray); $urlNum++ )
				{
					if( $fileUrlsArray[$urlNum] != "" )
					{
						$fileCurrentUrlArray = explode( "\t",$fileUrlsArray[$urlNum] );
						if( $fileCurrentUrlArray[0] == $urlId )
						{
							$urlFound = true;
							break;
						}
					}
				}
				
				if( $urlFound )
				{
					unset($fileUrlsArray[$urlNum]);
					$fileUrlsArray = array_values($fileUrlsArray);
					// echo "<pre>";print_r( $fileUrlsArray );exit;

					$newUrlsContent = implode( PHP_EOL,$fileUrlsArray );
					$fp = fopen( $fileName, 'w' );
					fwrite( $fp,$newUrlsContent );
					fclose( $fp );		

					$indexToBeRemoved = $urlNum;
				}
			}
			else
			{
				fclose($fhandle);
			}
		}

		echo $indexToBeRemoved;exit;
	}

    public function uniquePublicHashtagAction()
	{
		// echo "uniquePublicHastagAction";exit;
		if( isset($_POST['publicHtHolder']) && $_POST['publicHtHolder']!="" )
		{
			$hashtagCount = $this->getUserCategoriesTable()->checkPublicHashtag( $_POST['publicHtHolder'] );
			if( $hashtagCount > 0 ){
				$result = new JsonModel(array(					
					'output' 		=> 'success',
				));
			}else{
				$result = new JsonModel(array(					
					'output' 		=> 'notsuccess',
				));			
			}
		}
		else
		{
			$result = new JsonModel(array(					
				'output' 		=> 'success',
			));
		}
		return $result;
	}

    public function uniquePrivateCodeAction()
	{
		// echo "uniquePrivateCodeAction";exit;
		
		$ucExistsStatus = 0;
		if( isset($_POST['uniqueCode']) && $_POST['uniqueCode']!="" )
		{
			$uniqueCodeRs = $this->getUserCategoriesTable()->checkPrivateCode( $_POST['uniqueCode'] );
			if( $uniqueCodeRs->count() > 0 )
			{
				$ucExistsStatus = 1;
			}
		}

		$result = new JsonModel(array(
			'ucExistsStatus' 	=>  $ucExistsStatus
		));	

		return $result;
	}

	private function getUrlsForBak( $childrenArray )
	{
		foreach( $childrenArray as $key=>$urls )
		{
			if( isset($urls['url']) )
			{
				// $linksFunc[$urlNum]['title']=$urls['title'];
				$this->bakLinksArray[$this->bakLinkNum++] = $urls['url'];
			}
			else if( isset($urls['children']) )
			{
				$this->getUrlsForBak( $urls['children'] );
			}
		}
	}

	private function getUrlsForJson( $childrenArray )
	{
		foreach( $childrenArray as $key=>$urls )
		{
			if( isset($urls['uri']) )
			{
				// $linksFunc[$urlNum]['title']=$urls['title'];
				$this->jsonLinksArray[$this->jsonLinkNum++] = $urls['uri'];
			}
			else if( isset($urls['children']) )
			{
				$this->getUrlsForJson( $urls['children'] );
			}
		}
	}

	private function getUrlsArray()
	{
		$linksFunc=array();
		
		//$fileExtension = "";
		if( isset($_FILES['file']['name']) )
		{
			$feArray = explode( ".",$_FILES['file']['name'] );
			if( isset($feArray[1]) )
			{
				$this->fileExtension = $feArray[1];
			}
		}
		// echo $fileExtension;exit;
		
		$str = file_get_contents( $_FILES['file']['tmp_name'] );
		$json = json_decode($str, true);
		//echo '<pre>'; print_r($json);exit;
		$urlNum = 0;

		if( $this->fileExtension == "json" )
		{
			//echo '<pre>'; print_r($json);exit;
			$childrenArray=$json['children'][0];
			//$this->getUrlsForJson( $childrenArray['children'] );
			//$linksFunc = $this->jsonLinksArray;
			$linksFunc = $childrenArray;
		}
		else if( $this->fileExtension == "bak" )
		{
			 //echo '<pre>'; print_r($json);exit;
			// echo '<pre>'; print_r($childrenArray['children']);exit;
			$childrenArray=$json['roots']['bookmark_bar']['children'];
			// $this->getUrlsForBak( $childrenArray['children'] );
			$linksFunc = $childrenArray;
		}
		
		return $linksFunc;
	}

	private function myArraysCombine( $key,$val )
	{
		return array( $key=>$val );
	}	

	public function publicBoxesAjaxAction()
	{
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$publicBoxesfilterVal 	   = $_POST['publicBoxesfilterVal'];
		// echo $publicBoxesOffset;exit;
		if($publicBoxesfilterVal==0){
			$publicBoxesPerPage        = $_POST['publicBoxesPerPage'];
			$publicBoxesOffset         = $_POST['publicBoxesOffset'];
			$publicBoxesRs = $this->getUserCategoriesTable()->getHomePublicBoxes( $publicBoxesPerPage,$publicBoxesOffset,$publicBoxesfilterVal );
		}else if($publicBoxesfilterVal==2){
			$publicBoxesNewPerPage     = $_POST['publicBoxesNewPerPage'];
			$publicBoxesNewOffset      = $_POST['publicBoxesNewOffset'];
			$publicBoxesRs = $this->getUserCategoriesTable()->getHomePublicBoxesForFilters( $publicBoxesNewPerPage,$publicBoxesNewOffset,$publicBoxesfilterVal);
		}else if($publicBoxesfilterVal==3){
			$publicBoxesTrendingPerPage= $_POST['publicBoxesTrendingPerPage'];
			$publicBoxesTrendingOffset = $_POST['publicBoxesTrendingOffset'];
			$publicBoxesRs = $this->getUserCategoriesTable()->getHomePublicBoxesForFilters( $publicBoxesTrendingPerPage,$publicBoxesTrendingOffset,$publicBoxesfilterVal);
		}else if($publicBoxesfilterVal==4){
			$publicBoxesMyPostPerPage  = $_POST['publicBoxesMyPostPerPage'];
			$publicBoxesMyPostOffset   = $_POST['publicBoxesMyPostOffset'];
			$publicBoxesRs = $this->getUserCategoriesTable()->getHomePublicBoxesForFilters( $publicBoxesMyPostPerPage,$publicBoxesMyPostOffset,$publicBoxesfilterVal);
		}
		
		$publicBoxesHtml = "";
		$publicBoxesAllLoaded = 0;
		if( $publicBoxesRs->count() == 0 )
		{
			$publicBoxesAllLoaded = 1;
			$result = new JsonModel(array(
				'publicBoxesAllLoaded' 	=>  $publicBoxesAllLoaded,
				'publicBoxesHtml'		=>	$publicBoxesHtml
			));	
			return $result;
		}
		//$publicBoxesHtml.='<div id="divDataboxWrapper" class="divCardsWrapper" data-columns="4">';
		$divCards = array();
		foreach( $publicBoxesRs as $currentBoxRow )
		{
		//echo "<pre>";print_r($currentBoxRow);exit;
			$linksCount = 0;
			for( $currentCatId = 0; $currentCatId < count($_SESSION["catWiseLinksCount"]); $currentCatId++ )
			{
				if( $_SESSION['catWiseLinksCount'][$currentCatId]['categoryId'] == $currentBoxRow->category_id )
				{
					$linksCount = $_SESSION['catWiseLinksCount'][$currentCatId]['categoryLinksCount'];
					break;
				}
			}
			
			if( $linksCount == 0 )
			{
				$linksCount = $this->getCategoryLinksTable()->getCategoryLinks( $currentBoxRow->category_id )->count();
			}
			
			$dispHashName = "";
			if( strlen($currentBoxRow->user_hashname)>25 )
			{
				$dispHashName = substr($currentBoxRow->user_hashname,0,25) . '...';
			}
			else
			{
				$dispHashName = $currentBoxRow->user_hashname;
			}
			
			$showMcNsfwPopup = false;
			if( (! isset($_SESSION['usersinfo'])) && (($currentBoxRow->mature_content == 1) || ($currentBoxRow->not_safe_for_work == 1)) )
			{
				$showMcNsfwPopup = true;
			}
			
			$viewUrl = "";
			if( isset($_SESSION['usersinfo']) )
			{
				if($currentBoxRow->settingId==3){
					$viewUrl = "post-vertical";
				}else{
					$viewUrl = "post-horizontal";
				}
			}
			else
			{
				if($currentBoxRow->settingId==3){
					$viewUrl = "pre-vertical";
				}else{
					$viewUrl = "pre-horizontal";
				}
			}
			$viewsCount='';
			if( $currentBoxRow->views_count=="")
			{
				$viewsCount=0;
			}else{
				$viewsCount=$currentBoxRow->views_count;
			}
			$displayCustomizationUrl = $baseUrl;
			$catTitles = str_replace( "-","~",$currentBoxRow->category_title );
			$catTitle=str_replace(' ','-',$catTitles);
			$catHashName = substr( $currentBoxRow->user_hashname,1 );
			$catImageUrl=explode(".",$currentBoxRow->category_image);
			$catImageUrll=$catImageUrl[0];
			$roundLikes=round($currentBoxRow->likes);
			$displayCustomizationUrl .= "/databox/" . $viewUrl . "/" . $currentBoxRow->category_id . "+" . $catImageUrll . "+" . $catHashName . "+" . $catTitle;
			/* nch */
				 $publicBoxesHtml='<div class="publicsearchdatabox">';
				$publicBoxesHtml .= '<div id="divDatabox-1" class="divCard"><div id="divDatabox-1-imageWrapper" class="divCardImageWrapper img_center">';
				if( $showMcNsfwPopup ){
					$publicBoxesHtml .=	'<a href="javascript:void(0)" onClick="Javascript:displayNsfwMc(' .$currentBoxRow->category_id .','. $dispHashName .','. $currentBoxRow->category_title .','. $currentBoxRow->settingId .', '.$catImageUrll.'><img src="'.$basePath .'/images/social_media/mature_content.jpg" width="234" height="302" /></a>';
				} else { 
					$publicBoxesHtml .='<a href="'.$displayCustomizationUrl.'"><img id="Databox-1-img" class="" alt="'.$dispHashName.'" src="'.$basePath.'/images/project/categoryImages/'.$currentBoxRow->category_image .'"/></a>';
				}
				$publicBoxesHtml .='<div id="divDatabox-1-hashtag" class="divCardHashtag hash_tag_color"> <a href="'.$displayCustomizationUrl.'"> <h3 class="hashtagh2_size"><b>'.$dispHashName .'</b></h3></a></div>';
				$publicBoxesHtml .='<div id="divDatabox-1-sharedLinks" class="divCardSharedLinks">';
				$publicBoxesHtml .= '<span class="fatFont">';
				if( $currentBoxRow->user_classification!="" ){
					$publicBoxesHtml .= $linksCount.' HOT LINKS </span>'.$currentBoxRow->user_classification;
				}else{
					$publicBoxesHtml .= $linksCount.' HOT LINKS </span> collected and shared';
				}
				$publicBoxesHtml .= '</div>';
				$publicBoxesHtml .='<div class="divCardLoveTrash'.$currentBoxRow->category_id.'" id="divCardLoveTrash'.$currentBoxRow->category_id.'" >';
				if($currentBoxRow->userVoteUp=='1'){
					$imgUp='love_ok';
				}else{
					$imgUp='love';	
				} 
				
				$publicBoxesHtml .=	'<div class="divbrg_f" onClick="return likeDislikeCnt('.$currentBoxRow->category_id.',1,'.$currentBoxRow->rw_lh.')">'.$currentBoxRow->voteUp.' <a href="Javascript:void(0);" ><img src="'. $basePath .'/img/'.$imgUp.'.png" alt="" width="15px"  /></a></div>';
				$publicBoxesHtml .=	'<div class="divbrg_s" onClick="return likeDislikeCnt('.$currentBoxRow->category_id.',1,'.$currentBoxRow->rw_lh.')">'.$currentBoxRow->voteDown.'<img src="'.$basePath .'/img/trash.png" alt="" width="10px" /></div>';
				
				$userVoteUpTz = "3";
				if($currentBoxRow->userVoteUp!=""){
					$userVoteUpTz = $currentBoxRow->userVoteUp;
				}
				$userVoteDownTz = "3";
				if($currentBoxRow->uservoteDown!=""){
					$userVoteDownTz = $currentBoxRow->uservoteDown;
				}
				$publicBoxesHtml .='<input type="hidden" id="voting'.$currentBoxRow->category_id.'" name="voting'.$currentBoxRow->category_id.'" value="'.$userVoteUpTz.'">';
				$publicBoxesHtml .='<input type="hidden" id="votingDown'.$currentBoxRow->category_id.'" name="votingDown'.$currentBoxRow->category_id.'" value="'.$userVoteDownTz.'">';
				$publicBoxesHtml .='</div>';
				$publicBoxesHtml .='<div style="clear:both;"></div></div>';
				
				$publicBoxesHtml .='<div id="divDatabox-1-contentWrapper" class="divCardContentWrapper">';
				$publicBoxesHtml .='<div id="divDatabox-1-views" class="" align="center">';
				$publicBoxesHtml .='<img src="'. $basePath .'/img/views.png" alt="" />  '.$viewsCount.' views';
			    $publicBoxesHtml .='</div>';
			   
				if($currentBoxRow->category_title !=""){
					$publicBoxesHtml .='<div id="divDatabox-1-title" class="divCardTitle"><a href="'.$displayCustomizationUrl.'"> <h2 class="home_title_d"><b>'. $currentBoxRow->category_title .
					'</b></h2></a></div>';
				}
				if($currentBoxRow->meta_tags !=""){
					$publicBoxesHtml .='<div id="divCardKeywords" class="divCardKeywords">';
					$publicBoxesHtml .='<a href="'.$displayCustomizationUrl.'"><h3 class="home_keyword_h3"><b>Keywords: </b>'.$currentBoxRow->meta_tags.'</h3></a>';
					$publicBoxesHtml .='</div>';
				}
			    if($currentBoxRow->category_description !=""){
			   $publicBoxesHtml .=' <div id="divDatabox-1-description" class="divCardDescription home_de_brg_t home_title_des_s"><a href="'.$displayCustomizationUrl.'">';
			   if($currentBoxRow->category_description !=""){
					if(strlen($currentBoxRow->category_description)>160)
					{
						$publicBoxesHtml.= substr($currentBoxRow->category_description,0,160) . '...';
					}
					else
					{
						$publicBoxesHtml.= $currentBoxRow->category_description;
					}
				}
			   $publicBoxesHtml .='</a></div>';
			   }
			   $publicBoxesHtml .='</div>';
		       $publicBoxesHtml .='</div>';
			  // $publicBoxesHtml .='</div>';
			   array_push($divCards, $publicBoxesHtml);
		}
		if( $publicBoxesRs->count() < $publicBoxesPerPage )
		{
			$publicBoxesAllLoaded = 1;
		}
		$result = new JsonModel(array(					
			'publicBoxesAllLoaded' 	=>  $publicBoxesAllLoaded,
			'cards'					=>	json_encode($divCards)
		));	
		return $result;
	}
	
	public function highlightBoxesAjaxAction()
	{
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		
		$highlightBoxesPerPage = $_POST['highlightBoxesPerPage'];
		$highlightBoxesOffset = $_POST['highlightBoxesOffset'];
		// echo $highlightBoxesOffset;exit;

		$highlightBoxesRs = $this->getUserCategoriesTable()->getHomeHighlightBoxes( $highlightBoxesPerPage,$highlightBoxesOffset );
		
		$highlightBoxesHtml = "";
		$highlightBoxesAllLoaded = 0;
		if( $highlightBoxesRs->count() == 0 )
		{
			$highlightBoxesAllLoaded = 1;
			$result = new JsonModel(array(
				'highlightBoxesAllLoaded' 	=>  $highlightBoxesAllLoaded,
				'highlightBoxesHtml'		=>	$highlightBoxesHtml
			));	
			return $result;
		}
		foreach( $highlightBoxesRs as $currentBoxRow )
		{
			$linksCount = 0;
			for( $currentCatId = 0; $currentCatId < count($_SESSION["catWiseLinksCount"]); $currentCatId++ )
			{
				if( $_SESSION['catWiseLinksCount'][$currentCatId]['categoryId'] == $currentBoxRow->category_id )
				{
					$linksCount = $_SESSION['catWiseLinksCount'][$currentCatId]['categoryLinksCount'];
					break;
				}
			}
		
			if( $linksCount == 0 )
			{
				$linksCount = $this->getCategoryLinksTable()->getCategoryLinks( $currentBoxRow->category_id )->count();
			}

			$viewUrl = "";
			if( isset($_SESSION['usersinfo']) )
			{
				if($currentBoxRow->settingId==3){
					$viewUrl = "post-vertical";
				}else{
					$viewUrl = "post-horizontal";
				}
			}
			else
			{
				if($currentBoxRow->settingId==3){
					$viewUrl = "pre-vertical";
				}else{
					$viewUrl = "pre-horizontal";
				}
			}
			$displayCustomizationUrl = $baseUrl;
			$catTitles = str_replace( "-","~",$currentBoxRow->category_title );
			$catTitle=str_replace(' ','-',$catTitles);
			$catHashName = substr( $currentBoxRow->user_hashname,1 );
			$catImageUrl=explode(".",$currentBoxRow->category_image);
			$catImageUrll=$catImageUrl[0];
			$displayCustomizationUrl .= "/databox/" . $viewUrl . "/" . $currentBoxRow->category_id . "+" . $catImageUrll . "+" . $catHashName . "+" . $catTitle;
			$dispHighCatTitle = "";
			if( strlen($currentBoxRow->category_title) > 57 )
			{
				$dispHighCatTitle = substr($currentBoxRow->category_title,0,57) . '...';
			}
			else
			{
				$dispHighCatTitle = $currentBoxRow->category_title;
			}
			$highlightBoxesHtml .= '<li><div class="highlight_block home_pos_r">';
			$highlightBoxesHtml .= '<div class="image_highlight"><a href="' . $displayCustomizationUrl . '"><img src= "'.$basePath.'/images/project/categoryImages/' . $currentBoxRow->category_image . '" width="263" height="234" /></a></div>';
			$highlightBoxesHtml .= '<div class="hashtag_highlights"><a class="home_anchor_black" href="' . $displayCustomizationUrl . '">' . $currentBoxRow->user_hashname . '</a></div>';
			$highlightBoxesHtml .= '<div class="heighlights_pos_abso_left_title"><p><a class="home_anchor_black" href="' . $displayCustomizationUrl . '">' . $dispHighCatTitle . '</a></p></div>';
			$highlightBoxesHtml .= '<div class="heighlights_pos_abso_left"><p>>> Listed Sources >> </p></div>';
			$highlightBoxesHtml .= '<div class="heighlights_pos_abso_right"><p>' . $linksCount . '</p></div>';
			$highlightBoxesHtml .= '</div></li>';
		}
		
		// echo "<pre>";print_r($highlightBoxesHtml);exit;
		if( $highlightBoxesRs->count() < $highlightBoxesPerPage )
		{
			$highlightBoxesAllLoaded = 1;
		}
		$result = new JsonModel(array(					
			'highlightBoxesAllLoaded' 	=>  $highlightBoxesAllLoaded,
			'highlightBoxesHtml'		=>	$highlightBoxesHtml
		));	
		return $result;
	}

	public function publicSearchAjaxAction()
	{
		$baseUrls   = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl    = $baseUrlArr['baseUrl'];
		$basePath   = $baseUrlArr['basePath'];
		
		$searchTermHolder = $_POST['searchTermHolder'];
		// echo $searchTermHolder;exit;

		$searchBoxesRs = $this->getUserCategoriesTable()->getSearchPublicBoxes( $searchTermHolder );
		$publicBoxesHtml = "";
		$zeroPublicBoxesFound = 0;
		$boxesCount=0;
		if( $searchBoxesRs->count() == 0 )
		{
			$zeroPublicBoxesFound = 1;
			$result = new JsonModel(array(
				'zeroPublicBoxesFound' 	=>  $zeroPublicBoxesFound,
				'publicBoxesHtml'		=>	$publicBoxesHtml
			));	
			return $result;
		}
		if( $searchBoxesRs->count() == 1 )
		{
			$boxesCount=$searchBoxesRs->count();
		}
		$publicBoxesHtml1='<div id="divDataboxWrapper" class="divCardsWrapper" data-columns>';
		foreach( $searchBoxesRs as $currentBoxRow )
		{
			$linksCount = 0;
			for( $currentCatId = 0; $currentCatId < count($_SESSION["catWiseLinksCount"]); $currentCatId++ )
			{
				if( $_SESSION['catWiseLinksCount'][$currentCatId]['categoryId'] == $currentBoxRow->category_id )
				{
					$linksCount = $_SESSION['catWiseLinksCount'][$currentCatId]['categoryLinksCount'];
					break;
				}
			}
			if( $linksCount == 0 )
			{
				$linksCount = $this->getCategoryLinksTable()->getCategoryLinks( $currentBoxRow->category_id )->count();
			}

			$dispHashName = "";
			if( strlen($currentBoxRow->user_hashname)>25 )
			{
				$dispHashName = substr($currentBoxRow->user_hashname,0,25) . '...';
			}
			else
			{
				$dispHashName = $currentBoxRow->user_hashname;
			}

			$showMcNsfwPopup = false;
			if( (! isset($_SESSION['usersinfo'])) && (($currentBoxRow->mature_content == 1) || ($currentBoxRow->not_safe_for_work == 1)) )
			{
				$showMcNsfwPopup = true;
			}

			$viewUrl = "";
			if( isset($_SESSION['usersinfo']) )
			{
				if($currentBoxRow->settingId==3){
					$viewUrl = "post-vertical";
				}else{
					$viewUrl = "post-horizontal";
				}
			}
			else
			{
				if($currentBoxRow->settingId==3){
					$viewUrl = "pre-vertical";
				}else{
					$viewUrl = "pre-horizontal";
				}
			}
			$viewsCount='';
			if( $currentBoxRow->views_count=="")
			{
				$viewsCount=0;
			}else{
				$viewsCount=$currentBoxRow->views_count;
			}
			$displayCustomizationUrl = $baseUrl;
			$catTitles = str_replace( "-","~",$currentBoxRow->category_title );
			$catTitle = str_replace( " ","-",$catTitles );
			$catHashName = substr( $currentBoxRow->user_hashname,1 );
			$catImageUrl=explode(".",$currentBoxRow->category_image);
			$catImageUrll=$catImageUrl[0];
			$roundLikes=round($currentBoxRow->likes);
			$displayCustomizationUrl .= "/databox/" . $viewUrl . "/" . $currentBoxRow->category_id . "+" . $catImageUrll . "+" . $catHashName . "+" . $catTitle;
			
			//NEW Code
		    $publicBoxesHtml1 .='<div class="left width20 remove_mobile_c" id="remove_mobile_c">';
			$publicBoxesHtml1 .= '<div id="divDatabox-1" class="divCard" draggable="true"><div id="divDatabox-1-imageWrapper" class="divCardImageWrapper img_center">';
				if( $showMcNsfwPopup ){
					$publicBoxesHtml1 .=	'<a href="javascript:void(0)" onClick="Javascript:displayNsfwMc(' .$currentBoxRow->category_id .','. $dispHashName .','. $currentBoxRow->category_title .','. $currentBoxRow->settingId .', '.$catImageUrll.'><img src="'.$basePath .'/images/social_media/mature_content.jpg" width="234" height="302" /></a>';
				} else { 
					$publicBoxesHtml1 .='<a href="'.$displayCustomizationUrl.'"><img id="Databox-1-img" class="" alt="'.$dispHashName.'" src="'.$basePath.'/images/project/categoryImages/'.$currentBoxRow->category_image .'"/></a>';
				}
				$publicBoxesHtml1 .='<div id="divDatabox-1-hashtag" class="divCardHashtag hash_tag_color"> <a href="'.$displayCustomizationUrl.'"><h3 class="hashtagh2_size"><b>'.$dispHashName .'</b></h3></a></div>';
				$publicBoxesHtml1 .='<div id="divDatabox-1-sharedLinks" class="divCardSharedLinks">';
				$publicBoxesHtml1 .= '<span class="fatFont">';
				if( $currentBoxRow->user_classification!="" ){
					$publicBoxesHtml1 .= $linksCount.' HOT LINKS </span>'.$currentBoxRow->user_classification;
				}else{
					$publicBoxesHtml1 .= $linksCount.' HOT LINKS </span> collected and shared';
				}
				$publicBoxesHtml1 .= '</div>';
			
				$publicBoxesHtml1 .='<div class="divCardLoveTrash'.$currentBoxRow->category_id.'" id="divCardLoveTrash'.$currentBoxRow->category_id.'" >';
					if($currentBoxRow->userVoteUp=='1'){
						$imgUp='love_ok';
					}else{
						$imgUp='love';	
					} 
				$publicBoxesHtml1 .=	'<div class="divbrg_f" onClick="return likeDislikeCnt('.$currentBoxRow->category_id.',1,'.$currentBoxRow->rw_lh.')">'.$currentBoxRow->voteUp.' <a href="Javascript:void(0);" ><img src="'. $basePath .'/img/'.$imgUp.'.png" alt="" width="15px"  /></a></div>';
				$publicBoxesHtml1 .=	'<div class="divbrg_s" onClick="return likeDislikeCnt('.$currentBoxRow->category_id.',1,'.$currentBoxRow->rw_lh.')">'.$currentBoxRow->voteDown.' <a href="Javascript:void(0);" ><img src="'.$basePath .'/img/trash.png" alt="" width="10px" /></a></div>';
				
				$userVoteUpTz = "3";
				if($currentBoxRow->userVoteUp!=""){
					$userVoteUpTz = $currentBoxRow->userVoteUp;
				}
				$userVoteDownTz = "3";
				if($currentBoxRow->uservoteDown!=""){
					$userVoteDownTz = $currentBoxRow->uservoteDown;
				}
				$publicBoxesHtml1 .='<input type="hidden" id="voting'.$currentBoxRow->category_id.'" name="voting'.$currentBoxRow->category_id.'" value="'.$userVoteUpTz.'">';
				$publicBoxesHtml1 .='<input type="hidden" id="votingDown'.$currentBoxRow->category_id.'" name="votingDown'.$currentBoxRow->category_id.'" value="'.$userVoteDownTz.'">';
				$publicBoxesHtml1 .='</div>';
				$publicBoxesHtml1 .='<div style="clear:both;"></div></div>';
				
			    $publicBoxesHtml1 .='<div id="divDatabox-1-contentWrapper" class="divCardContentWrapper"><div>';
				$publicBoxesHtml1 .='<div id="divDatabox-1-views" class="" align="center">';
				$publicBoxesHtml1 .='<img src="'. $basePath .'/img/views.png" alt="" /> '.$viewsCount.' views';
				$publicBoxesHtml1 .='</div>';
			  
				$publicBoxesHtml1 .='</div></div>';

				if($currentBoxRow->category_title !=""){
				$publicBoxesHtml1 .='<div id="divDatabox-1-title" class="divCardTitle"><a href="'.$displayCustomizationUrl.'"><h2 class="home_title_d"> <b>'. $currentBoxRow->category_title .
				'</b></h2></a></div>';
				}
				if($currentBoxRow->meta_tags !=""){
				$publicBoxesHtml1 .='<div id="divCardKeywords" class="divCardKeywords"><a href="'.$displayCustomizationUrl.'"><h3 class="home_keyword_h3"><b>Keywords: </b>';
				$publicBoxesHtml1 .=$currentBoxRow->meta_tags;
				$publicBoxesHtml1 .='</h3></a></div>';
				}
				if($currentBoxRow->category_description !=""){
			   $publicBoxesHtml1 .=' <div id="divDatabox-1-description" class="divCardDescription home_de_brg_t home_title_des_s"><a href="'.$displayCustomizationUrl.'">';
			   if($currentBoxRow->category_description !=""){
					if(strlen($currentBoxRow->category_description)>160)
					{
						$publicBoxesHtml1.= substr($currentBoxRow->category_description,0,160) . '...';
					}
					else
					{
						$publicBoxesHtml1.= $currentBoxRow->category_description;
					}
				}
			   $publicBoxesHtml1 .='</a></div>';
			   }
			$publicBoxesHtml1 .='</div>';
		    $publicBoxesHtml1 .='</div>';
			
			//$publicBoxesHtml1 .='</div>';
		  
			//END
		}
	 $publicBoxesHtml1 .='</div>';
	   
		$result = new JsonModel(array(					
			'zeroPublicBoxesFound' 	=>  $zeroPublicBoxesFound,
			'boxesCount' 	=>  $boxesCount,
			'url' 	=>  $displayCustomizationUrl,
			'publicBoxesHtml'		=>	$publicBoxesHtml1
		));	
		return $result;
	}

	public function privateSearchAjaxAction()
	{
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		
		$searchTermHolder = $_POST['searchTermHolder'];
		$pvtUniqueCodeHolder = $_POST['pvtUniqueCodeHolder'];
		// echo $searchTermHolder;exit;

		$privateBoxRs = $this->getUserCategoriesTable()->getSearchPrivateBox( $searchTermHolder,$pvtUniqueCodeHolder );
		
		$privateBoxHtml = "";
		$zeroPrivateBoxFound = 0;
		if( $privateBoxRs->count() == 0 )
		{
			$zeroPrivateBoxFound = 1;
			$result = new JsonModel(array(
				'zeroPrivateBoxFound' 	=>  $zeroPrivateBoxFound,
				'privateBoxHtml'		=>	$privateBoxHtml
			));	
			return $result;
		}
		if( $privateBoxRs->count() == 1 )
		{
			$boxesCount=$privateBoxRs->count();
		}
		$privateBoxHtml.='<div id="divDataboxWrapper" class="divCardsWrapper" data-columns>';
		foreach( $privateBoxRs as $currentBoxRow )
		{
			$linksCount = 0;
			for( $currentCatId = 0; $currentCatId < count($_SESSION["catWiseLinksCount"]); $currentCatId++ )
			{
				if( $_SESSION['catWiseLinksCount'][$currentCatId]['categoryId'] == $currentBoxRow->category_id )
				{
					$linksCount = $_SESSION['catWiseLinksCount'][$currentCatId]['categoryLinksCount'];
					break;
				}
			}
		
			$dispHashName = "";
			if( strlen($currentBoxRow->user_hashname)>25 )
			{
				$dispHashName = substr($currentBoxRow->user_hashname,0,25) . '...';
			}
			else
			{
				$dispHashName = $currentBoxRow->user_hashname;
			}

			$viewUrl = "";
			if( isset($_SESSION['usersinfo']) )
			{
				if($currentBoxRow->settingId==3){
					$viewUrl = "post-vertical";
				}else{
					$viewUrl = "post-horizontal";
				}
			}
			else
			{
				if($currentBoxRow->settingId==3){
					$viewUrl = "pre-vertical";
				}else{
					$viewUrl = "pre-horizontal";
				}
			}
			$viewsCount='';
			if( $currentBoxRow->views_count=="")
			{
				$viewsCount=0;
			}else{
				$viewsCount=$currentBoxRow->views_count;
			}
			$showMcNsfwPopup = false;
			if( (! isset($_SESSION['usersinfo'])) && (($currentBoxRow->mature_content == 1) || ($currentBoxRow->not_safe_for_work == 1)) )
			{
				$showMcNsfwPopup = true;
			}
			$displayCustomizationUrl = $baseUrl;
			$catTitles = str_replace( "-","~",$currentBoxRow->category_title );
			$catTitle = str_replace( " ","-",$catTitles );
			$catHashName = substr( $currentBoxRow->user_hashname,1 );
			$catImageUrl=explode(".",$currentBoxRow->category_image);
			$catImageUrll=$catImageUrl[0];
			$displayCustomizationUrl .= "/databox/" . $viewUrl . "/" . $currentBoxRow->category_id . "+" . $catImageUrll . "+" . $catHashName . "+" . $catTitle;
			$roundLikes=round($currentBoxRow->likes);
			//new code 
			$privateBoxHtml.='<div class="left width20 remove_mobile_c" id="remove_mobile_c">';
			$privateBoxHtml .= '<div id="divDatabox-1" class="divCard" draggable="true"><div id="divDatabox-1-imageWrapper" class="divCardImageWrapper img_center">';
				if( $showMcNsfwPopup ){
					$privateBoxHtml .=	'<a href="javascript:void(0)" onClick="Javascript:displayNsfwMc(' .$currentBoxRow->category_id .','. $dispHashName .','. $currentBoxRow->category_title .','. $currentBoxRow->settingId .', '.$catImageUrll.'><img src="'.$basePath .'/images/social_media/mature_content.jpg" width="234" height="302" /></a>';
				} else { 
					$privateBoxHtml .='<a href="'.$displayCustomizationUrl.'"><img id="Databox-1-img" class="" alt="'.$dispHashName.'" src="'.$basePath.'/images/project/categoryImages/'.$currentBoxRow->category_image .'"/></a>';
				}
				$privateBoxHtml .='<div id="divDatabox-1-hashtag" class="divCardHashtag hash_tag_color"> <a href="'.$displayCustomizationUrl.'"><h3 class="hashtagh2_size"><b>'.$dispHashName .'</b></h3> </a></div>';
				$privateBoxHtml .='<div id="divDatabox-1-sharedLinks" class="divCardSharedLinks">';
				$privateBoxHtml .= '<span class="fatFont">';
				if( $currentBoxRow->user_classification!="" ){
					$privateBoxHtml .= $linksCount.' HOT LINKS </span>'.$currentBoxRow->user_classification;
				}else{
					$privateBoxHtml .= $linksCount.' HOT LINKS </span> collected and shared';
				}
				$privateBoxHtml .= '</div>';
				
				$privateBoxHtml .='<div class="divCardLoveTrash'.$currentBoxRow->category_id.'" id="divCardLoveTrash'.$currentBoxRow->category_id.'" >';
				if($currentBoxRow->userVoteUp=='1'){
					$imgUp='love_ok';
				}else{
					$imgUp='love';	
				} 	
				$privateBoxHtml .=	'<div class="divbrg_f" onClick="return likeDislikeCnt('.$currentBoxRow->category_id.',1,'.$currentBoxRow->rw_lh.')">'.$currentBoxRow->voteUp.' <a href="Javascript:void(0);" ><img src="'. $basePath .'/img/'.$imgUp.'.png" alt="" width="15px"  /></a></div>';
				$privateBoxHtml .=	'<div class="divbrg_s" onClick="return likeDislikeCnt('.$currentBoxRow->category_id.',1,'.$currentBoxRow->rw_lh.')">'.$currentBoxRow->voteDown.' <a href="Javascript:void(0);" ><img src="'.$basePath .'/img/trash.png" alt="" width="10px" /></a></div>';
				
				$userVoteUpTz = "3";
				if($currentBoxRow->userVoteUp!=""){
					$userVoteUpTz = $currentBoxRow->userVoteUp;
				}
				$userVoteDownTz = "3";
				if($currentBoxRow->uservoteDown!=""){
					$userVoteDownTz = $currentBoxRow->uservoteDown;
				}
				$privateBoxHtml .='<input type="hidden" id="voting'.$currentBoxRow->category_id.'" name="voting'.$currentBoxRow->category_id.'" value="'.$userVoteUpTz.'">';
				$privateBoxHtml .='<input type="hidden" id="votingDown'.$currentBoxRow->category_id.'" name="votingDown'.$currentBoxRow->category_id.'" value="'.$userVoteUpTz.'">';
				$privateBoxHtml .='</div>';
				$privateBoxHtml .='<div style="clear:both;"></div></div>';

			$privateBoxHtml .='<div id="divDatabox-1-contentWrapper" class="divCardContentWrapper">';
				$privateBoxHtml .='<div id="divDatabox-1-views" class="" align="center">';
					$privateBoxHtml .='<img src="'. $basePath .'/img/views.png" alt="" /> '.$viewsCount.' views';
			   $privateBoxHtml .='</div>';
				
			$privateBoxHtml .='</div></div>';

				if($currentBoxRow->category_title !=""){
					$privateBoxHtml .='<div id="divDatabox-1-title" class="divCardTitle"><a href="'.$displayCustomizationUrl.'"><h2 class="home_title_d"><b>'. $currentBoxRow->category_title .
					'</b></h2></a></div>';
				}	
				if($currentBoxRow->meta_tags !=""){
				
				$privateBoxHtml .='<div id="divCardKeywords" class="divCardKeywords"><a href="'.$displayCustomizationUrl.'"><h3 class="home_keyword_h3"><b>Keywords: </b>'; 
				$privateBoxHtml .=$currentBoxRow->meta_tags;
				$privateBoxHtml.='</h3></a></div>';
				}
				if($currentBoxRow->category_description !=""){
			   $privateBoxHtml .=' <div id="divDatabox-1-description" class="divCardDescription home_de_brg_t home_title_des_s"><a href="'.$displayCustomizationUrl.'">';
			   if($currentBoxRow->category_description !=""){
					if(strlen($currentBoxRow->category_description)>160)
					{
						$privateBoxHtml.= substr($currentBoxRow->category_description,0,160) . '...';
					}
					else
					{
						$privateBoxHtml.= $currentBoxRow->category_description;
					}
				}
			   $privateBoxHtml .='</a></div>';
			   }
			$privateBoxHtml .='</div>';
		    $privateBoxHtml .='</div>';
			 //$privateBoxHtml .='</div>';
		  
			//END
		}
				$privateBoxHtml .='</div>';
		$result = new JsonModel(array(					
			'zeroPrivateBoxFound' 	=>  $zeroPrivateBoxFound,
			'boxesCount' 	=>  $boxesCount,
			'url' 	=>  $displayCustomizationUrl,
			'privateBoxHtml'		=>	$privateBoxHtml
		));	
		return $result;
	}
	
	public function updateCatDescAction()
	{
		$categoryId = 0;
		$catNewDesc = "";
		if( isset($_POST["categoryId"]) && isset($_POST["catNewDesc"]) )
		{
			$categoryId = $_POST["categoryId"];
			$catNewDesc = $_POST["catNewDesc"];
			// echo "<pre>";print_r($categoryId);print_r($catNewDesc);exit;
			
			$updateCatDescStatus = $this->getUserCategoriesTable()->updateCatDescription( $categoryId,$catNewDesc );
			echo "1";exit;
		}
		else
		{
			echo "0";exit;
		}
	}
	
	public function getCategoryTable()
    {
        if (!$this->categoryTable) {				
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('Databox\Model\CategoryFactory');			
        }
        return $this->categoryTable;
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

	public function getLinkDetailsTable()
    {
        if (!$this->linkDetailsTable) {				
            $sm = $this->getServiceLocator();
            $this->linkDetailsTable = $sm->get('Databox\Model\LinkDetailsFactory');			
        }
        return $this->linkDetailsTable;
    }

	public function getUserTable()
    {
        if (!$this->userTable) {				
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Databoxuser\Model\UserFactory');			
        }
        return $this->userTable;
    }

	public function getLinkSettingTable()
    {
        if (!$this->linkSettingTable) {				
            $sm = $this->getServiceLocator();
            $this->linkSettingTable = $sm->get('Databox\Model\LinkSettingFactory');			
        }
        return $this->linkSettingTable;
    }

	public function getSettingFlexibleTypeTable()
    {
        if (!$this->settingFlexibleTypeTable) {				
            $sm = $this->getServiceLocator();
            $this->settingFlexibleTypeTable = $sm->get('Databox\Model\SettingFlexibleTypeFactory');			
        }
        return $this->settingFlexibleTypeTable;
    }

	public function getUserHighlightsTable()
    {
        if (!$this->userHighlightsTable) {				
            $sm = $this->getServiceLocator();
            $this->userHighlightsTable = $sm->get('Databox\Model\UserHighlightsFactory');			
        }
        return $this->userHighlightsTable;
    }
	public function getCatImageTable()
    {
        if (!$this->catImageTable) {				
            $sm = $this->getServiceLocator();
            $this->catImageTable = $sm->get('Databox\Model\CatImageFactory');			
        }
        return $this->catImageTable;
    }
	public function getJsPlumbGridTable()
    {
        if (!$this->jsPlumbGridTable) {				
            $sm = $this->getServiceLocator();
            $this->jsPlumbGridTable = $sm->get('Databox\Model\JsPlumbGridFactory');			
        }
        return $this->jsPlumbGridTable;
    }
	public function allindustriesAjaxAction(){
	    $baseUrls = $this->getServiceLocator()->get('config');
        $baseUrlArr = $baseUrls['urls'];
        $baseUrl = $baseUrlArr['baseUrl'];
        $basepath = $baseUrlArr['basePath'];
	    $getallindustries=$this->getIndustryTable()->getallindustries();
		$i=0;$ind_stry=array();$cat_name='';$cat_total='';
		if(isset($getallindustries) && $getallindustries->count()!=0){
				foreach($getallindustries as $industries){
					if(isset($industries->category_id) && $industries->category_id!=""){
					 $array=explode(",",$industries->category_id);
					  foreach($array as $categoryId){
						 $getallcategories = $this->getCategoriesTable()->getallCategory($categoryId);
						 $cat_name=$getallcategories->category_name;
						 $cat_total=$cat_total.$cat_name.",";
						 $cat_name="";
					  }
					  $categoryname=trim($cat_total,',');
					}
					$id=$industries->industry_id;
					$data[$i]['sno']=$i+1;
					$data[$i]['category_name']=$categoryname;
					$data[$i]['industryname']= $industries->industryname;
					$data[$i]['action'] ='<a style="color: #000000;font-size: 15px;" href="'.$baseUrl.'/products/industry?industry_id='.$id.'"><img src="'.$basepath.'/images/edit.png" title="Edit"></a>&nbsp;/&nbsp;<a style="color: #000000;font-size: 15px;" href="javascript:void(0);" onClick="deleteIndustry('.$id.')"><img src="'.$basepath.'/images/delete.png" title="Delete"></a>';
					$i++;
					$cat_total='';
				}	
			$data['aaData'] = $data;
			print_r(json_encode($data['aaData']));exit;
		}else{
			echo '1';exit;
		}
	}
	public function viewVerticalAction(){
		if(isset($_POST) && isset($_POST['catId'])){
			$addRow = $this->getJsPlumbGridTable()->addRow( $_POST );
			return new JsonModel(array(				
				'output' 		=> 	1
			));
		}else if(isset($_POST) && isset($_POST['ld_id'])){
			$para = $this->getLinkDetailsTable()->getParagraph( $_POST['ld_id'] );
			return new JsonModel(array(				
				'para' 		=> 	$para->description,
				'title' 	=> 	$para->title,
				'image' 	=> 	$para->image,
				'isVideo' 	=> 	$para->is_video,
				'iframeSrc' => 	$para->iframe_src
			));
		}else{
			$baseUrls = $this->getServiceLocator()->get('config');
			$baseUrlArr = $baseUrls['urls'];
			$baseUrl = $baseUrlArr['baseUrl'];
			$basePath = $baseUrlArr['basePath'];
			$databoxLinksGrid=array();
			$categoryId = 0;
			$hashName="";
			$categoryTitle="";
			$categoryImage="";
			$categoryDescripton = "";
			$isPrivateDatabox = 1;
			$boxKeywords="";
			$catDescGot = 0;
			$category_type = "";
			$category_highlight = "";
			if($this->params()->fromRoute('id', 0)!="")
			{
				$params=$this->params()->fromRoute('id', 0);
				$paramss=explode("+",$params);
				$categoryId=$paramss[0];
				if( isset($paramss[1]) )
				{
					$hashName='#'.$paramss[2];
					$categoryTitles=str_replace('~','-',$paramss[3]);
					$categoryTitle=str_replace('-',' ',$categoryTitles);
					$categoryImage=$paramss[1];
				}
				else
				{
					$catRow = $this->getUserCategoriesTable()->getEditHighlight( $categoryId );
					$hashName="#".substr($catRow->user_hashname,1);
					$categoryTitle=str_replace('-',' ',$catRow->category_title);
					$categoryImage1=explode(".",$catRow->category_image);
					$categoryImage=$categoryImage1[0];
					$main_cat_name = $catRow->main_cat_tittle;
					$sub_cat_name = $catRow->sub_cat_tittle;
					$cat_name = $catRow->category_name;
					$categoryDescripton = $catRow->category_description;
					if( ($catRow->category_type == 0) && ( ! is_null($catRow->secret_code) && (trim($catRow->secret_code) != "" ) ) )
					{
						$isPrivateDatabox = 0;
					}
					if( ! is_null($catRow->meta_tags) && (trim($catRow->meta_tags) != "") )
					{
						$boxKeywords = $catRow->meta_tags;
					}
					$catDescGot = 1;
				}
			}

			$displayedCategoryUserId = 0;

			$databoxLinksGridd = $this->getJsPlumbGridTable()->getRow( $categoryId );
			$data=1;
			if(!$databoxLinksGridd->count()){
				$databoxLinksGrids = $this->getLinkDetailsTable()->getDataboxGrid( $categoryId );

				$databoxLinksGrid = $databoxLinksGrids->buffer();
				$main_cat_name = $databoxLinksGrids->buffer()->current()->main_cat_tittle;
				$sub_cat_name = $databoxLinksGrids->buffer()->current()->sub_cat_tittle;
				$cat_name = $databoxLinksGrids->buffer()->current()->category_name;
				$displayedCategoryUserId = $databoxLinksGrids->buffer()->current()->user_id;
				$categoryDescripton = $databoxLinksGrids->buffer()->current()->category_description;
				$category_type = $databoxLinksGrids->buffer()->current()->category_type;
				$category_highlight = $databoxLinksGrids->buffer()->current()->category_highlight;
				
				if( ($databoxLinksGrids->buffer()->current()->boxPrivacy == 0) && ( ! is_null($databoxLinksGrids->buffer()->current()->secret_code) && (trim($databoxLinksGrids->buffer()->current()->secret_code) != "" ) ) )
				{
					$isPrivateDatabox = 0;
				}
				if( ! is_null($databoxLinksGrids->buffer()->current()->meta_tags) && (trim($databoxLinksGrids->buffer()->current()->meta_tags) != "") )
				{
					$boxKeywords = $databoxLinksGrids->buffer()->current()->meta_tags;
				}
				$catDescGot = 1;
				$data=0;
			}else{
				$databoxLinksGrid=$databoxLinksGridd;
				$displayedCategoryUserId = $databoxLinksGridd->buffer()->current()->user_id;
				$main_cat_name = "";
				$sub_cat_name = "";
				$cat_name = "";
				$category_type = "";
				$category_highlight = "";
				
			}
			// echo '<pre>'; print_r($databoxLinksGrid);exit;
			
			$displayedCatUName = "User name";
			$displayedUMontImage = "";
			$userRow = $this->getUserTable()->getUserRow( $displayedCategoryUserId );
			if( isset($userRow->display_name) && trim($userRow->display_name != "" ) )
			{
				$displayedCatUName = $userRow->display_name;
			}
			if( isset($userRow->montage_image) && trim($userRow->montage_image != "" ) )
			{
				$displayedUMontImage = $userRow->montage_image;
			}
			
			if( $catDescGot == 0 )
			{
				$ucRow = $this->getUserCategoriesTable()->getEditHighlight( $categoryId );

				$categoryDescripton = $ucRow->category_description;
				$main_cat_name = $ucRow->main_cat_tittle;
				$sub_cat_name = $ucRow->sub_cat_tittle;
				$cat_name = $ucRow->category_name;
				if( ($ucRow->category_type == 0) && ( ! is_null($ucRow->secret_code) && (trim($ucRow->secret_code) != "" ) ) )
				{
					$isPrivateDatabox = 0;
				}
				if( ! is_null($ucRow->meta_tags) && (trim($ucRow->meta_tags) != "") )
				{
					$boxKeywords = $ucRow->meta_tags;
				}
			}
			
			// echo "#" . $main_cat_name . "#";exit;

			//Get Relevance Worth
			if(isset($_SESSION['usersinfo']->userId)){
				$user_id=$_SESSION['usersinfo']->userId;
			}else{
				$user_id=$_SERVER['REMOTE_ADDR'];
			}
			$relevanceWorthVoteTable=$this->getServiceLocator()->get('Databox\Model\RelevanceWorthVoteFactory');
			$getRelevanceWorth = $relevanceWorthVoteTable->getVoteUpDown( $categoryId,$user_id );
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
			//End
			$getRelevanceCount1 = $relevanceWorthVoteTable->getRelevanceCount( $categoryId,1 );
			$getRelevanceCount0 = $relevanceWorthVoteTable->getRelevanceCount( $categoryId,0 );
			$getWorthCount1 = $relevanceWorthVoteTable->getWorthCount( $categoryId,1 );
			$getWorthCount0 = $relevanceWorthVoteTable->getWorthCount( $categoryId,0 );
			//Total Count Relevance And Worth
			if($getRelevanceCount1->countRelevance1==""){
				$countRelevance1=0;
			}else{
				$countRelevance1=$getRelevanceCount1->countRelevance1;
			}
			if($getRelevanceCount0->countRelevance0==""){
				$countRelevance0=0;
			}else{
				$countRelevance0=$getRelevanceCount0->countRelevance0;
			}
			
			if($getWorthCount1->countWorth1==""){
				$countWorth1=0;
			}else{
				$countWorth1=$getWorthCount1->countWorth1;
			}
			if($getWorthCount0->countWorth0==""){
				$countWorth0=0;
			}else{
				$countWorth0=$getWorthCount0->countWorth0;
			}
			//End
			//echo $countRelevance1 .'--'. $countRelevance0 .'--'. $countWorth1 .'--'. $countWorth0; exit;
			$getCommentss = $this->getDataboxCommentsTable()->getDataboxComments($categoryId)->toArray();
			$getComments =array();
			foreach($getCommentss as $comment){
				if($comment['parent_comment_id']!='' && $comment['parent_comment_id']!='0'){
					$getComments[$comment['parent_comment_id']]['sub'][]=$comment;
				}else{
					$getComments[$comment['databox_comment_id']]=$comment;
				}
			}
			// echo "<pre>";print_r($getComments);exit;
			// Added code for Relevance Worth Liked and Disliked By dileep
			$votePerLikeDis = $relevanceWorthVoteTable->votesPercentageAndLD($categoryId);
			// echo "<pre>";print_r($votePerLikeDis);exit;
			
			//Start
			if(isset($_SESSION['usersinfo']->userId)){
				$getTotalLinks = $this->getUserCategoriesTable()->getTotalLinks( $_SESSION['usersinfo']->userId)->count();
				$_SESSION['usersinfo']->getTotalLinks=$getTotalLinks;
				$getHighlights = $this->getUserCategoriesTable()->getHighlightDataboxCount( $_SESSION['usersinfo']->userId);
				$getHighlightsCount = count($getHighlights->toArray());
				$_SESSION['usersinfo']->HighLightsTotalcount=$getHighlightsCount;

				$getPublicDataboxCount = $this->getUserCategoriesTable()->getPublicDataboxCount( $user_id);
				$getPrivateDataboxCount = $this->getUserCategoriesTable()->getPrivateDataboxCount( $user_id);
				$publicprivatetotalcount=count($getPublicDataboxCount->toArray()) + count($getPrivateDataboxCount->toArray());
				$_SESSION['usersinfo']->totalcount=$publicprivatetotalcount;
			}
			//End
			
			$userStatusLD = $relevanceWorthVoteTable->userLikedDisLiked($categoryId);
			return new ViewModel(array(				
				'baseUrl' 					=> 	$baseUrl,
				'basePath' 					=> 	$basePath,
				'options'					=>	$this->getOptions(),
				'databoxLinksGrid' 			=> 	$databoxLinksGrid,
				'hashName' 					=> 	$hashName,
				'categoryTitle' 			=> 	$categoryTitle,
				'main_cat_name' 			=> 	$main_cat_name,
				'sub_cat_name' 				=> 	$sub_cat_name,
				'cat_name' 					=> 	$cat_name,
				'categoryId' 				=> 	$categoryId,
				'data' 						=> 	$data,
				'categoryRelevanceStatus' 	=> 	$categoryRelevanceStatus,
				'categoryWorthStatus' 		=> 	$categoryWorthStatus,
				'countRelevance1' 			=> 	$countRelevance1,
				'countRelevance0' 			=> 	$countRelevance0,
				'countWorth1' 				=> 	$countWorth1,
				'countWorth0' 				=> 	$countWorth0,
				'ImageNumber' 				=> 	$categoryImage,
				'displayedCategoryUserId' 	=> 	$displayedCategoryUserId,
				'displayedCatUName' 		=> 	$displayedCatUName,
				'displayedUMontImage' 		=> 	$displayedUMontImage,
				'categoryDescripton' 		=> 	$categoryDescripton,
				'isPrivateDatabox' 			=> 	$isPrivateDatabox,
				'boxKeywords' 				=> 	$boxKeywords,
				'getComments' 				=> 	$getComments,
				'votePerLikeDis' 			=> 	$votePerLikeDis,
				'userStatusLD' 			    => 	$userStatusLD,
				'category_type' 			=>  $category_type,
				//'getTotalLinks' 		    => 	$getTotalLinks,
				//'getHighlightsCount' 		=> 	$getHighlightsCount,
				//'publicprivatetotalcount'   => 	$publicprivatetotalcount,
				'category_highlight' 		=> 	$category_highlight

			));
		}
	}
	public function viewHorizontalAction(){
		if(isset($_POST) && isset($_POST['catId'])){
			$addRow = $this->getJsPlumbGridTable()->addRow( $_POST );
			return new JsonModel(array(				
				'output' 		=> 	1
			));
		}else{
			$baseUrls = $this->getServiceLocator()->get('config');
			$baseUrlArr = $baseUrls['urls'];
			$baseUrl = $baseUrlArr['baseUrl'];
			$basePath = $baseUrlArr['basePath'];
			$databoxLinksGrid=array();
			$categoryId = 0;
			$hashName="";
			$categoryTitle="";
			$categoryImage="";
			$categoryDescripton = "";
			$isPrivateDatabox = 1;
			$boxKeywords="";
			$catDescGot = 0;
			if($this->params()->fromRoute('id', 0)!="")
			{
				$params=$this->params()->fromRoute('id', 0);
				$paramss=explode("+",$params);
				$categoryId=$paramss[0];
				if( isset($paramss[1]) )
				{
					$hashName='#'.$paramss[2];
					$categoryTitles=str_replace('~','-',$paramss[3]);
					$categoryTitle=str_replace('-',' ',$categoryTitles);
					$categoryImage=$paramss[1];
				}
				else
				{
					$catRow = $this->getUserCategoriesTable()->getEditHighlight( $categoryId );
					$hashName="#".substr($catRow->user_hashname,1);
					$categoryTitle=str_replace('-',' ',$catRow->category_title);
					$categoryImage1=explode(".",$catRow->category_image);
					$categoryImage=$categoryImage1[0];
					$main_cat_name = $catRow->main_cat_tittle;
					$sub_cat_name = $catRow->sub_cat_tittle;
					$cat_name = $catRow->category_name;
					$categoryDescripton = $catRow->category_description;
					if( ($catRow->category_type == 0) && ( ! is_null($catRow->secret_code) && (trim($catRow->secret_code) != "" ) ) )
					{
						$isPrivateDatabox = 0;
					}
					if( ! is_null($catRow->meta_tags) && (trim($catRow->meta_tags) != "") )
					{
						$boxKeywords = $catRow->meta_tags;
					}
					$catDescGot = 1;
				}
			}

			$displayedCategoryUserId = 0;

			$databoxLinksGridd = $this->getJsPlumbGridTable()->getRow( $categoryId );
			$data=1;
			if(!$databoxLinksGridd->count()){
				$databoxLinksGrids = $this->getLinkDetailsTable()->getDataboxGrid( $categoryId );
				$databoxLinksGrid = $databoxLinksGrids->buffer();
				$main_cat_name = $databoxLinksGrids->buffer()->current()->main_cat_tittle;
				$sub_cat_name = $databoxLinksGrids->buffer()->current()->sub_cat_tittle;
				$cat_name = $databoxLinksGrids->buffer()->current()->category_name;
				$displayedCategoryUserId = $databoxLinksGrids->buffer()->current()->user_id;
				$categoryDescripton = $databoxLinksGrids->buffer()->current()->category_description;
				if( ($databoxLinksGrids->buffer()->current()->boxPrivacy == 0) && ( ! is_null($databoxLinksGrids->buffer()->current()->secret_code) && (trim($databoxLinksGrids->buffer()->current()->secret_code) != "" ) ) )
				{
					$isPrivateDatabox = 0;
				}
				if( ! is_null($databoxLinksGrids->buffer()->current()->meta_tags) && (trim($databoxLinksGrids->buffer()->current()->meta_tags) != "") )
				{
					$boxKeywords = $databoxLinksGrids->buffer()->current()->meta_tags;
				}
				$catDescGot = 1;
				$data=0;
			}else{
				$databoxLinksGrid=$databoxLinksGridd;
				$displayedCategoryUserId = $databoxLinksGridd->buffer()->current()->user_id;
				$main_cat_name = "";
				$sub_cat_name = "";
				$cat_name = "";
			}
			//echo '<pre>'; print_r($databoxLinksGrid);exit;
			
			$displayedCatUName = "User name";
			$userRow = $this->getUserTable()->getUserRow( $displayedCategoryUserId );
			if( isset($userRow->display_name) && trim($userRow->display_name != "" ) )
			{
				$displayedCatUName = $userRow->display_name;
			}
			
			if( $catDescGot == 0 )
			{
				$ucRow = $this->getUserCategoriesTable()->getEditHighlight( $categoryId );
				$categoryDescripton = $ucRow->category_description;
				$main_cat_name = $ucRow->main_cat_tittle;
				$sub_cat_name = $ucRow->sub_cat_tittle;
				$cat_name = $ucRow->category_name;
				if( ($ucRow->category_type == 0) && ( ! is_null($ucRow->secret_code) && (trim($ucRow->secret_code) != "" ) ) )
				{
					$isPrivateDatabox = 0;
				}
				if( ! is_null($ucRow->meta_tags) && (trim($ucRow->meta_tags) != "") )
				{
					$boxKeywords = $ucRow->meta_tags;
				}
			}

			//Get Relevance Worth
			if(isset($_SESSION['usersinfo']->userId)){
				$user_id=$_SESSION['usersinfo']->userId;
			}else{
				$user_id=$_SERVER['REMOTE_ADDR'];
			}
			$relevanceWorthVoteTable=$this->getServiceLocator()->get('Databox\Model\RelevanceWorthVoteFactory');
			$getRelevanceWorth = $relevanceWorthVoteTable->getVoteUpDown( $categoryId,$user_id );
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
			//End
			$getRelevanceCount1 = $relevanceWorthVoteTable->getRelevanceCount( $categoryId,1 );
			$getRelevanceCount0 = $relevanceWorthVoteTable->getRelevanceCount( $categoryId,0 );
			$getWorthCount1 = $relevanceWorthVoteTable->getWorthCount( $categoryId,1 );
			$getWorthCount0 = $relevanceWorthVoteTable->getWorthCount( $categoryId,0 );
			//Total Count Relevance And Worth
			if($getRelevanceCount1->countRelevance1==""){
				$countRelevance1=0;
			}else{
				$countRelevance1=$getRelevanceCount1->countRelevance1;
			}
			if($getRelevanceCount0->countRelevance0==""){
				$countRelevance0=0;
			}else{
				$countRelevance0=$getRelevanceCount0->countRelevance0;
			}
			
			if($getWorthCount1->countWorth1==""){
				$countWorth1=0;
			}else{
				$countWorth1=$getWorthCount1->countWorth1;
			}
			if($getWorthCount0->countWorth0==""){
				$countWorth0=0;
			}else{
				$countWorth0=$getWorthCount0->countWorth0;
			}
			//End
			//echo '<pre>'; print_r($databoxLinksGrid); exit;
			return new ViewModel(array(				
				'baseUrl' 			=> 	$baseUrl,
				'basePath' 			=> 	$basePath,
				'options'			=>	$this->getOptions(),
				'databoxLinksGrid' 	=> 	$databoxLinksGrid,
				'hashName' 			=> 	$hashName,
				'categoryTitle' 	=> 	$categoryTitle,
				'main_cat_name' 	=> 	$main_cat_name,
				'sub_cat_name' 		=> 	$sub_cat_name,
				'cat_name' 			=> 	$cat_name,
				'categoryId' 		=> 	$categoryId,
				'data' 				=> 	$data,
				'categoryRelevanceStatus' 	=> 	$categoryRelevanceStatus,
				'categoryWorthStatus' 		=> 	$categoryWorthStatus,
				'countRelevance1' 			=> 	$countRelevance1,
				'countRelevance0' 			=> 	$countRelevance0,
				'countWorth1' 				=> 	$countWorth1,
				'countWorth0' 				=> 	$countWorth0,
				'ImageNumber' 				=> 	$categoryImage,
				'displayedCatUName' 		=> 	$displayedCatUName,
				'categoryDescripton' 		=> 	$categoryDescripton,
				'isPrivateDatabox' 			=> 	$isPrivateDatabox,
				'boxKeywords' 				=> 	$boxKeywords
			));
		}
	}

	public function myCollectedLinksAction(){
		// echo "myCollectedLinks";exit;
		
		if( isset($_POST) )
		{
			if( isset($_POST['catLinkId']) )
			{
				$addRow = $this->getUserCollectionsTable()->addUserCollection( $_POST );
				
				if( isset($_SESSION['usersinfo']) && isset($_SESSION['usersinfo']->collectionsCount) )
				{
					$_SESSION['usersinfo']->collectionsCount += 1;
				}
				
				return new JsonModel(array(				
					'output' 		=> 	1
				));
			}
			else if( isset($_POST['collection_id']) )
			{
				$deleteStatus = $this->getUserCollectionsTable()->deleteCollectedLink( $_POST['collection_id'] );
				
				if( isset($_SESSION['usersinfo']) && isset($_SESSION['usersinfo']->collectionsCount) )
				{
					$_SESSION['usersinfo']->collectionsCount -= 1;
				}
				
				return new JsonModel(array(				
					'output' 		=> 	1
				));
			}
			$userCollectedLinksArr = $this->getUserCollectionsTable()->getUserCollectedLinks()->toArray();

			$baseUrls = $this->getServiceLocator()->get('config');
			$baseUrlArr = $baseUrls['urls'];
			$baseUrl = $baseUrlArr['baseUrl'];
			$basePath = $baseUrlArr['basePath'];
			return new ViewModel(array(				
				'baseUrl' 	=> $baseUrl,
				'basePath' 	=> $basePath,
				'userCollectedLinksArr' 	=> $userCollectedLinksArr
			));
		}
		else
		{
			$userCollectedLinksArr = $this->getUserCollectionsTable()->getUserCollectedLinks()->toArray();
			// echo "<pre>";print_r($userCollectedLinksArr);exit;

			$baseUrls = $this->getServiceLocator()->get('config');
			$baseUrlArr = $baseUrls['urls'];
			$baseUrl = $baseUrlArr['baseUrl'];
			$basePath = $baseUrlArr['basePath'];
			return new ViewModel(array(				
				'baseUrl' 	=> $baseUrl,
				'basePath' 	=> $basePath,
				'userCollectedLinksArr' 	=> $userCollectedLinksArr
			));
		}
	}
	public function checkMyCollectedLinksAction()
	{
			$userCollectedLinksArr = $this->getUserCollectionsTable()->getCheckCollectedLinks($_POST['catLinkId']);
			if( $userCollectedLinksArr->count() != 0 )
			{
				$output = 1;
			}else{
				$output = 0;
			}
			return $view = new JsonModel(
			array(
				'output'	=> $output
			));
	}
	public function searchHighHashNamesAction(){
		$hashNames="";
		$getsearchHashNames = $this->getUserCategoriesTable()->getsearchHighHashNames($_POST['value']);
		if($getsearchHashNames->count()!=0){
			foreach($getsearchHashNames as $key=>$search){
				$hashNames[$key]=$search->user_hashname;
				$hashNameIds[$key]=$key;
				$count=$key;				
			}		
			$combined = array();
			foreach($hashNames as $index => $refNumber) {			
				$combined[] = array(
					'ref'  => $refNumber,
					'part' => $hashNameIds[$index]
				);
			}
			return $view = new JsonModel(
			array(
				'output'			=>	1,
				'searchHashNames'	=>	$combined,
				'countt'				=>	$count,
			));
		}else{
			return $view = new JsonModel(
			array(
				'output'			=>	0,
			));
		}
	}
	//Book Marks Pop Up
	private function bookMarksPopUp($links){
		$bookMarksArray=array();
		$bookMarkssArray=array();
		$bookMarksssArray=array();
		if($this->fileExtension=='json'){
			foreach($links as $bookMarkss){
				foreach($bookMarkss as $bookMarks){
					if(!array_key_exists('children',$bookMarks)){
						$bookMarksArray['Bookmarks Menu'][]=$bookMarks;
					}else{
						$bookMarkssArray['Bookmarks Menu Remaining'][]=$bookMarks;
					}
				}
			}
			foreach($bookMarkssArray['Bookmarks Menu Remaining'] as $bookMarkk){
				foreach($bookMarkk as $bookkMark){
					foreach($bookkMark as $bookMark){
						if(!array_key_exists('children',$bookMark)){
							$bookMarksArray['Bookmarks Menu'][$bookMarkk['title']][]=$bookMark;
						}else{
							$bookMarksssArray['sub'][]=$bookMark;
						}
					}
				}
			}
			foreach($bookMarksssArray['sub'] as $subBook){
				foreach($subBook['children'] as $sbookMark){
					$bookMarksArray['Bookmarks Menu'][$bookMarkk['title']][$subBook['title']][]=$sbookMark;
				}
			}
		}else if($this->fileExtension=='bak'){
			foreach($links as $key=>$bookMarkss){
				if($key==0){
					if(isset($bookMarkss['url'])){
						$bookMarksArray['Bookmarks Menu'][]['uri']=$bookMarkss;
					}else{
						foreach($bookMarkss as $keyy=>$bookMarks){
							if($keyy=='children'){
								foreach($bookMarkss['children'] as $keyyy=>$bookMarks){
									if($keyyy==0){
										if(isset($bookMarks['url'])){
											$bookMarksArray['Bookmarks Menu'][$bookMarkss['name']][]['uri']=$bookMarks;
										}else{
											foreach($bookMarks as $keyyyy=>$bookMarksss){
												if($keyyyy=='children'){
													foreach($bookMarks['children'] as $bookMarkssss){
														$bookMarksArray['Bookmarks Menu'][$bookMarkss['name']][$bookMarks['name']][]['uri']=$bookMarkssss;
													}
												}
											}
										}
									}else{
										$bookMarksArray['Bookmarks Menu'][$bookMarkss['name']][]['uri']=$bookMarks;
									}
								}
							}
						}
					}
				}else{
					$bookMarksArray['Bookmarks Menu'][]['uri']=$bookMarkss;
				}
			}
		}
		$htmlBook="";
		// echo "<pre>";print_r($bookMarksArray);exit;
		$linkUrl = "";
		$linkTitle = "";
		foreach($bookMarksArray as $keyB=>$userBook){
			$htmlBook.='<p style="font-size:9pt;color:#444;"><input type="checkbox" id="main-0" value="main">&nbsp;&nbsp;<b>'.$keyB.'</b></p>';
			foreach($userBook as $keyy=>$linkUri){
				if(is_numeric($keyy)){
					if(isset($linkUri['uri'])){
						$linkUrl = "";
						$linkTitle = "";
						if( $this->fileExtension=='json' )
						{
							$linkUrl = $linkUri['uri'];
							$linkTitle=$linkUri['uri'];
							if( isset($linkUri['title']) && trim($linkUri['title']) != "" )
							{
								$linkTitle=trim($linkUri['title']);
							}
						}
						else if( $this->fileExtension=='bak' )
						{
							if( isset($linkUri['uri']['url']) && trim($linkUri['uri']['url']) != "" )
							{
								$linkUrl=trim($linkUri['uri']['url']);
								$linkTitle=trim($linkUri['uri']['url']);
							}
							if( isset($linkUri['uri']['name']) && trim($linkUri['uri']['name']) != "" )
							{
								$linkTitle=trim($linkUri['uri']['name']);
							}
						}
						$htmlBook.='<p style="margin-left:20px;font-size:9pt;color:#444;"><input type="checkbox" class="uriClass" id="main-'.$keyy.'"  value="'.$linkUrl.'">&nbsp;&nbsp;'.$linkTitle.'</p>';
					}
				}else{
					$htmlBook.='<p style="margin-left:20px;font-size:9pt;color:#444;"><input type="checkbox" id="main-cat-'.$keyy.'" value="cat-'.$keyy.'" >&nbsp;&nbsp;<b>'.$keyy.'</b></p>';
					foreach($linkUri as $cat=>$category){
						if(is_numeric($cat)){
							if(isset($category['uri'])){
								$linkUrl = "";
								$linkTitle = "";
								if( $this->fileExtension=='json' )
								{
									$linkUrl = $category['uri'];
									$linkTitle=$category['uri'];
									if( isset($category['title']) && trim($category['title']) != "" )
									{
										$linkTitle=trim($category['title']);
									}
								}
								else if( $this->fileExtension=='bak' )
								{
									if( isset($category['uri']['url']) && trim($category['uri']['url']) != "" )
									{
										$linkUrl=trim($category['uri']['url']);
										$linkTitle=trim($category['uri']['url']);
									}
									if( isset($category['uri']['name']) && trim($category['uri']['name']) != "" )
									{
										$linkTitle=trim($category['uri']['name']);
									}
								}
								$htmlBook.='<p style="margin-left:40px;font-size:9pt;color:#444;"><input type="checkbox" class="uriClass" id="main-cat-'.$keyy.'-'.$cat.'" value="'.$linkUrl.'">&nbsp;&nbsp;'.$linkTitle.'</p>';
							}
						}else{
							$htmlBook.='<p style="margin-left:40px;font-size:9pt;color:#444;"><input type="checkbox" id="main-cat-'.$keyy.'-subcat-'.$cat.'" value="subcat-'.$cat.'-'.$keyy.'" >&nbsp;&nbsp;<b>'.$cat.'</b></p>';
							foreach($category as $subcat=>$subcategory){
								if(isset($subcategory['uri'])){
									$linkUrl = "";
									$linkTitle = "";
									if( $this->fileExtension=='json' )
									{
										$linkUrl = $subcategory['uri'];
										$linkTitle=$subcategory['uri'];
										if( isset($subcategory['title']) && trim($subcategory['title']) != "" )
										{
											$linkTitle=trim($subcategory['title']);
										}
									}
									else if( $this->fileExtension=='bak' )
									{
										if( isset($subcategory['uri']['url']) && trim($subcategory['uri']['url']) != "" )
										{
											$linkUrl=trim($subcategory['uri']['url']);
											$linkTitle=trim($subcategory['uri']['url']);
										}
										if( isset($subcategory['uri']['name']) && trim($subcategory['uri']['name']) != "" )
										{
											$linkTitle=trim($subcategory['uri']['name']);
										}
									}
									$htmlBook.='<p style="margin-left:60px;font-size:9pt;color:#444;"><input type="checkbox" class="uriClass" id="main-cat-'.$keyy.'-subcat-'.$cat.'-'.$subcat.'" value="'.$linkUrl.'">&nbsp;&nbsp;'.$linkTitle.'</p>';
								}
							}
						}
					}
				}
			}

		}
		$htmlBook.='<div style="position:absolute;bottom:0;left:40px;"><b style="color:#227cec">Total Selected Links</b> : <b id="selectedTotalBM">0</b></div><div style="position:absolute;bottom:11px;right:40px;"><a onclick="cancelBookMarksUrls()" class="btn" href="javascript:void(0);">CANCEL</a>&nbsp;&nbsp;&nbsp;<a onclick="checkBookMarksUrls()" class="btn" href="javascript:void(0);">UPLOAD</a></div>';
		return $htmlBook; 
	}
	//End
	public function updateViewsCountAction()
	{
		$totCount = 0;
		$updateViewsCountTable = $this->getDataboxViewsTable()->updateViewsCount($_POST['categoryId'])->count();
		if($updateViewsCountTable==0){
			$databoxViewsTable=	$this->getDataboxViewsTable()->insertDataboxUserId($_POST['categoryId']);
		}
		return $result = new JsonModel(
			array(
				'output'			=>	1,
				'updateViewsCountTable'	=>	$updateViewsCountTable,
			));
	}
	public function getDataboxViewsTable()
    {
        if (!$this->databoxViewsTable) {				
            $sm = $this->getServiceLocator();
            $this->databoxViewsTable = $sm->get('Databox\Model\DataboxViewsFactory');			
        }
        return $this->databoxViewsTable;
    }
	public function getUserCollectionsTable()
    {
        if (!$this->userCollectionsTable) {				
            $sm = $this->getServiceLocator();
            $this->userCollectionsTable = $sm->get('Databox\Model\UserCollectionsFactory');			
        }
        return $this->userCollectionsTable;
    }
	public function getDataboxCommentsTable()
    {
        if (!$this->databoxCommentsTable) {				
            $sm = $this->getServiceLocator();
            $this->databoxCommentsTable = $sm->get('Databox\Model\DataboxCommentsFactory');			
        }
        return $this->databoxCommentsTable;
    }
	public function insertCommentAction()
    {
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$totCount = '';
		$databoxUser = '';
		$insertComment = $this->getDataboxCommentsTable()->addComment($_POST);
		if($insertComment>0){
			$totCount =	$this->getDataboxCommentsTable()->totalCommentsOfDataBox($_POST['category_id']);
			if($totCount=='5'){
				$databoxInfo = $this->getCategoryTable()->getInfo( $_POST['category_id'] );	
				if(count($databoxInfo)>0){
					$databoxUser = $databoxInfo->user_id;
					$update_fresh_status = $this->getCategoryTable()->updateFreshStatus( $_POST['category_id'] );
				}
			}
		}
		$getCommentss = $this->getDataboxCommentsTable()->getDataboxComments($_POST['category_id'])->toArray();
		$getComments =array();
		foreach($getCommentss as $comment){
			if($comment['parent_comment_id']!='' && $comment['parent_comment_id']!='0'){
				$getComments[$comment['parent_comment_id']]['sub'][]=$comment;
			}else{
				$getComments[$comment['databox_comment_id']]=$comment;
			}
		}
		$html = '';
		$headerMontageImage="";
		if( strpos($_SESSION['usersinfo']->montage_image, "https://" ) !== false || strpos($_SESSION['usersinfo']->montage_image, "http://") !== false )
		{
			$headerMontageImage=$_SESSION['usersinfo']->montage_image;
		}
		else
		{
			$headerMontageImage=$basePath.'/images/project/montageImages/'.$_SESSION['usersinfo']->montage_image;
		}
		if(count($getComments)!=0){foreach($getComments as $getComments){ 
			$html.='<div id="comment'.$getComments["databox_comment_id"].'" class="">';
			$html.='<div class="row tmar_b20">';
			$html.='<div class="rimg_left"><img  width="55px" src="'.$headerMontageImage.'" alt="" class="height100" /></div>';
			$html.='<div class="rcontenleft">';
			$html.='<div class="name_c">'.$getComments['display_name'].'</div>';
			$html.='<span id="commText'. $getComments['databox_comment_id'].'">'.$getComments["databox_comment"].'</span><br/><br/>';
			$html.='<span id="commTextUpdatedDate'. $getComments['databox_comment_id'].'"> Commented On : '.$getComments["updated_date"].'</span><br/>';
			if(isset($_SESSION['usersinfo']->userId)){if($_SESSION['usersinfo']->userId==$getComments['comment_user_id']){ 
			$html.='<br/><div class="comment_btns_pos_ab">';
			$html.='<button class="comment_edit_btn" onClick="editComment('.$getComments["databox_comment_id"].')">Edit</button>&nbsp;'; 
			$html.='<button id="deleteComment'.$getComments["databox_comment_id"].'" class="comment_delete_btn" onClick="deletedComment('.$getComments["databox_comment_id"].')">Delete</button>&nbsp;';
			$html.='<button id="replyComment'.$getComments["databox_comment_id"].'" class="comment_edit_btn" onClick="replyComment('.$getComments['databox_comment_id'].')">Reply</button></div>';
			} else{ 
			$html.='<div class="comment_btns_pos_ab">';
			$html.='<button id="replyComment'.$getComments["databox_comment_id"].'" class="comment_edit_btn" onClick="replyComment('.$getComments["databox_comment_id"].')">Reply</button></div>';
			}}
			$html.='<div style="display:none;margin-left:8%;" id="replyDiv'.$getComments["databox_comment_id"].'">';
			$html.='<textarea id="msg'.$getComments['databox_comment_id'].'" class="" placeholder="Enter your replay"></textarea>';
			$html.='<div class="comment_btn_bg">';
			$html.='<button id="reCommentSend'.$getComments["databox_comment_id"].'" onClick="insertReplyComment('.$getComments["databox_comment_id"].')" class="btn">Reply SEND</button>';
			$html.='<input type="hidden" id="hidReplySendComment" value="0">';
			$html.='<input type="hidden" id="hidUpReplySendComment" value="0" >';
			$html.='<input type="hidden" id="parentCid" value="0"></div></div></div></div></div><div class="clearfix"></div>';
			if(isset($getComments['sub']) && count($getComments['sub'])>0){ foreach($getComments['sub'] as $getCommentss){
			$html.='<span id="insert_div'.$getCommentss["parent_comment_id"].'">';		
			$html.='<div id="reCommentsList'.$getCommentss["databox_comment_id"].'" style="margin-left:5%;">';
			$html.='<div id="reComment'.$getCommentss['databox_comment_id'].'"class="">';
			$html.='<div class="row tmar_b20">';
			$html.='<div class="rimg_left"><img  width="55px" src="<?php echo $basePath; ?>/images/project/montageImages/'.$getCommentss["montage_image"].'" alt="" class="height100" /></div>';
			$html.='<div class="rcontenleft">';
			$html.='<div class="name_c">'.$getCommentss['display_name'].'</div>';
			$html.='<span id="reCommText'.$getCommentss['databox_comment_id'].'">'.$getCommentss['databox_comment'].'</span><br/><br/>';
			$html.='<span id="reCommTextUpdatedDate'.$getCommentss['databox_comment_id'].'">Commented On : '.$getCommentss['updated_date'].'</span><br/>';
			if(isset($_SESSION['usersinfo']->userId)){if($_SESSION['usersinfo']->userId==$getCommentss['comment_user_id']){
			$html.='<br/><div class="comment_btns_pos_ab">';
			$html.='<button class="comment_edit_btn" onClick="editReComment('.$getCommentss['databox_comment_id'].','.$getCommentss['parent_comment_id'].')">Edit</button>&nbsp;'; 
			$html.='<button id="deleteComment'.$getCommentss["databox_comment_id"].'" class="comment_delete_btn" onClick="deletedReComment('. $getCommentss['databox_comment_id'].')">Delete </button>';
			$html.='</div>';
			}} else{}
			$html.='</div></div></div><div class="clearfix"></div></div></span>';	
		} } else {
			$html.='<span id="insert_div'.$getComments["databox_comment_id"].'"></span>';
		}}}
		return $view = new JsonModel(
		array(
			'output'			=>	$insertComment,
			'databoxCommentsCount'	=>	$totCount,
			'databoxUser' =>$databoxUser,
			'comDiv' =>$html,
		));
    }
	public function insertReplayCommentAction()
    {
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$totCount = '';
		$databoxUser = '';
		 $insertComment = $this->getDataboxCommentsTable()->addReplyComment($_POST);
		if($insertComment>0){
			$totCount =	$this->getDataboxCommentsTable()->totalCommentsOfDataBox($_POST['category_id']);
			if($totCount=='5'){
				$databoxInfo = $this->getCategoryTable()->getInfo( $_POST['category_id'] );	
				if(count($databoxInfo)>0){
					$databoxUser = $databoxInfo->user_id;
					$update_fresh_status = $this->getCategoryTable()->updateFreshStatus( $_POST['category_id'] );
				}
			}
		}
		return $view = new JsonModel(
		array(
			'output'			=>	$insertComment,
			'databoxCommentsCount'	=>	$totCount,
			'databoxUser' =>$databoxUser
		));
    }
	public function deleteCommentAction()
    {
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$deleteComment = $this->getDataboxCommentsTable()->deleteCommentId($_POST['databox_comment_id'],$_POST['deR']);
		$getCommentss = $this->getDataboxCommentsTable()->getDataboxComments($_POST['category_id'])->toArray();
		$getComments =array();
		foreach($getCommentss as $comment){
			if($comment['parent_comment_id']!='' && $comment['parent_comment_id']!='0'){
				$getComments[$comment['parent_comment_id']]['sub'][]=$comment;
			}else{
				$getComments[$comment['databox_comment_id']]=$comment;
			}
		}
		$html = '';
		$headerMontageImage="";
		if( strpos($_SESSION['usersinfo']->montage_image, "https://" ) !== false || strpos($_SESSION['usersinfo']->montage_image, "http://") !== false )
		{
			$headerMontageImage=$_SESSION['usersinfo']->montage_image;
		}
		else
		{
			$headerMontageImage=$basePath.'/images/project/montageImages/'.$_SESSION['usersinfo']->montage_image;
		}
		if(count($getComments)!=0){foreach($getComments as $getComments){ 
			$html.='<div id="comment'.$getComments["databox_comment_id"].'" class="">';
			$html.='<div class="row tmar_b20">';
			$html.='<div class="rimg_left"><img  width="55px" src="'.$headerMontageImage.'" alt="" class="height100" /></div>';
			$html.='<div class="rcontenleft">';
			$html.='<div class="name_c">'.$getComments['display_name'].'</div>';
			$html.='<span id="commText'. $getComments['databox_comment_id'].'">'.$getComments["databox_comment"].'</span><br/><br/>';
			$html.='<span id="commTextUpdatedDate'. $getComments['databox_comment_id'].'"> Commented On : '.$getComments["updated_date"].'</span><br/>';
			if(isset($_SESSION['usersinfo']->userId)){if($_SESSION['usersinfo']->userId==$getComments['comment_user_id']){ 
			$html.='<br/><div class="comment_btns_pos_ab">';
			$html.='<button class="comment_edit_btn" onClick="editComment('.$getComments["databox_comment_id"].')">Edit</button>&nbsp;'; 
			$html.='<button id="deleteComment'.$getComments["databox_comment_id"].'" class="comment_delete_btn" onClick="deletedComment('.$getComments["databox_comment_id"].')">Delete</button>&nbsp;';
			$html.='<button id="replyComment'.$getComments["databox_comment_id"].'" class="comment_edit_btn" onClick="replyComment('.$getComments['databox_comment_id'].')">Reply</button></div>';
			} else{ 
			$html.='<div class="comment_btns_pos_ab">';
			$html.='<button id="replyComment'.$getComments["databox_comment_id"].'" class="comment_edit_btn" onClick="replyComment('.$getComments["databox_comment_id"].')">Reply</button></div>';
			}}
			$html.='<div style="display:none;margin-left:8%;" id="replyDiv'.$getComments["databox_comment_id"].'">';
			$html.='<textarea id="msg'.$getComments['databox_comment_id'].'" class="" placeholder="Enter your replay"></textarea>';
			$html.='<div class="comment_btn_bg">';
			$html.='<button id="reCommentSend'.$getComments["databox_comment_id"].'" onClick="insertReplyComment('.$getComments["databox_comment_id"].')" class="btn">Reply SEND</button>';
			$html.='<input type="hidden" id="hidReplySendComment" value="0">';
			$html.='<input type="hidden" id="hidUpReplySendComment" value="0" >';
			$html.='<input type="hidden" id="parentCid" value="0"></div></div></div></div></div><div class="clearfix"></div>';
			if(isset($getComments['sub']) && count($getComments['sub'])>0){ foreach($getComments['sub'] as $getCommentss){
			$html.='<span id="insert_div'.$getCommentss["parent_comment_id"].'">';		
			$html.='<div id="reCommentsList'.$getCommentss["databox_comment_id"].'" style="margin-left:5%;">';
			$html.='<div id="reComment'.$getCommentss['databox_comment_id'].'"class="">';
			$html.='<div class="row tmar_b20">';
			$html.='<div class="rimg_left"><img  width="55px" src="<?php echo $basePath; ?>/images/project/montageImages/'.$getCommentss["montage_image"].'" alt="" class="height100" /></div>';
			$html.='<div class="rcontenleft">';
			$html.='<div class="name_c">'.$getCommentss['display_name'].'</div>';
			$html.='<span id="reCommText'.$getCommentss['databox_comment_id'].'">'.$getCommentss['databox_comment'].'</span><br/><br/>';
			$html.='<span id="reCommTextUpdatedDate'.$getCommentss['databox_comment_id'].'">Commented On : '.$getCommentss['updated_date'].'</span><br/>';
			if(isset($_SESSION['usersinfo']->userId)){if($_SESSION['usersinfo']->userId==$getCommentss['comment_user_id']){
			$html.='<br/><div class="comment_btns_pos_ab">';
			$html.='<button class="comment_edit_btn" onClick="editReComment('.$getCommentss['databox_comment_id'].','.$getCommentss['parent_comment_id'].')">Edit</button>&nbsp;'; 
			$html.='<button id="deleteComment'.$getCommentss["databox_comment_id"].'" class="comment_delete_btn" onClick="deletedReComment('. $getCommentss['databox_comment_id'].')">Delete </button>';
			$html.='</div>';
			}} else{}
			$html.='</div></div></div><div class="clearfix"></div></div></span>';	
		} } else {
			$html.='<span id="insert_div'.$getComments["databox_comment_id"].'"></span>';
		}}}
			return $view = new JsonModel(
				array(
					'output'			=>	1,
					'comDiv' =>$html
				));
    }
	public function updateCommentAction()
    {
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$update= $this->getDataboxCommentsTable()->updateCommentId($_POST['databox_comment_id'],$_POST['comment']);
		return $view = new JsonModel(array(
			'output' =>	1,
		));
    }
	public function changeDataboxDetailsAction()
    {
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		if($_POST['type']=="1"){
			$_SESSION['databox']->hashtag = "";
			$_SESSION['databox']->hashtag= $_POST['text'];
			$updateHashNames = $this->getUserCategoriesTable()->updateDataboxHashNames($_POST);
		}else{
			$_SESSION['databox']->hashtitle= "";
			$_SESSION['databox']->hashtitle= $_POST['text'];
			$updateHashNames = $this->getUserCategoriesTable()->updateDataboxHashTitle($_POST);
		}
		return $view = new JsonModel(array(
			'output' =>	1,
		));
    }
	public function deleteLinkAction()
    {
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$categoryLinksDeleteStatus = $this->getCategoryLinksTable()->deleteCustomisedCategoryLink( $_POST['category_link_id'] );		
		return $view = new JsonModel(array(
			'output' =>	1,
		));
    }
}

		

	
