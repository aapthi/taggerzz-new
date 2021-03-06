<?php
namespace Databoxuser\Controller;

use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

use Zend\View\Model\JsonModel;
use Zend\Session\Container;

class DataboxuserController extends AbstractActionController
{
	protected $userTable;
	protected $userDetailsTable;
	protected $userCategoriesTable;
	protected $relevanceWorthVoteTable;
	protected $loginLinkExpiredTable;
	protected $forgotPasswordTable;
	protected $userCollectionsTable;
	protected $blockUserTable;
	protected $userMessagesTable;
	protected $activitypointsTable;
	protected $userpointsTable;
	protected $invitationsTable;
	protected $categoryTable;
	protected $categoryLinksTable;
	protected $rechargeOrders;
	protected $rechargeOrdersTable;

    public function indexAction()
	{
		
	}

    public function emailExistsAction()
    {
		$emailId = "";
		$userPassword = "";
		if( $_POST )
		{
			if( isset($_POST["emailId"]) )
			{
				$emailId = $_POST["emailId"];
			}
			if( isset($_POST["userPassword"]) )
			{
				$userPassword = $_POST["userPassword"];
			}
			if( ($emailId != "") && ($userPassword != "") )
			{
				$userInfo["email"] = $_POST['emailId'];
				$userInfo["password"] = md5($userPassword);
				$userRow = $this->getUserTable()->checkEmailExists( $userInfo );
				
				if( isset($userRow->email) )
				{
					echo "1";exit;
				}
			}
		}
		echo "0";exit;
	}

    public function setRedirectSessionAction()
    {
		if( isset($_POST["sessUnset"]) )
		{
			unset($_SESSION["urlRedirection"]);
			echo "1";exit;
		}
		else if( isset($_POST["postHighlights"]) )
		{
			unset($_SESSION["urlRedirection"]);
			$_SESSION["urlRedirection"] = "postHighlights";
		}
		else if( isset($_POST["nsfwMcSet"]) )
		{
			unset($_SESSION["urlRedirection"]);
			$_SESSION["urlRedirection"][0][0] = "nsfwMcSet";
			$_SESSION["urlRedirection"][0][1] = $_POST["categoryId"];
			$_SESSION["urlRedirection"][0][2] = $_POST["hashName"];
			$_SESSION["urlRedirection"][0][3] = $_POST["categoryTitle"];
			$_SESSION["urlRedirection"][0][4] = $_POST["settingId"];
			$_SESSION["urlRedirection"][0][5] = $_POST["categoryImage"];
		}
		echo "1";exit;
	}

    public function userLoginAction()
    {
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];

