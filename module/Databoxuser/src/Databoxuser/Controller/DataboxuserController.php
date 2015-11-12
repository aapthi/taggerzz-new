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
					if( isset($_POST["emailId"]) )
					{
						$emailId = $_POST["emailId"];
					}
					if( isset($_POST["userPassword"]) )
					{
						$userPassword = $_POST["userPassword"];
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
						$getPublicDataboxCount = $this->getUserCategoriesTable()->getPublicDataboxCount( $_SESSION['usersinfo']->userId);
						$getPrivateDataboxCount = $this->getUserCategoriesTable()->getPrivateDataboxCount( $_SESSION['usersinfo']->userId);
						$publicprivatetotalcount=count($getPublicDataboxCount->toArray()) + count($getPrivateDataboxCount->toArray());
						$userCollectedLinksCount= $this->getUserCollectionsTable()->getCollectedLinksCount($_SESSION['usersinfo']->userId);
						$collectionsCount=count($userCollectedLinksCount->toArray());
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
						$userMessagesCount= count($this->getUserMessagesTable()->getUserMessages( $_SESSION['usersinfo']->userId,$frnds)->toArray());
						$user_session->totalcount=$publicprivatetotalcount;
						$user_session->userMessagesCount=$userMessagesCount;
						$user_session->collectionsCount=$collectionsCount;
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
			}
			// End
			$userRoww = $this->getUserTable()->changeAccountStatus( $userInfo );
			$userRow = $this->getUserTable()->getUser( $user_id );
			$getPublicDataboxCount = $this->getUserCategoriesTable()->getPublicDataboxCount( $user_id);
			$getPrivateDataboxCount = $this->getUserCategoriesTable()->getPrivateDataboxCount( $user_id);
			$publicprivatetotalcount=count($getPublicDataboxCount->toArray()) + count($getPrivateDataboxCount->toArray());
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
			$user_session->totalcount=$publicprivatetotalcount;
			$user_session->userMessagesCount=$userMessagesCount;
			$user_session->collectionsCount=$collectionsCount;
			$dbuser_template = "/databoxuser/databoxuser/redirect-userlogin.phtml";
		}
		else
		{
			$userRow = $this->getUserTable()->getUser( $user_id );
			$getPublicDataboxCount = $this->getUserCategoriesTable()->getPublicDataboxCount( $user_id);
			$getPrivateDataboxCount = $this->getUserCategoriesTable()->getPrivateDataboxCount( $user_id);
			$publicprivatetotalcount=count($getPublicDataboxCount->toArray()) + count($getPrivateDataboxCount->toArray());
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
			$user_session->totalcount=$publicprivatetotalcount;
			$user_session->userMessagesCount=$userMessagesCount;
			$user_session->collectionsCount=$collectionsCount;

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
		//$decrypted = my_number_decrypt($userEnId, $key);
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
			$view = new ViewModel(
			array(
				'baseUrl' 	=> $baseUrl,
				'basePath' 	=> $basePath
			));
			//return $view->setTemplate( "/databoxuser/databoxuser/redirect-catchoice.phtml" );
			return $this->redirect()->toUrl($baseUrl . '/contentpage');
		}else{
			$status = $this->getLoginLinkExpiredTable()->checkLinkExists( $decrypted );
			if( ($status->count()!=0) && isset($status->buffer()->current()->status) && $status->current()->status==0 ){
				$statusupdate = $this->getLoginLinkExpiredTable()->updateLoginLinkExpired( $decrypted );
				$userInfo['userId']=$decrypted;
				$userInfo['status']=1;
				// Code for First Login With Taggerzz
				$activityTable = $this->getActivityPointsTable();
				$firstTimeUser = $activityTable->getActivityFresh('freshUser');
				if(count($firstTimeUser)>0){
					$activityId = $firstTimeUser->activity_id;
					$insertedId = $this->activityMethod($decrypted,$activityId);
				}
				//End
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
		$userpointsTable = $this->getUserPointsTable();
		$lastInsertedId = $userpointsTable->addUserPoints($uid,$aid);
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
		}
		//Databox Responsed by other users
		if(isset($_POST['dataBoxOwner']) && $_POST['dataBoxOwner']!=""){
			if($_POST['dataBoxOwner']!=$_SESSION['usersinfo']->userId){
				$dataBoxOwner = $_POST['dataBoxOwner'];
				$acitvityInserted = $this->activityMethod($dataBoxOwner,$activityId);
				if($acitvityInserted!=""){
					$user_id = $_SESSION['usersinfo']->userId;
					$activity_name = 'Databox owner';
					$activityMode = $activityTable->getActivityFresh($activity_name);
					if(count($activityMode)>0){
						$activityId = $activityMode->activity_id;
					}
					$acitvityLasted = $this->activityMethod($user_id,$activityId);
					if($acitvityLasted!=''){
						return $view = new JsonModel(array(
							'output' 	=> 1,
						));
					}
				}
			}
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
		$alreadyInivited = $inivitTable->getInfo($email_id,$user_id);
		if($alreadyInivited =='0'){
			$insertId = $inivitTable->insertInivite($email_id,$comment,$user_id);
			return $view = new JsonModel(array(
				'output' 	=> 'nice',
			));
		}else{
			return $view = new JsonModel(array(
				'output' 	=> 'cool',
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
			
			return $view = new ViewModel(array(
				'baseUrl' 						=> $baseUrl,
				'basePath' 						=> $basePath,
				'allDataboxes' 				    => $allDataboxes,
				'getUserMessages' 				=> $getUserMessages
			));
			
	}

	public function accountsAction(){
		if(isset($_POST['display_name'])){
			$user_session = new Container('usersinfo');
			$user_session->displayName=$_POST['display_name'];
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
			$getTotalLinks = $this->getUserCategoriesTable()->getTotalLinks( $_SESSION['usersinfo']->userId);
			$votesCountRow = $this->getRelevanceWorthVoteTable()->getVotesUpDownCount();
			$keywordsRow = $this->getUserCategoriesTable()->getAllKeywords();
			//echo '<pre>'; print_r($keywordsRow); exit;
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
	public function getInvitationsTable()
    {
        if (!$this->invitationsTable) {				
            $sm = $this->getServiceLocator();
            $this->invitationsTable = $sm->get('Databoxuser\Model\InvitationsFactory');			
        }
        return $this->invitationsTable;
    }
	
}

		

	