		$emailId = "";
		$userPassword = "";
		$imageNameCrop = "";
		if($this->params()->fromRoute('id', 0)!="")
		{
			$params=$this->params()->fromRoute('id', 0);
			$imageNameCrop=str_replace("-",".",$params);
		}else{
			if( $_POST )
			{
				if(isset($_POST['hid_log']) && $_POST['hid_log']=="logChe"){
					if( isset($_POST["emailId1"]) )
					{
						$emailId = $_POST["emailId1"];
					}
					if( isset($_POST["userPassword1"]) )
					{
						$userPassword = $_POST["userPassword1"];
					}				
				}else if(isset($_POST['hid_log']) && $_POST['hid_log']=="loginPopup"){
					if( isset($_POST["emailId2"]) )
					{
						$emailId = $_POST["emailId2"];
					}
					if( isset($_POST["userPassword2"]) )
					{
						$userPassword = $_POST["userPassword2"];
					}				
				}else{
					if( isset($_POST["emailId45"]) )
					{
						$emailId = $_POST["emailId45"];
					}
					if( isset($_POST["userPassword45"]) )
					{
						$userPassword = $_POST["userPassword45"];
					}
				}
				if( ($emailId != "") && ($userPassword != "") )
				{
					$userInfo["email"] = $emailId;
					$userInfo["password"] = md5($userPassword);
					$userRow = $this->getUserTable()->checkUserExists( $userInfo );
					if( isset($userRow->display_name) )
					{
						if( (isset($userRow->userStatus)) && ($userRow->userStatus == 2) )
						{
							$user=array();
							$user["userId"] = $userRow->u_user_id;
							$user["status"] = "1";
							$userRowUpdated = $this->getUserTable()->changeAccountStatus( $user );
						}

						$user_session = new Container('usersinfo');
						$user_session->userId=$userRow->u_user_id;
						$user_session->email=$emailId;
						$user_session->displayName=$userRow->display_name;
						$user_session->montage_hash_name=$userRow->montage_hash_name;
						$user_session->montage_title=$userRow->montage_title;
						$user_session->montage_paragraph=$userRow->montage_paragraph;
						$user_session->montage_image=$userRow->montage_image;
						$user_session->montage_main_image=$userRow->montage_main_image;
						$user_session->hinting_state=$userRow->hinting_state;
						$user_session->disable_messageing=$userRow->disable_messageing;
						$getPublicDataboxCount = $this->getUserCategoriesTable()->getPublicDataboxCount( $_SESSION['usersinfo']->userId);
						$getPrivateDataboxCount = $this->getUserCategoriesTable()->getPrivateDataboxCount( $_SESSION['usersinfo']->userId);
						$getHighlights = $this->getUserCategoriesTable()->getHighlightDataboxCount( $_SESSION['usersinfo']->userId);
						$getHighlightsCount = count($getHighlights->toArray());
						$publicprivatetotalcount=count($getPublicDataboxCount->toArray()) + count($getPrivateDataboxCount->toArray());
						$userCollectedLinksCount= $this->getUserCollectionsTable()->getCollectedLinksCount($_SESSION['usersinfo']->userId);
						$collectionsCount=count($userCollectedLinksCount->toArray());
						$getBlockUserDetails= $this->getBlockUserTable()->getBlockedIds();
						$finalIds='';
						$getTotalLinks = $this->getUserCategoriesTable()->getTotalLinks( $_SESSION['usersinfo']->userId)->count();

						foreach($getBlockUserDetails as $key=>$blockUser){

							if($blockUser->block_by_uid==$_SESSION['usersinfo']->userId){
								$finalIds .='"'. $blockUser->blocked_to_uid.'"' . ',';
							}
							if($blockUser->blocked_to_uid==$_SESSION['usersinfo']->userId){
								$finalIds.='"'.$blockUser->block_by_uid.'"' . ',';
							}
						}
						$frnds= rtrim($finalIds,',');
						$userMessagesCount= count($this->getUserMessagesTable()->getUserMessages( $_SESSION['usersinfo']->userId,$frnds)->toArray());
						$user_session->totalcount=$publicprivatetotalcount;
						$user_session->HighLightsTotalcount=$getHighlightsCount;
						$user_session->getTotalLinks=$getTotalLinks;
						$user_session->userMessagesCount=$userMessagesCount;
						$user_session->collectionsCount=$collectionsCount;
						$userpointsTable = $this->getUserPointsTable();
						$userPointss = $userpointsTable->loggedUserPoints($_SESSION['usersinfo']->userId);
						$userPoints ='0';
						if(count($userPointss)>0){
							$userPoints = $userPointss->userPoints;
						}
						$user_session->rewardPoints=$userPoints;
						$loginView = new ViewModel(
							array(
								'baseUrl' 	=> $baseUrl,
								'basePath' 	=> $basePath,
							));

						if( (isset($_POST["postHighlights"])) && ($_POST["postHighlights"] == 1) )
						{
							return $this->redirect()->toUrl($baseUrl . '/databox/highlights-both');
						}
						else if( (isset($_POST["nsfwMcSet"])) && ($_POST["nsfwMcSet"] == 1) )
						{
							if( (isset($_POST["hashName"])) && ($_POST["hashName"] != "") )
							{
								if( isset($_POST["settingId"]) )
								{
									if( $_POST["settingId"]==3 )
									{
										return $this->redirect()->toUrl($baseUrl . '/databox/post-vertical/'.$_POST["categoryId"].'+'.$_POST["categoryImage"].'+'.$_POST["hashName"].'+'.$_POST["categoryTitle"]);
									}
									else if( $_POST["settingId"]==2 )
									{
										return $this->redirect()->toUrl($baseUrl . '/databox/post-horizontal/'.$_POST["categoryId"].'+'.$_POST["categoryImage"].'+'.$_POST["hashName"].'+'.$_POST["categoryTitle"]);
									}
								}
							}
							else
							{
								if( isset($_POST["settingId"]) )
								{
									if( $_POST["settingId"]==3 )
									{
										return $this->redirect()->toUrl($baseUrl . '/databox/post-vertical/'.$_POST["categoryId"]);
									}
									else if( $_POST["settingId"]==2 )
									{
										return $this->redirect()->toUrl($baseUrl . '/databox/post-horizontal/'.$_POST["categoryId"]);
									}
								}
							}
						}
						$_SESSION['logoutStatus']=0;
						//return $loginView->setTemplate( "/databoxuser/databoxuser/redirect-catchoice.phtml" );
						return $this->redirect()->toUrl($baseUrl . '/contentpage');
					}
				}
			}
		}
		return new ViewModel(array(				
			'emailId' 			=> $emailId,
			'userPassword' 		=> $userPassword,
			'baseUrl' 			=> $baseUrl,
			'basePath' 			=> $basePath,
			'imageNameCrop' 	=> $imageNameCrop,
		));
    }

    public function userRedirectAction()
    {
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];

		$dbuser_template = "";
		if(isset($_SESSION['usersinfo']->userId) && $_SESSION['usersinfo']->userId!=''){
			$user_id = $_SESSION['usersinfo']->userId;
		}else{
			$user_id = $_SESSION['Zend_Auth']->storage;
		}
		$getPublicDataboxCount = $this->getUserCategoriesTable()->getPublicDataboxCount( $user_id );
		$getPrivateDataboxCount = $this->getUserCategoriesTable()->getPrivateDataboxCount( $user_id );
		$publicprivatetotalcount=count($getPublicDataboxCount->toArray()) + count($getPrivateDataboxCount->toArray());
		$getBlockUserDetails= $this->getBlockUserTable()->getBlockedIds();
		$finalIds='';
		foreach($getBlockUserDetails as $key=>$blockUser){

			if($blockUser->block_by_uid==$user_id){
				$finalIds .='"'. $blockUser->blocked_to_uid.'"' . ',';
			}
			if($blockUser->blocked_to_uid==$user_id){
				$finalIds.='"'.$blockUser->block_by_uid.'"' . ',';
			}
		}
		$frnds= rtrim($finalIds,',');
		$userMessagesCount= count($this->getUserMessagesTable()->getUserMessages( $user_id,$frnds)->toArray());
		$userCollectedLinksCount= $this->getUserCollectionsTable()->getCollectedLinksCount($user_id);
		$collectionsCount=count($userCollectedLinksCount->toArray());
		$row = $this->getUserDetailsTable()->checkDetailsRecorded($user_id);
		if( $row->countUser == 0 )
		{
			$user_details_id = $this->getUserDetailsTable()->addUserDetails( $user_id );
			$userInfo['status']=1;
			$userInfo['userId']=$user_id;
			// Code for First Login With Facebook/Gmail
			$activityTable = $this->getActivityPointsTable();
			$firstTimeUser = $activityTable->getActivityFresh('freshUser');
			if(count($firstTimeUser)>0){
				$activityId = $firstTimeUser->activity_id;
				$insertedId = $this->activityMethod($user_id,$activityId);
				// Invitations
				if($insertedId>0){
					$userRowI = $this->getUserTable()->getUser( $user_id );
					$uEmail = $userRowI->email;
					$inivitTable = $this->getInvitationsTable();
					$inivtedEmailCheck = $inivitTable->inivtedEmailCheck($uEmail);
					if(count($inivtedEmailCheck)>=0){
						$firstInvitations = $activityTable->getActivityFresh('Invitations');
						$activityIdd = $firstInvitations->activity_id;
						foreach($inivtedEmailCheck as $inivite){
							$user_idd = $inivite->user_id;
							$inivit_id = $inivite->inivit_id;
							$updateStatusCheck = $inivitTable->updateStatus($inivit_id);
							$lastInsertedId = $this->activityMethod($user_idd,$activityIdd);	
						}				
					}
				}
				// End
			}
			//Added Points By Dileep
				$userpointsTable = $this->getUserPointsTable();
				$userPointss = $userpointsTable->loggedUserPoints($user_id);
				$userPoints ='0';
				if(count($userPointss)>0){
					$userPoints = $userPointss->userPoints;
				}	
			// End
			$userRoww = $this->getUserTable()->changeAccountStatus( $userInfo );
			$userRow = $this->getUserTable()->getUser( $user_id );
			$getPublicDataboxCount = $this->getUserCategoriesTable()->getPublicDataboxCount( $user_id);
			$getPrivateDataboxCount = $this->getUserCategoriesTable()->getPrivateDataboxCount( $user_id);
			$publicprivatetotalcount=count($getPublicDataboxCount->toArray()) + count($getPrivateDataboxCount->toArray());
			$getHighlights = $this->getUserCategoriesTable()->getHighlightDataboxCount( $user_id);
			$getHighlightsCount = count($getHighlights->toArray());
			$getTotalLinks = $this->getUserCategoriesTable()->getTotalLinks( $user_id)->count();

			if(isset($_SESSION['usersinfo'])){
				unset($_SESSION['usersinfo']);
			}
			$user_session = new Container('usersinfo');
			$user_session->userId=$userRow->user_id;
			$user_session->email=$userRow->email;
			$user_session->displayName=$userRow->display_name;
			$user_session->montage_hash_name=$userRow->montage_hash_name;
			$user_session->montage_title=$userRow->montage_title;
			$user_session->montage_paragraph=$userRow->montage_paragraph;
			$user_session->montage_image=$userRow->montage_image;
			$user_session->montage_main_image=$userRow->montage_main_image;
			$user_session->hinting_state=$userRow->hinting_state;
			$user_session->disable_messageing=$userRow->disable_messageing;
			$user_session->totalcount=$publicprivatetotalcount;
			$user_session->HighLightsTotalcount=$getHighlightsCount;
			$user_session->getTotalLinks=$getTotalLinks;
			$user_session->userMessagesCount=$userMessagesCount;
			$user_session->collectionsCount=$collectionsCount;
			$user_session->rewardPoints=$userPoints;
			$dbuser_template = "/databoxuser/databoxuser/redirect-userlogin.phtml";
		}
		else
		{
			//Added Points By Dileep
				$userpointsTable = $this->getUserPointsTable();
				$userPointss = $userpointsTable->loggedUserPoints($user_id);
				$userPoints ='0';
				if(count($userPointss)>0){
					$userPoints = $userPointss->userPoints;
				}	
			// End
			$userRow = $this->getUserTable()->getUser( $user_id );
			$getPublicDataboxCount = $this->getUserCategoriesTable()->getPublicDataboxCount( $user_id);
			$getPrivateDataboxCount = $this->getUserCategoriesTable()->getPrivateDataboxCount( $user_id);
			$getHighlights = $this->getUserCategoriesTable()->getHighlightDataboxCount( $user_id);
			$getHighlightsCount = count($getHighlights->toArray());
			$getTotalLinks = $this->getUserCategoriesTable()->getTotalLinks( $user_id)->count();
			$publicprivatetotalcount=count($getPublicDataboxCount->toArray()) + count($getPrivateDataboxCount->toArray());
			if(isset($_SESSION['usersinfo'])){
				unset($_SESSION['usersinfo']);
			}
			$user_session = new Container('usersinfo');
			$user_session->userId=$userRow->user_id;
			$user_session->email=$userRow->email;
			$user_session->displayName=$userRow->display_name;
			$user_session->montage_hash_name=$userRow->montage_hash_name;
			$user_session->montage_title=$userRow->montage_title;
			$user_session->montage_paragraph=$userRow->montage_paragraph;
			$user_session->montage_image=$userRow->montage_image;
			$user_session->montage_main_image=$userRow->montage_main_image;
			$user_session->hinting_state=$userRow->hinting_state;
			$user_session->disable_messageing=$userRow->disable_messageing;
			$user_session->totalcount=$publicprivatetotalcount;
			$user_session->HighLightsTotalcount=$getHighlightsCount;
			$user_session->getTotalLinks=$getTotalLinks;
			$user_session->userMessagesCount=$userMessagesCount;
			$user_session->collectionsCount=$collectionsCount;
			$user_session->rewardPoints=$userPoints;

			if( isset($_SESSION["urlRedirection"]) && $_SESSION["urlRedirection"] == "postHighlights" )
			{
				unset($_SESSION["urlRedirection"]);
				return $this->redirect()->toUrl($baseUrl . '/databox/highlights-both');
			}
			if( isset($_SESSION["urlRedirection"][0][0]) && $_SESSION["urlRedirection"][0][0] == "nsfwMcSet" )
			{
				if( isset($_SESSION["urlRedirection"]) && isset($_SESSION["urlRedirection"][0][2]) && $_SESSION["urlRedirection"][0][2] !="" )
				{
					$catId =  $_SESSION["urlRedirection"][0][1];
					$hashName =  $_SESSION["urlRedirection"][0][2];
					$catTitle =  $_SESSION["urlRedirection"][0][3];
					$settingId = 3;
					$catImage = "";
					if( isset($_SESSION["urlRedirection"][0][4]) )
					{
						$settingId =  $_SESSION["urlRedirection"][0][4];
					}
					if( isset($_SESSION["urlRedirection"][0][5]) )
					{
						$catImage =  $_SESSION["urlRedirection"][0][5];
					}
					if( $settingId==3 )
					{
						return $this->redirect()->toUrl($baseUrl . '/databox/post-vertical/'.$catId.'+'.$catImage.'+'.$hashName.'+'.$catTitle);
					}
					else if( $settingId==2 )
					{
						return $this->redirect()->toUrl($baseUrl . '/databox/post-horizontal/'.$catId.'+'.$catImage.'+'.$hashName.'+'.$catTitle);
					}
					unset($_SESSION["urlRedirection"]);
				}
				else
				{
					$catId =  $_SESSION["urlRedirection"][0][1];
					$settingId = 3;
					if( isset($_SESSION["urlRedirection"][0][4]) )
					{
						$settingId =  $_SESSION["urlRedirection"][0][4];
					}
					if( $settingId==3 )
					{
						return $this->redirect()->toUrl($baseUrl . '/databox/post-vertical/'.$catId);
					}
					else if( $settingId==2 )
					{
						return $this->redirect()->toUrl($baseUrl . '/databox/post-horizontal/'.$catId);
					}
					unset($_SESSION["urlRedirection"]);
				}
			}
			
			$_SESSION['logoutStatus']=0;
			
			$dbuser_template = "/databoxuser/databoxuser/redirect-catchoice.phtml";
		}

		$view = new ViewModel(
			array(
				'baseUrl' 	=> $baseUrl,
				'basePath' 	=> $basePath,
			));
		//return $view->setTemplate( $dbuser_template );
		return $this->redirect()->toUrl($baseUrl . '/contentpage');
	}
	public function skipToContinueAction(){
		$dbuser_template = "/databoxuser/databoxuser/redirect-catchoice.phtml";
		
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$view = new ViewModel(
			array(
				'baseUrl' 	=> $baseUrl,
				'basePath' 	=> $basePath,
			));

			if( isset($_SESSION["urlRedirection"]) && $_SESSION["urlRedirection"] == "postHighlights" )
			{
				unset($_SESSION["urlRedirection"]);
				return $this->redirect()->toUrl($baseUrl . '/databox/highlights-both');
			}
			if( isset($_SESSION["urlRedirection"][0][0]) && $_SESSION["urlRedirection"][0][0] == "nsfwMcSet" )
			{
				if( isset($_SESSION["urlRedirection"]) && isset($_SESSION["urlRedirection"][0][2]) && $_SESSION["urlRedirection"][0][2] !="" )
				{
					$catId =  $_SESSION["urlRedirection"][0][1];
					$hashName =  $_SESSION["urlRedirection"][0][2];
					$catTitle =  $_SESSION["urlRedirection"][0][3];
					$settingId = 3;
					$catImage = "";
					if( isset($_SESSION["urlRedirection"][0][4]) )
					{
						$settingId =  $_SESSION["urlRedirection"][0][4];
					}
					if( isset($_SESSION["urlRedirection"][0][5]) )
					{
						$catImage =  $_SESSION["urlRedirection"][0][5];
					}
					if( $settingId==3 )
					{
						return $this->redirect()->toUrl($baseUrl . '/databox/post-vertical/'.$catId.'+'.$catImage.'+'.$hashName.'+'.$catTitle);
					}
					else if( $settingId==2 )
					{
						return $this->redirect()->toUrl($baseUrl . '/databox/post-horizontal/'.$catId.'+'.$catImage.'+'.$hashName.'+'.$catTitle);
					}
					unset($_SESSION["urlRedirection"]);
				}
				else
				{
					$catId =  $_SESSION["urlRedirection"][0][1];
					$settingId = 3;
					if( isset($_SESSION["urlRedirection"][0][4]) )
					{
						$settingId =  $_SESSION["urlRedirection"][0][4];
					}
					if( $settingId==3 )
					{
						return $this->redirect()->toUrl($baseUrl . '/databox/post-vertical/'.$catId);
					}
					else if( $settingId==2 )
					{
						return $this->redirect()->toUrl($baseUrl . '/databox/post-horizontal/'.$catId);
					}
					unset($_SESSION["urlRedirection"]);
				}
			}

		//return $view->setTemplate( $dbuser_template );
		return $this->redirect()->toUrl($baseUrl . '/contentpage');
	}
    public function updateProfileAction()
    {
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		if( isset($_POST) )
		{
			$password = "";
			$password = md5($_POST["password"]);
			$userInfo=array();
			$userInfo["displayname"] = $_POST['displayname'];
			$userInfo["password"] = $password;
			if(isset($_POST['email'])){
				if($_POST['email']!=""){
					$userInfo["email"] = $_POST['email'];
				}else{
					$userInfo["email"] = "";
				}
			}else{
				$userInfo["email"] = "";
			}
			$userRow = $this->getUserTable()->updateUser( $userInfo,$_SESSION['Zend_Auth']->storage );
			$userDetails=array();
			$userDetails["mobile"] = "";
			if($_POST['cropImage']==0){
				$montageImage = $_SESSION['Zend_Auth']->photoURL;
			}else{
				$montageImage = $_POST['cropImage'];
			}
			/*
			if(isset($_FILES)){
				if($_FILES['accountPublicImageFile']['name']!=""){
					$image = stripslashes($_FILES['accountPublicImageFile']['name']);
					$extension = getExtension($image);
					$extension = strtolower($extension);
					$uploadedfile=$_FILES['accountPublicImageFile']['tmp_name'];
					if($extension=="jpg" || $extension=="jpeg" ){
						$src = imagecreatefromjpeg($uploadedfile);
					}
					else if($extension=="png"){
						$src = imagecreatefrompng($uploadedfile);
					}
					else{
						$src = imagecreatefromgif($uploadedfile);
					}
					list($width,$height)=getimagesize($uploadedfile);
					
					$newwidth=225;
					$newheight=300;
					$tmp=imagecreatetruecolor($newwidth,$newheight);
					imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
					$filename='./public/images/project/montageImages/'.$_FILES['accountPublicImageFile']['name'];
					imagejpeg($tmp,$filename,100);
					imagedestroy($tmp);
					imagedestroy($src);
					$montageImage = $_FILES['accountPublicImageFile']['name'];
				}else{
					$montageImage = $_SESSION['Zend_Auth']->photoURL;
				}
			}*/
			$userDetails["image"] = $montageImage;
			$detRow = $this->getUserDetailsTable()->updateDetails( $userDetails,$_SESSION['Zend_Auth']->storage );
		}

		$view = new ViewModel(
			array(
				'baseUrl' 	=> $baseUrl,
				'basePath' 	=> $basePath,
			));
			
			if( isset($_SESSION["urlRedirection"]) && $_SESSION["urlRedirection"] == "postHighlights" )
			{
				unset($_SESSION["urlRedirection"]);
				return $this->redirect()->toUrl($baseUrl . '/databox/highlights-both');
			}
			if( isset($_SESSION["urlRedirection"][0][0]) && $_SESSION["urlRedirection"][0][0] == "nsfwMcSet" )
			{
				if( isset($_SESSION["urlRedirection"]) && isset($_SESSION["urlRedirection"][0][2]) && $_SESSION["urlRedirection"][0][2] !="" )
				{
					$catId =  $_SESSION["urlRedirection"][0][1];
					$hashName =  $_SESSION["urlRedirection"][0][2];
					$catTitle =  $_SESSION["urlRedirection"][0][3];
					$settingId = 3;
					$catImage = "";
					if( isset($_SESSION["urlRedirection"][0][4]) )
					{
						$settingId =  $_SESSION["urlRedirection"][0][4];
					}
					if( isset($_SESSION["urlRedirection"][0][5]) )
					{
						$catImage =  $_SESSION["urlRedirection"][0][5];
					}
					if( $settingId==3 )
					{
						return $this->redirect()->toUrl($baseUrl . '/databox/post-vertical/'.$catId.'+'.$catImage.'+'.$hashName.'+'.$catTitle);
					}
					else if( $settingId==2 )
					{
						return $this->redirect()->toUrl($baseUrl . '/databox/post-horizontal/'.$catId.'+'.$catImage.'+'.$hashName.'+'.$catTitle);
					}
					unset($_SESSION["urlRedirection"]);
				}
				else
				{
					$catId =  $_SESSION["urlRedirection"][0][1];
					$settingId = 3;
					if( isset($_SESSION["urlRedirection"][0][4]) )
					{
						$settingId =  $_SESSION["urlRedirection"][0][4];
					}
					if( $settingId==3 )
					{
						return $this->redirect()->toUrl($baseUrl . '/databox/post-vertical/'.$catId);
					}
					else if( $settingId==2 )
					{
						return $this->redirect()->toUrl($baseUrl . '/databox/post-horizontal/'.$catId);
					}
					unset($_SESSION["urlRedirection"]);
				}
			}
			
		return $view->setTemplate( "/databoxuser/databoxuser/redirect-catchoice.phtml" );
	}

    public function emailLoginAction()
    {
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		if( isset($_POST) )
		{
			$password = "";
			$password = md5($_POST["password"]);
			$userInfo=array();
			$userInfo["displayname"] = $_POST['displayname'];
			$userInfo["password"] = $password;
			$userInfo["email"] = $_POST['email'];
			$user_id = $this->getUserTable()->addUser( $userInfo );
			$userDetails=array();
			$userDetails["mobile"] = "";
			/*
			if(isset($_FILES)){
				if($_FILES['accountPublicImageFile']['name']!=""){
					$image = stripslashes($_FILES['accountPublicImageFile']['name']);
					$extension = getExtension($image);
					$extension = strtolower($extension);
					$uploadedfile=$_FILES['accountPublicImageFile']['tmp_name'];
					if($extension=="jpg" || $extension=="jpeg" ){
						$src = imagecreatefromjpeg($uploadedfile);
					}
					else if($extension=="png"){
						$src = imagecreatefrompng($uploadedfile);
					}
					else{
						$src = imagecreatefromgif($uploadedfile);
					}
					list($width,$height)=getimagesize($uploadedfile);
					
					$newwidth=225;
					$newheight=300;
					$tmp=imagecreatetruecolor($newwidth,$newheight);
					imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
					$filename='./public/images/project/montageImages/'.$_FILES['accountPublicImageFile']['name'];
					imagejpeg($tmp,$filename,100);
					imagedestroy($tmp);
					
					$newwidth1=175;
					$newheight1=160;
					$tmp1=imagecreatetruecolor($newwidth1,$newheight1);
					imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);
					$filename1='./public/images/project/montageImages/medium/'.$_FILES['accountPublicImageFile']['name'];
					imagejpeg($tmp1,$filename1,100);
					imagedestroy($tmp1);
					
					$newwidth2=44;
					$newheight2=44;
					$tmp2=imagecreatetruecolor($newwidth2,$newheight2);
					imagecopyresampled($tmp2,$src,0,0,0,0,$newwidth2,$newheight2,$width,$height);
					$filename2='./public/images/project/montageImages/small/'.$_FILES['accountPublicImageFile']['name'];
					imagejpeg($tmp2,$filename2,100);
					imagedestroy($tmp2);
					
					imagedestroy($src);
					
					$montageImage = $_FILES['accountPublicImageFile']['name'];
				}
			}*/
			$montageImage = $_POST['cropImage'];
			$userDetails["image"] = $montageImage;
			$user_details_id = $this->getUserDetailsTable()->addEmailLogin( $user_id,$userDetails );
		
		$data = $user_id;
		//$key = "&/ASD%g/..&FWSF2csvsq2we!%%";
		$randomNumber=rand(1000,10000);
		//$encryptedRand = my_number_encrypt($randomNumber, $key);
		//$encrypted = my_number_encrypt($data, $key);
		$encryptedRand = base64_encode($randomNumber);
		$encrypted = base64_encode($data);
		//SEND MAIL START
			global $regSubject;                                
			global $regMessage;
			$regMessage = str_replace("<FULLNAME>",$_POST['displayname'], $regMessage);
			$regMessage = str_replace("<REGLINK>",$baseUrl.'/databoxuser/user-verified/'.$encrypted, $regMessage);
			$to=$_POST['email'];
			if(sendMail($to,$regSubject,$regMessage)){
				$linkExpired = $this->getLoginLinkExpiredTable()->addLoginLinkExpired( $user_id );
			}
		}
		//END
		$_SESSION["newEmailId"]=$_POST['email'];
		$view = new ViewModel(
			array(
				'baseUrl' 	=> $baseUrl,
				'basePath' 	=> $basePath,
				'email' 	=> $_POST['email'],
				'userId'	=> $user_id
			));
			
		$routeUrl="login-step-two";
		return $this->redirect()->toUrl($routeUrl);
	}
	public function loginStepTwoAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$email = "";
		if( isset($_SESSION["newEmailId"]) )
		{
			$email = $_SESSION["newEmailId"];
		}
		$view = new ViewModel(
			array(
				'baseUrl' 	=> $baseUrl,
				'basePath' 	=> $basePath,
				'email' 	=> $email
			));
		return $view->setTemplate( "/databoxuser/databoxuser/login-step-two.phtml" );
	}
	public function userVerifiedAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$key = "&/ASD%g/..&FWSF2csvsq2we!%%";
		$params=$this->params()->fromRoute('id', 0);
		$paramss=explode("-",$params);
		$userEnId=$paramss[0];
		$decrypted = base64_decode($userEnId);
		if(isset($paramss[1])){
			$userRow = $this->getUserTable()->getUser( $decrypted );
			$user_session = new Container('usersinfo');
			$user_session->userId=$userRow->user_id;
			$user_session->email=$userRow->email;
			$user_session->displayName=$userRow->display_name;
			$user_session->montage_hash_name=$userRow->montage_hash_name;
			$user_session->montage_title=$userRow->montage_title;
			$user_session->montage_paragraph=$userRow->montage_paragraph;
			$user_session->montage_image=$userRow->montage_image;
			$user_session->montage_main_image=$userRow->montage_main_image;
			$user_session->hinting_state=$userRow->hinting_state;
			$user_session->disable_messageing=$userRow->disable_messageing;
			$userCollectedLinksCount= $this->getUserCollectionsTable()->getCollectedLinksCount($userRow->user_id);
			$collectionsCount=count($userCollectedLinksCount->toArray());
			$getBlockUserDetails= $this->getBlockUserTable()->getBlockedIds();
			$finalIds='';
			foreach($getBlockUserDetails as $key=>$blockUser){

				if($blockUser->block_by_uid==$userRow->user_id){
					$finalIds .='"'. $blockUser->blocked_to_uid.'"' . ',';
				}
				if($blockUser->blocked_to_uid==$userRow->user_id){
					$finalIds.='"'.$blockUser->block_by_uid.'"' . ',';
				}
			}
			$frnds= rtrim($finalIds,',');
			$userMessagesCount= count($this->getUserMessagesTable()->getUserMessages( $userRow->user_id,$frnds)->toArray());
			$user_session->totalcount=$publicprivatetotalcount;
			$user_session->userMessagesCount=$userMessagesCount;
			$user_session->collectionsCount=$collectionsCount;
			$userpointsTable = $this->getUserPointsTable();
			$userPointss = $userpointsTable->loggedUserPoints($userRow->user_id);
			
			$userPoints ='0';
			if(count($userPointss)>0){
				$userPoints = $userPointss->userPoints;
			}
			$user_session->rewardPoints=$userPoints;
			$view = new ViewModel(
			array(
				'baseUrl' 	=> $baseUrl,
				'basePath' 	=> $basePath
			));
			return $this->redirect()->toUrl($baseUrl . '/contentpage');
		}else{
			$status = $this->getLoginLinkExpiredTable()->checkLinkExists( $decrypted );
			if( ($status->count()!=0) && isset($status->buffer()->current()->status) && $status->current()->status==0 ){				
				$userInfo['userId']=$decrypted;
				$userInfo['status']=1;
				// Code for First Login With Taggerzz
				$activityTable = $this->getActivityPointsTable();
				$firstTimeUser = $activityTable->getActivityFresh('freshUser');
				if(count($firstTimeUser)>0){
					$activityId = $firstTimeUser->activity_id;
					$insertedId = $this->activityMethod($decrypted,$activityId);
					// Invitations
					if($insertedId>0){
						$userRowI = $this->getUserTable()->getUser( $decrypted );
						$uEmail = $userRowI->email;
						$inivitTable = $this->getInvitationsTable();
						$inivtedEmailCheck = $inivitTable->inivtedEmailCheck($uEmail);
						if(count($inivtedEmailCheck)>=0){
							$firstInvitations = $activityTable->getActivityFresh('Invitations');
							$activityIdd = $firstInvitations->activity_id;
							foreach($inivtedEmailCheck as $inivite){
								$user_id = $inivite->user_id;
								$inivit_id = $inivite->inivit_id;
								$updateStatusCheck = $inivitTable->updateStatus($inivit_id);
								$lastInsertedId = $this->activityMethod($user_id,$activityIdd);	
							}				
						}
					}
					// END
				}
				//End
				$statusupdate = $this->getLoginLinkExpiredTable()->updateLoginLinkExpired( $decrypted );
				$user_id = $this->getUserTable()->changeAccountStatus( $userInfo );
				$view = new ViewModel(
					array(
						'baseUrl' 			=> $baseUrl,
						'basePath' 			=> $basePath,
						'encryptId'			=>	$userEnId,
						'encryptedRand'		=>	$paramss[0]
					));
				return $view->setTemplate( "/databoxuser/databoxuser/login-step-three.phtml" );
			}else{
				$view = new ViewModel(
				array(
					'baseUrl' 	=> $baseUrl,
					'basePath' 	=> $basePath
				));
				return $view->setTemplate( "/databoxuser/databoxuser/expired-link.phtml" );
			}
		}
	}
	public function activityMethod($uid,$aid){
		$lastInsertedId = 0;
		$userpointsTable = $this->getUserPointsTable();
		$minutes_lu = 0;
		if(isset($_SESSION['usersinfo']->userId) && $_SESSION['usersinfo']->userId!=""){
			$user_id = $_SESSION['usersinfo']->userId;
			$getLastActivityLoggedU  = $userpointsTable->lastActivity($user_id);
			$time_diff_Lu = 0;
			if(count($getLastActivityLoggedU)>0){
				$time_diff_Lu = time() - strtotime($getLastActivityLoggedU->activity_dt);
			}
			$minutes_lu = floor($time_diff_Lu / 10);
		}
		$getLastActivityDataboxU = $userpointsTable->lastActivity($uid);
		$time_diff_Luu = 0;
		if(count($getLastActivityDataboxU)>0){
			$time_diff_Luu = time() - strtotime($getLastActivityDataboxU->activity_dt);
		}
		$minutes_luu = floor($time_diff_Luu / 10);
		if($minutes_lu!='0'){
			if(($minutes_lu>1) && ($minutes_luu>1)){
				$lastInsertedId = $userpointsTable->addUserPoints($uid,$aid);
				if(isset($_SESSION['usersinfo']->userId) && $_SESSION['usersinfo']->userId!=""){
					$userpointsTable = $this->getUserPointsTable();
					$userPointss = $userpointsTable->loggedUserPoints($_SESSION['usersinfo']->userId);
					$userPoints ='0';
					if(count($userPointss)>0){
						$userPoints = $userPointss->userPoints;
					}
					$_SESSION['usersinfo']->rewardPoints=$userPoints;
				}
			}
		}else{
			if($minutes_luu>1){
				$lastInsertedId = $userpointsTable->addUserPoints($uid,$aid);
			}
		}
		return $lastInsertedId;
	}
	public function recordActivityPointsAction(){
		$baseUrls 	= $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl 	= $baseUrlArr['baseUrl'];
		$basePath 	= $baseUrlArr['basePath'];
		$activityId ='';
		$activityTable = $this->getActivityPointsTable();
		if(isset($_POST['activityType']) && $_POST['activityType']!=''){
			$activity_name = $_POST['activityType'];
			$activityMode = $activityTable->getActivityFresh($activity_name);
			if(count($activityMode)>0){
				$activityId = $activityMode->activity_id;
			}
			//Likes
			if($_POST['activityType']=='Like Points'){
				if(isset($_SESSION['usersinfo']->userId) && $_SESSION['usersinfo']->userId!=''){
					$user_id = $_SESSION['usersinfo']->userId;
					$acitvityInserted = $this->activityMethod($user_id,$activityId);
					return $view = new JsonModel(array(
						'output' 	=> 1,
					));
				}else{
					return $view = new JsonModel(array(
						'output' 	=> 0,
					));
				}
			}
			if($_POST['activityType']=='trashDatabox'){
				if(isset($_SESSION['usersinfo']->userId) && $_SESSION['usersinfo']->userId!=''){
					$categoryId = $_POST['categoryId'];
					$categoryIdDetails = $this->getCategoryTable()->getInfo($categoryId);
					$dataBoxOwner=$categoryIdDetails->user_id;
					$acitvityInserted = $this->activityMethod($dataBoxOwner,$activityId);
					return $view = new JsonModel(array(
						'output' 	=> 1,
					));
				}else{
					return $view = new JsonModel(array(
						'output' 	=> 0,
					));
				}
			}
		}
		//Comments Points For Fresh Databox
		if(isset($_POST['comt']) && $_POST['comt']=='cmtt'){
			//$acitvityInserted = $this->activityMethod($_POST['databoxUser'],$activityId);
			return $view = new JsonModel(array(
				'output' 	=> 1,
			));
		}
		//Databox Responsed by other users
		if(isset($_POST['dataBoxOwner']) && $_POST['dataBoxOwner']!=""){
			if($_POST['dataBoxOwner']!=$_SESSION['usersinfo']->userId){
				$user_id = $_SESSION['usersinfo']->userId;
				$dataBoxOwner = $_POST['dataBoxOwner'];
				$acitvityInserted = $this->activityMethod($user_id,$activityId);
				if($acitvityInserted!="0"){
					$activity_name = 'Databox owner';
					$activityMode = $activityTable->getActivityFresh($activity_name);
					if(count($activityMode)>0){
						$activityId = $activityMode->activity_id;
					}
					$acitvityLasted = $this->activityMethod($dataBoxOwner,$activityId);
					if($acitvityLasted!='0'){
						return $view = new JsonModel(array(
							'output' 	=> 1,
						));
					}
				}else{
					return $view = new JsonModel(array(
						'output' 	=> 'Break Point',
					));
				}
			}else{
				return $view = new JsonModel(array(
					'output' 	=> 'Sorry',
				));
			}
		}else{
			return $view = new JsonModel(array(
				'output' 	=> 'Sorry',
			));
		}
	}
	public function iniviteFriendAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$inivitTable = $this->getInvitationsTable();
		$user_id = $_SESSION['usersinfo']->userId;
		$email_id = $_POST['emailId'];
		$comment = $_POST['comment'];
		global $iniviteFriendSubject;
		global $iniviteFriendMessage;
		$registeredEmail = $this->getUserTable()->existsEmailChecking( $email_id );
		if($registeredEmail=='0'){
			$alreadyInivited = $inivitTable->getInfo($email_id,$user_id);
			if($alreadyInivited =='0'){
				$insertId = $inivitTable->insertInivite($email_id,$comment,$user_id);
				if($insertId>0){
					$loggedEmail=$_SESSION['usersinfo']->email;
					$loggedUserName=ucfirst($_SESSION['usersinfo']->displayName);
					$iniviteFriendMessage = str_replace("<MESSAGE>",'Your friend '.$loggedUserName.' '.$loggedEmail.' inviting to Taggerzz', $iniviteFriendMessage);
					$iniviteFriendMessage = str_replace("<SITELINK>",$baseUrl.'/', $iniviteFriendMessage);
					$to=$email_id;
					if(sendMail($to,$iniviteFriendSubject,$iniviteFriendMessage))
					{
						return $view = new JsonModel(array(
							'output' 	=> 'nice',
						));
					}
				}
			}else{
				return $view = new JsonModel(array(
					'output' 	=> 'cool',
				));
			}	
		}else{
			return $view = new JsonModel(array(
				'output' 	=> 'boom',
			));
		}
	}
	public function dashboardAction(){
			$baseUrls = $this->getServiceLocator()->get('config');
			$baseUrlArr = $baseUrls['urls'];
			$baseUrl = $baseUrlArr['baseUrl'];
			$basePath = $baseUrlArr['basePath'];

			//new added
			$allDataboxes= $this->getUserCategoriesTable()->allDataboxes( $_SESSION['usersinfo']->userId)->toArray();
			$getBlockUserDetails= $this->getBlockUserTable()->getBlockedIds();
			$finalIds='';
			foreach($getBlockUserDetails as $key=>$blockUser){
				
				if($blockUser->block_by_uid==$_SESSION['usersinfo']->userId){
					$finalIds .='"'. $blockUser->blocked_to_uid.'"' . ',';
				}
				if($blockUser->blocked_to_uid==$_SESSION['usersinfo']->userId){
						$finalIds.='"'.$blockUser->block_by_uid.'"' . ',';
				}
			}
			$frnds= rtrim($finalIds,',');
			$getUserMessages= $this->getUserMessagesTable()->getUserMessages( $_SESSION['usersinfo']->userId,$frnds)->toArray();
			//End new added
			//echo "<pre>";print_r($getUserMessages);
			//newly added by sivareddy
			$userInfo = $this->getUserTable()->getUser( $_SESSION['usersinfo']->userId );

			
			return $view = new ViewModel(array(
				'baseUrl' 						=> $baseUrl,
				'basePath' 						=> $basePath,
				'allDataboxes' 				    => $allDataboxes,
				'getUserMessages' 				=> $getUserMessages,
				'userInfo' 				        => $userInfo
			));
			
	}
	public function couponsAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$userRechargeOrdersTable = $this->getUserRechargeOrdersTable();
		if(isset($_SESSION['usersinfo']->userId) && $_SESSION['usersinfo']->userId!=""){
			$userpointsTable = $this->getUserPointsTable();
			$userPointss = $userpointsTable->loggedUserPoints($_SESSION['usersinfo']->userId);
			$userRecharge = $userRechargeOrdersTable->userRechargedPoints($_SESSION['usersinfo']->userId);
			if(count($userPointss)>0){
				$userPoints = (($userPointss->userPoints)-$userRecharge->userPointsminus);
			}
			$_SESSION['usersinfo']->rewardPoints=$userPoints;
		}
		$checkRechargeCount = $userRechargeOrdersTable->checkRechargeCount($_SESSION['usersinfo']->userId);
		$rcechargeCountstatus='';

		if($checkRechargeCount <=4)
		{
					
			if(isset($_POST['mob']) && $_POST['mob']!=""){
				$UserID='8105259914';
				$Pass='words_9949_truth-649';
				$mob=$_POST['mob'];
				$OperatorCode=$_POST['operator_code'];
				$amt=$_POST['amt'];
				$agentid='12555';
				$rechargeUrl='http://erechargesoftware.com/API/APIService.aspx?userid='.$UserID.'&pass='.$Pass.'&mob='.$mob.'&opt='.$OperatorCode.'&amt='.$amt.'&agentid='.$agentid.'&fmt=Json';
				$rechargeInit = curl_init();
				curl_setopt($rechargeInit, CURLOPT_URL, $rechargeUrl);
				curl_setopt($rechargeInit, CURLOPT_USERAGENT, 'SugarConnector/1.4');
				curl_setopt($rechargeInit, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data)
				));
				curl_setopt($rechargeInit, CURLOPT_VERBOSE, 1);
				curl_setopt($rechargeInit, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($rechargeInit, CURLOPT_CUSTOMREQUEST, "GET"); 
				curl_setopt($rechargeInit, CURLOPT_SSL_VERIFYPEER, 0);
				$rechargeresult = curl_exec($rechargeInit);
				$rechargeapierr = curl_errno($rechargeInit);
				$rechargeerrmsg = curl_error($rechargeInit);
				curl_close($rechargeInit);
				$rechargeArr = json_decode($rechargeresult);

					if(isset($rechargeArr->STATUS) && $rechargeArr->STATUS!=""){
						
						$recharge_mobile=$rechargeArr->MOBILE;
						$amt=$rechargeArr->AMOUNT;
						$recharge_rp_id=$rechargeArr->RPID;
						$recharge_agent_id=$rechargeArr->AGENTID;
						$recharge_op_id=$rechargeArr->OPID;
						$recharge_msg=$rechargeArr->MSG;
						$recharge_usage_points=($amt*0.10*100);
						$recharge_status=$rechargeArr->STATUS;
						
							$userRechargeAdd = $userRechargeOrdersTable->addRechargeDetails($recharge_mobile,$amt,$recharge_rp_id,$recharge_agent_id,$recharge_op_id,$recharge_msg,$recharge_usage_points,$recharge_status);
						return $this->redirect()->toUrl($baseUrl . '/databoxuser/recharge-status?status='.$rechargeArr->STATUS);
					}	

			}
		}else{
			$rcechargeCountstatus='5 times for day only.';
			return $this->redirect()->toUrl($baseUrl . '/databoxuser/recharge-status?status='.$rcechargeCountstatus);

		}	
		return $view = new ViewModel(array(
			'baseUrl' 						=> $baseUrl,
			'basePath' 						=> $basePath,
			'points' 						=> $userPoints
		));
	}
	public function rechargeStatusAction(){
		$baseUrls   = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl    = $baseUrlArr['baseUrl'];
		$basePath   = $baseUrlArr['basePath'];
		return $view = new ViewModel(array(
			'baseUrl' 						=> $baseUrl,
			'basePath' 						=> $basePath
		));
	}
	public function accountsAction(){
		if(isset($_POST['display_name'])){
			$user_session = new Container('usersinfo');
			$user_session->displayName=$_POST['display_name'];
			$user_session->disable_messageing=$_POST['disable_messaging'];

			$userRow = $this->getUserTable()->updateAccount( $_POST );
			
			if( trim($_POST['password']) != "" )
			{
				global $pwdChangedSubject;                                
				global $pwdChangedMessage;
				$pwdChangedMessage = str_replace("<FULLNAME>",$_SESSION['usersinfo']->displayName, $pwdChangedMessage);
				$pwdChangedMessage = str_replace("<MESSAGE>",'Your Taggerzz Login Password Changed Successfully', $pwdChangedMessage);
				$to=$_SESSION['usersinfo']->email;
				if( sendMail($to,$pwdChangedSubject,$pwdChangedMessage) )
				{
					return $view = new JsonModel(
					array(
						'output' 	=> 2,
					));
				}
			}

			return $view = new JsonModel(
			array(
				'output' 	=> 1,
			));
		}else{
			$baseUrls = $this->getServiceLocator()->get('config');
			$baseUrlArr = $baseUrls['urls'];
			$baseUrl = $baseUrlArr['baseUrl'];
			$basePath = $baseUrlArr['basePath'];
			$userRow = $this->getUserTable()->getUser( $_SESSION['usersinfo']->userId );
			$getPublicDataboxCount = $this->getUserCategoriesTable()->getPublicDataboxCount( $_SESSION['usersinfo']->userId);
			$getPrivateDataboxCount = $this->getUserCategoriesTable()->getPrivateDataboxCount( $_SESSION['usersinfo']->userId);
			$getHiglightDataboxCount = $this->getUserCategoriesTable()->getHighlightDataboxCount( $_SESSION['usersinfo']->userId);
			$getTotalLinks = $this->getUserCategoriesTable()->getTotalLinks( $_SESSION['usersinfo']->userId);
			$votesCountRow = $this->getRelevanceWorthVoteTable()->getVotesUpDownCount();
			$keywordsRow = $this->getUserCategoriesTable()->getAllKeywords();
			$totalKeywords = 0;
			if( $keywordsRow->count() )
			{
				$keywordsArray = explode( ",",$keywordsRow->current()->allUserMetaTags );
				for( $keywordId = 0; $keywordId < count( $keywordsArray ); $keywordId++ )
				{
					if( $keywordsArray[$keywordId] != "" )
					{
						$totalKeywords++;
					}
				}
			}
			return $view = new ViewModel(array(
				'baseUrl' 						=> $baseUrl,
				'basePath' 						=> $basePath,
				'userDetails' 					=> $userRow,
				'getPublicDataboxCount' 		=> $getPublicDataboxCount->count(),
				'getHiglightDataboxCount' 		=> $getHiglightDataboxCount->count(),
				'publicDataboxes' 		        => $getPublicDataboxCount,
				'privateDataboxes' 		        => $getPrivateDataboxCount,
				'getPrivateDataboxCount' 		=> $getPrivateDataboxCount->count(),
				'getTotalLinks' 				=> $getTotalLinks->count(),
				'votesCountRow' 				=> $votesCountRow,
				'totalKeywords' 				=> $totalKeywords
			));
		}
	}
	public function changeStatusAccountAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		if(isset($_POST['status'])){
			$userRow = $this->getUserTable()->changeAccountStatus( $_POST );
			//$deleteDataboxe = $this->getUserCategoriesTable()->deleteDataboxeUser( $_SESSION['usersinfo']->userId,$_POST['status'] );
			global $deactiveSubject;                                
			global $deactiveMessage;
			$deactiveMessage = str_replace("<FULLNAME>",$_SESSION['usersinfo']->displayName, $deactiveMessage);
			$deactiveMessage = str_replace("<REGLINK>",$baseUrl, $deactiveMessage);
			$to=$_SESSION['usersinfo']->email;
			sendMail($to,$deactiveSubject,$deactiveMessage);
			return $view = new JsonModel(
			array(
				'output' 	=> 1,
			));
		}
	}
	public function accountVotesCountAction()
	{
		if( isset($_POST['status']) )
		{
			$userRow = $this->getRelevanceWorthVoteTable()->changeAccountStatus( $_POST );
			return $view = new JsonModel(
			array(
				'output' 	=> 1,
			));
		}
	}

	private function getUniqueCode($length = "")
	{
		$code = md5(uniqid(rand(), true));
		if ($length != "")
		return substr($code, 0, $length);
		else
		return $code;
	}

	public function forgotPasswordAction()
	{
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];

		if( isset($_POST['email']) && $_POST['email']!="" )
		{
			$email = $_POST['email'];			

			$token = "";
			$regEmailRs = $this->getUserTable()->checkRegEmailExists( $email );
			if( $regEmailRs->count() != 0 )
			{
				$token = $this->getUniqueCode('10');
				$regEmailRsBfr = $regEmailRs->buffer();
				$userId = $regEmailRsBfr->buffer()->current()->user_id;
				$regEmailRs = $this->getForgotPasswordTable()->addFpNewRow( $userId,$email,$token );

				global $fpPwdSubject;                                
				global $fpPwdMessage;
				$fpPwdMessage = str_replace("<FULLNAME>",$regEmailRsBfr->buffer()->current()->display_name, $fpPwdMessage);
				$fpPwdMessage = str_replace("<MESSAGE>",'Please use below link to reset your password.', $fpPwdMessage);
				$fpPwdMessage = str_replace("<PASSWORDLINK>",$baseUrl."/reset-password?token=".$token, $fpPwdMessage);
				$to=$email;
				if( sendMail($to,$fpPwdSubject,$fpPwdMessage) )
				{
					return $view = new JsonModel(
					array(
						'output' 	=> 1,
					));
				}
				else
				{
					return $view = new JsonModel(
					array(
						'output' 	=> 2,
					));
				}
			}

			return $view = new JsonModel(
			array(
				'output' 	=> 0,
			));
		}

		return $view = new ViewModel(
		array(
			'baseUrl' 	=> $baseUrl,
			'basePath' 	=> $basePath
		));
	}

	public function resetPasswordAction()
	{
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		
		$tokenExists = 0;
		if( isset($_GET['token']) )
		{
			$tokenRs = $this->getForgotPasswordTable()->checkFpTokenExists( $_GET['token'] );
			if( $tokenRs->count() != 0 )
			{
				$tokenExists = 1;
			}
		}

		return $view = new ViewModel(
		array(
			'baseUrl' 		=> $baseUrl,
			'basePath' 		=> $basePath,
			'tokenExists'	=> $tokenExists
		));
	}

	public function resetUserPasswordAction()
	{
		if( isset($_POST['token']) && isset($_POST['newPassword']) )
		{
			$tokenRs = $this->getForgotPasswordTable()->checkFpTokenExists( $_POST['token'] );
			if( $tokenRs->count() != 0 )
			{
				$userId = $tokenRs->buffer()->current()->user_id;
				$resetPasswordStatus = $this->getUserTable()->resetUserPassword( $userId,$_POST['newPassword'] );
				if( $resetPasswordStatus >=0 )
				{
					$deleteTokenStatus = $this->getForgotPasswordTable()->deleteToken( $userId );
					$userRow = $this->getUserTable()->getUser( $userId );
					$user_session = new Container('usersinfo');
					$user_session->userId=$userRow->user_id;
					$user_session->email=$userRow->email;
					$user_session->displayName=$userRow->display_name;
					$user_session->montage_hash_name=$userRow->montage_hash_name;
					$user_session->montage_title=$userRow->montage_title;
					$user_session->montage_paragraph=$userRow->montage_paragraph;
					$user_session->montage_image=$userRow->montage_image;
					$user_session->montage_main_image=$userRow->montage_main_image;
					$user_session->hinting_state=$userRow->hinting_state;
					$user_session->disable_messageing=$userRow->disable_messageing;
					$userCollectedLinksCount= $this->getUserCollectionsTable()->getCollectedLinksCount($userRow->user_id);
					$collectionsCount=count($userCollectedLinksCount->toArray());
					$getBlockUserDetails= $this->getBlockUserTable()->getBlockedIds();
					$finalIds='';
					foreach($getBlockUserDetails as $key=>$blockUser){

						if($blockUser->block_by_uid==$userRow->user_id){
							$finalIds .='"'. $blockUser->blocked_to_uid.'"' . ',';
						}
						if($blockUser->blocked_to_uid==$userRow->user_id){
							$finalIds.='"'.$blockUser->block_by_uid.'"' . ',';
						}
					}
					$frnds= rtrim($finalIds,',');
					$userMessagesCount= count($this->getUserMessagesTable()->getUserMessages( $userRow->user_id,$frnds)->toArray());
					$user_session->totalcount=$publicprivatetotalcount;
					$user_session->userMessagesCount=$userMessagesCount;
					$user_session->collectionsCount=$collectionsCount;
					$userpointsTable = $this->getUserPointsTable();
					$userPointss = $userpointsTable->loggedUserPoints($userRow->user_id);
					
					$userPoints ='0';
					if(count($userPointss)>0){
						$userPoints = $userPointss->userPoints;
					}
					$user_session->rewardPoints=$userPoints;
					return $view = new JsonModel(
					array(
						'output' 	=> 1,
					));
					//return $this->redirect()->toUrl($baseUrl . '/contentpage');
				}
				else
				{
					return $view = new JsonModel(
					array(
						'output' 	=> 2,
					));
				}
			}
		}
		return $view = new JsonModel(
		array(
			'output' 	=> 0,
		));
	}
	
    public function updateHintingAction()
    {
		$userId = "";
		$hintingNewVal = "";
		if( $_POST && isset($_SESSION['usersinfo']) && isset($_SESSION['usersinfo']->hinting_state) )
		{
			if( isset($_POST["userId"]) )
			{
				$userId = $_POST["userId"];
			}
			if( isset($_POST["hintingNewVal"]) )
			{
				$hintingNewVal = $_POST["hintingNewVal"];
			}
			if( ($userId != "") && ($hintingNewVal != "") )
			{
				$hintingReturn = $this->getUserDetailsTable()->updateHintingState( $userId,$hintingNewVal );
				$_SESSION['usersinfo']->hinting_state = $hintingNewVal;
				echo "1";exit;
			}
		}
		echo "0";exit;
	}

	public function getUserTable()
    {
        if (!$this->userTable) {				
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Databoxuser\Model\UserFactory');			
        }
        return $this->userTable;
    }

	public function getUserDetailsTable()
    {
        if (!$this->userDetailsTable) {				
            $sm = $this->getServiceLocator();
            $this->userDetailsTable = $sm->get('Databoxuser\Model\UserDetailsFactory');			
        }
        return $this->userDetailsTable;
    }
	public function getUserCategoriesTable()
    {
        if (!$this->userCategoriesTable) {				
            $sm = $this->getServiceLocator();
            $this->userCategoriesTable = $sm->get('Databox\Model\UserCategoriesFactory');			
        }
        return $this->userCategoriesTable;
    }
	
	public function getRelevanceWorthVoteTable()
    {
        if (!$this->relevanceWorthVoteTable) {				
            $sm = $this->getServiceLocator();
            $this->relevanceWorthVoteTable = $sm->get('Databox\Model\RelevanceWorthVoteFactory');			
        }
        return $this->relevanceWorthVoteTable;
    }
	public function getLoginLinkExpiredTable()
    {
        if (!$this->loginLinkExpiredTable) {				
            $sm = $this->getServiceLocator();
            $this->loginLinkExpiredTable = $sm->get('Databoxuser\Model\LoginLinkExpiredFactory');			
        }
        return $this->loginLinkExpiredTable;
    }
	public function getForgotPasswordTable()
    {
        if (!$this->forgotPasswordTable) {				
            $sm = $this->getServiceLocator();
            $this->forgotPasswordTable = $sm->get('Databoxuser\Model\ForgotPasswordFactory');			
        }
        return $this->forgotPasswordTable;
    }
	public function getUserMessagesTable()
    {
        if (!$this->userMessagesTable) {				
            $sm = $this->getServiceLocator();
            $this->userMessagesTable = $sm->get('Databox\Model\UserMessagesFactory');			
        }
        return $this->userMessagesTable;
    }
	public function getBlockUserTable()
    {
        if (!$this->blockUserTable) {				
            $sm = $this->getServiceLocator();
            $this->blockUserTable = $sm->get('Databox\Model\BlockUserFactory');			
        }
        return $this->blockUserTable;
    }
	public function allUsersNamesAjaxAction()
    {
			$userNames="";
			$userNameIds="";
			$count="";
			$getBlockUserDetails= $this->getBlockUserTable()->getBlockedIds();
			$finalIds='';
			foreach($getBlockUserDetails as $key=>$blockUser){
				
				if($blockUser->block_by_uid==$_SESSION['usersinfo']->userId){
					$finalIds .='"'. $blockUser->blocked_to_uid.'"' . ',';
				}
				if($blockUser->blocked_to_uid==$_SESSION['usersinfo']->userId){
						$finalIds.='"'.$blockUser->block_by_uid.'"' . ',';
				}
			}
			$frnds= rtrim($finalIds,',');
			$allUsersNamesSearch = $this->getUserTable()->getSearchUsers($_POST['value'],$frnds);
			if($allUsersNamesSearch->count()!=0){
			foreach($allUsersNamesSearch as $key=>$search){
				$userNames[$key]=$search->display_name;
				$userNameIds[$key]=$key;
				$count=$key;				
			}		
			$combined = array();
			foreach($userNames as $index => $refNumber) {			
				$combined[] = array(
					'ref'  => $refNumber,
					'part' => $userNameIds[$index]
				);
			}
			return $view = new JsonModel(
			array(
				'output'			=>	1,
				'usersAll'	=>	$combined,
				'countt'				=>	$count,
			));
		}else{
			return $view = new JsonModel(
			array(
				'output'			=>	0,
			));
		}
    }
	public function changeMessageStatusAction()
    {
			$changeMessageStatus = $this->getUserMessagesTable()->changeMsgStatus($_POST['msgId']);
			return $view = new JsonModel(
			array(
				'output'			=>	1,
			));
			
    }
	public function showMessageContentAction()
    {
			//echo "<pre>";print_r($_POST);exit;
			$getMessageDetails = $this->getUserMessagesTable()->getMessageDetails($_POST['msgId'])->toArray();
			$msg=$getMessageDetails[0]['message'];
			$userName=$getMessageDetails[0]['display_name'];
			$sendedId=$getMessageDetails[0]['msg_sender_id'];
			$receiverId=$getMessageDetails[0]['msg_receiver_id'];
			$date=$getMessageDetails[0]['msg_sended_date'];
				return $view = new JsonModel(
				array(
					'output'			=>	1,
					'msg'			    =>$msg,
					'userName'			=>$userName,
					'sendedId'			=>$sendedId,
					'receiverId'		=>$receiverId,
					'date'			    =>$date
				));
			
			
			
    }
	public function insertMessageAction()
    {
		$insertMsg = $this->getUserMessagesTable()->addMsg($_POST);
			return $view = new JsonModel(
				array(
					'output'			=>	1,
				));
    }
	public function getEmailDetailsAction()
    {
		$getEmailDetails = $this->getUserTable()->checkUserNameExists($_POST['email'])->toArray();
		if(count($getEmailDetails)!=0){
			$user_id=$getEmailDetails[0]['user_id'];
			return $view = new JsonModel(
				array(
					'output'			=>	1,
					'user_id'   => $user_id,
				));
		}else{
				return $view = new JsonModel(
				array(
					'output'			=> 0,
				));
		}		
    }
	public function insertBlockedUserAction()
    {
		$insertMsg = $this->getBlockUserTable()->insertBlockedUser($_POST);
		return $view = new JsonModel(
			array(
				'output'			=>	1,
			));
    }
	public function checkBlockedUserAction()
    {
		$getBlockedUserDetails = $this->getBlockUserTable()->checkBlockedToUser($_POST['blocked_to_uid'])->toArray();
		if(count($getBlockedUserDetails)!=0){
			$user_id=$_SESSION['usersinfo']->userId;
			$getBlockedToDetails= $this->getBlockUserTable()->BlockedUser($_POST['blocked_to_uid'],$user_id)->toArray();
			if(count($getBlockedToDetails)!=0){
					return $view = new JsonModel(
					array(
						'output'			=>	1,
					));
			}else{
				return $view = new JsonModel(
				array(
					'output'			=> 1,
				));
			}
		}else{
			$user_id=$_SESSION['usersinfo']->userId;
			$getBlockedToDetails= $this->getBlockUserTable()->BlockedUser($_POST['blocked_to_uid'],$user_id)->toArray();
			if(count($getBlockedToDetails)!=0){
				return $view = new JsonModel(
					array(
						'output'			=>	1,
					));
			}else{
				return $view = new JsonModel(
				array(
					'output'			=> 0,
				));
			}
		}	
		
    }
	public function getUserCollectionsTable()
    {
        if (!$this->userCollectionsTable) {				
            $sm = $this->getServiceLocator();
            $this->userCollectionsTable = $sm->get('Databox\Model\UserCollectionsFactory');			
        }
        return $this->userCollectionsTable;
    }
	// Added By Dileep
	public function getActivityPointsTable()
    {
        if (!$this->activitypointsTable) {				
            $sm = $this->getServiceLocator();
            $this->activitypointsTable = $sm->get('Databoxuser\Model\ActivityPointsFactory');			
        }
        return $this->activitypointsTable;
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
	public function getInvitationsTable()
    {
        if (!$this->invitationsTable) {				
            $sm = $this->getServiceLocator();
            $this->invitationsTable = $sm->get('Databoxuser\Model\InvitationsFactory');			
        }
        return $this->invitationsTable;
    }
	public function getCategoryTable()
    {
        if (!$this->categoryTable) {				
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('Databox\Model\CategoryFactory');			
        }
        return $this->categoryTable;
    }
	public function getCategoryLinksTable()
    {
        if (!$this->categoryLinksTable) {				
            $sm = $this->getServiceLocator();
            $this->categoryLinksTable = $sm->get('Databox\Model\CategoryLinksFactory');			
        }
        return $this->categoryLinksTable;
    }
	public function cronFreshLinksAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$category_ids = $this->getCategoryTable()->getCategoryIds();
		$cnt = 0;
		if(count($category_ids)>0){
			foreach($category_ids as $catid){
				echo $cat_id = $catid->category_id;
				$get_category_links = $this->getCategoryLinksTable()->getCategoryLinks( $cat_id );
				if(count($get_category_links)>0){
					foreach($get_category_links as $cat_links){
						$checkLinks = $this->getCategoryLinksTable()->checkLinks($cat_links->link);
						if($checkLinks==1){
							$cnt++;
						}
					}
					if($cnt>=50){
						$userId = $catid->user_id;
						$activity_name='FreshLinks';
						$activityTable = $this->getActivityPointsTable();
						$activityMode = $activityTable->getActivityFresh($activity_name);
						if(count($activityMode)>0){
							$activityId = $activityMode->activity_id;
							$acitvityLasted = $this->activityMethod($userId,$activityId);
							if($acitvityLasted!=0){
								$update_cron_status = $this->getCategoryTable()->updateCronStatus( $cat_id );
							}
							$userpointsTable = $this->getUserPointsTable();
							$userPointss = $userpointsTable->loggedUserPoints($userId);
							$userPoints ='0';
							if(count($userPointss)>0){
								$userPoints = $userPointss->userPoints;
							}
							if(isset($_SESSION['usersinfo']->userId) && $_SESSION['usersinfo']->userId==$userId){
								$_SESSION['usersinfo']->rewardPoints=$userPoints;
							}
						}
						echo "CRON Successfully....";
					}else{
						echo "NO DATA FOUND";
					}
				}else{
					echo "NO DATA FOUND";
				}
			}exit;
		}else{
			echo "NO DATA FOUND";exit;
		}
	}
	function restClient($url, $method, $data ) {
		
		$cInit = curl_init();
		curl_setopt($cInit, CURLOPT_URL, $url);
		curl_setopt($cInit, CURLOPT_USERAGENT, 'SugarConnector/1.4');
		if($data != ""){
			curl_setopt($cInit, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
			'Content-Length: ' . strlen($data)
			));
		}else{
			curl_setopt($cInit, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
			//'Content-Length: ' . strlen($data)
			));
		}
		curl_setopt($cInit, CURLOPT_VERBOSE, 1);
		curl_setopt($cInit, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cInit, CURLOPT_CUSTOMREQUEST, $method);
		if($data != ""){
			curl_setopt($cInit, CURLOPT_POSTFIELDS,$data);
		}		
		curl_setopt($cInit, CURLOPT_SSL_VERIFYPEER, 0);
		$cLoginresult = curl_exec($cInit); 
		curl_close($cInit);
		return $cLoginresult;
	}
}

		

	
