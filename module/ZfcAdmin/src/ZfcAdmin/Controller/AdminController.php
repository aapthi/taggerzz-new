<?php
namespace ZfcAdmin\Controller;
use Zend\Form\Form;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Parameters;
use Zend\Authentication\AuthenticationService;
use SanAuthWithDbSaveHandler\Storage\IdentityManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\Cache\StorageFactory;
use ScnSocialAuth\Mapper\UserProviderInterface;
class AdminController extends AbstractActionController
{
	protected $userTable;
	protected $userCategoriesTable;
	protected $adminReportsTable;
	protected $databoxCommentsTable;
	
	
	public function indexAction(){
		if(isset($_SESSION['admininfo']->userId) && $_SESSION['admininfo']->userId!=""){
			return $this->redirect()->toUrl('admin/administrator-settings');
		}
	}
	public function adminLoginAction(){
		$userInfo["email"] = $_POST['emailId'];
		$userInfo["password"] = md5($_POST['userPassword']);
		$userRow = $this->getUserTable()->checkAdminEmailExists( $userInfo )->current();
		$user_session = new Container('admininfo');
		$user_session->userId=$userRow->user_id;
		$user_session->email=$userRow->email;
		$user_session->displayName=$userRow->display_name;
		$user_session->montage_hash_name=$userRow->montage_hash_name;
		$user_session->montage_title=$userRow->montage_title;
		$user_session->montage_paragraph=$userRow->montage_paragraph;
		$user_session->montage_image=$userRow->montage_image;
		return $this->redirect()->toUrl('administrator-settings');
	}
	public function adminEmailExistsAction(){
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
				$userRow = $this->getUserTable()->checkAdminEmailExists( $userInfo )->toArray();
				if( count($userRow)!=0 ){
					echo "1";exit;
				}else{
					echo "0";exit;
				}
			}
		}
	}
	public function administratorSettingsAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];

		$getAllUsers = $this->getUserTable()->getAllUsers();
		$getTotalAllUsers = $this->getUserTable()->getTotalAllUsers()->toArray();
		$user_id=$getAllUsers->buffer()->current()->user_id;

		$getDataboxAndHighlights = $this->getUserCategoriesTable()->getDataboxAndHighlights($user_id)->toArray();
		$adminUserReports = $this->getAdminReportsTable()->getAdminUserReports()->toArray();
		$contactContents = $this->getAdminReportsTable()->getContactContents()->toArray();
		
		$getAllCommentsDataboxes = $this->getUserCategoriesTable()->getCommentsAllDataboxes();
		return $view = new ViewModel(
		array(
			'baseUrl' 	   				=> $baseUrl,
			'basePath' 					=> $basePath,
			'getDataboxAndHighlights' 	=> $getDataboxAndHighlights,
			'montage_hash_name' 		=> $getAllUsers->buffer()->current()->montage_hash_name,
			'getAllUsers' 				=> $getAllUsers->buffer(),
			'getTotalAllUsers' 			=> count($getTotalAllUsers),
			'user_id' 					=> $user_id,
			'adminUserReports' 			=> $adminUserReports,
			'contactContents' 			=> $contactContents,
			'getAllCommentsDataboxes' 	=> $getAllCommentsDataboxes
		));
	}
	public function ajaxDataAction(){
		$user_id=$_POST['user_id'];
		$getDataboxAndHighlights = $this->getUserCategoriesTable()->getDataboxAndHighlights($user_id);
		$getAllUsers = $this->getUserTable()->getMontageName($user_id);
		$dataBoxes="";
		$highlightsBoxes="";
		$countD=0;
		$countH=0;
		if($getDataboxAndHighlights->count()){
			foreach($getDataboxAndHighlights as $data){
				if($data->category_highlight==1){
					$dataBoxes.='<p id="p_databox_' . $data->category_id . '" onClick="Javascript:setActiveDbValues( ' . $data->category_id . ',' . $data->category_type . ',' . $data->databoxes_prior_order . ',' . $data->setting_id . ',' . $data->status . ',1 );">'.$data->user_hashname .'</p>';
					$countD++;
				}else{
					$highlightsBoxes.='<p id="p_highlight_' . $data->category_id . '" onClick="Javascript:setActiveHighValues( ' . $data->category_id . ',' . $data->highlights_prior_order . ',' . $data->setting_id . ',' . $data->status . ',2 );">'.$data->user_hashname .'</p>';
					$countH++;
				}
			}
		}
		if($countD==0){
			$dataBoxes.='<p>Not Found!</p>';
		}
		if($countH==0){
			$highlightsBoxes.='<p>Not Found!</p>';
		}
		if($getAllUsers->buffer()->current()->montage_hash_name!=""){
			$montage_hash_name='<p id="montageHashName" onClick="activateHashName();">'.$getAllUsers->buffer()->current()->montage_hash_name.'</p>';
		}else{
			$montage_hash_name='<p>Not Found!</p>';
		}
		$reports='<p>THREATENED LINKS</p><p>LINK FARMS</p><p>REPORTED</p>';
		$view = new JsonModel(
		array(
			'dataBoxes' 				=> $dataBoxes,
			'highlightsBoxes' 			=> $highlightsBoxes,
			'montage_hash_name' 		=> $montage_hash_name,
			'reports' 					=> $reports,
		));
		return $view;
	}
	public function appendAjaxUsersAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$offset=$_POST['offset'];
		$limit=$_POST['limit'];
		$getAllUsers = $this->getUserTable()->getAllUsers($offset,$limit);
		$view = new ViewModel(
		array(
			'baseUrl' 	   				=> $baseUrl,
			'basePath' 					=> $basePath,
			'getAllUsers' 				=> $getAllUsers->toArray(),
			'value' 					=> $offset,
		));
		return $view->setTerminal(true);
	}
	public function searchAjaxUsersAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$search=$_POST['search'];
		if($search=='0'){
			$getAllSearchUsers = $this->getUserTable()->getAllUsers();
		}else{
			$getAllSearchUsers = $this->getUserTable()->getSearchUsers($search);
		}
		$view = new ViewModel(
		array(
			'baseUrl' 	   				=> $baseUrl,
			'basePath' 					=> $basePath,
			'getAllSearchUsers' 		=> $getAllSearchUsers->toArray(),	
			'search' 					=> $search,	
			));
		return $view->setTerminal(true);
	}
	public function updateUserStatusAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$user['userId']=$_POST['id'];
		$user['status']=$_POST['status'];
		$updateRemoveUsersstatus = $this->getUserTable()->changeAccountStatus($user);
		$view = new JsonModel(
		array(
			'baseUrl' 	   				=> $baseUrl,
			'basePath' 					=> $basePath,
			'value' 	                => 1,	
			));
		return $view;
	}
	public function adminLogoutAction()
	{
		unset($_SESSION['admininfo']);
		$result = new JsonModel(array(
			'output' => 'success',
			'success'=>false,
		));
		return $result;
	}
	
	public function setDataboxPriorOrderAction()
	{
		if( isset($_POST["categoryId"]) && isset($_POST["boxPriorOrder"]) )
		{
			$boxPriorOrder = $_POST["boxPriorOrder"];
			$carryingCatId = 0;
			if( isset($_POST["carryingCatId"]) )
			{
				$carryingCatId = $_POST["carryingCatId"];
			}

			$setBoxPriorOrderStatus = $this->getUserCategoriesTable()->setDataboxBoxPriorOrder( $_POST["categoryId"],$boxPriorOrder,$carryingCatId );
			echo $setBoxPriorOrderStatus;exit;
		}
		else
		{
			echo "0";exit;
		}
	}
	
	public function setHighlightPriorOrderAction()
	{
		if( isset($_POST["categoryId"]) && isset($_POST["boxPriorOrder"]) )
		{
			$boxPriorOrder = $_POST["boxPriorOrder"];
			$carryingCatId = 0;
			if( isset($_POST["carryingCatId"]) )
			{
				$carryingCatId = $_POST["carryingCatId"];
			}

			$setBoxPriorOrderStatus = $this->getUserCategoriesTable()->setHighlightBoxPriorOrder( $_POST["categoryId"],$boxPriorOrder,$carryingCatId );
			echo $setBoxPriorOrderStatus;exit;
		}
		else
		{
			echo "0";exit;
		}
	}

	public function checkExistingOrderAction()
	{
		$orderExistsStatus = 0;
		$carryingCatId = 0;
		if( isset($_POST["categoryId"]) && isset($_POST["boxPriorOrder"]) && isset($_POST["boxType"]) )
		{
			$existingStatusRs = $this->getUserCategoriesTable()->checkPriorOrderExists( $_POST["categoryId"],$_POST["boxPriorOrder"],$_POST["boxType"] );
			if( $existingStatusRs->count() > 0 )
			{
				$carryingCatId = $existingStatusRs->current()->category_id;
				$orderExistsStatus = 1;
			}
		}
		$result = new JsonModel(array(
			'orderExistsStatus' =>  $orderExistsStatus,
			'carryingCatId' 	=>  $carryingCatId
		));	
		return $result;
	}

	public function checkMontageOrderAction()
	{
		$orderExistsStatus = 0;
		$carryingUserId = 0;
		if( isset($_POST["userId"]) && isset($_POST["montagePriorOrder"]) )
		{
			$existingStatusRs = $this->getUserTable()->checkMontageOrderExists( $_POST["userId"],$_POST["montagePriorOrder"] );
			if( $existingStatusRs->count() > 0 )
			{
				$carryingUserId = $existingStatusRs->current()->user_id;
				$orderExistsStatus = 1;
			}
		}
		$result = new JsonModel(array(
			'orderExistsStatus' =>  $orderExistsStatus,
			'carryingUserId' 	=>  $carryingUserId
		));	
		return $result;
	}

	public function setMontagePriorOrderAction()
	{
		if( isset($_POST["userId"]) && isset($_POST["montagePriorOrder"]) )
		{
			$montagePriorOrder = $_POST["montagePriorOrder"];
			$carryingUserId = 0;
			if( isset($_POST["carryingUserId"]) )
			{
				$carryingUserId = $_POST["carryingUserId"];
			}

			$setBoxPriorOrderStatus = $this->getUserTable()->setMontagePriorOrder( $_POST["userId"],$montagePriorOrder,$carryingUserId );
			echo $setBoxPriorOrderStatus;exit;
		}
		else
		{
			echo "0";exit;
		}
	}

	public function removeDataboxAction()
	{
		$removeDataboxStatus = 0;
		if( isset($_POST["userCategoryId"]) )
		{
			$removeDataboxStatus = $this->getUserCategoriesTable()->removeDatabox( $_POST["userCategoryId"] );
		}
		$result = new JsonModel(array(
			'removeDataboxStatus' 	=>  $removeDataboxStatus
		));	
		return $result;
	}

	public function hideDataboxAction()
	{
		$hideDataboxStatus = 0;
		$newStatus = 3;
		if( isset($_POST["userCategoryId"]) )
		{
			if( isset($_POST["dbStatus"]) )
			{
				$newStatus = $_POST["dbStatus"];
			}
			$hideDataboxStatus = $this->getUserCategoriesTable()->hideDatabox( $_POST["userCategoryId"],$newStatus );
		}
		$result = new JsonModel(array(
			'hideDataboxStatus' 	=>  $hideDataboxStatus
		));	
		return $result;
	}

	public function contactAdminAction(){
		$user_id = 0;
		$loginStatus = 0;
		$ip="";
		if(isset($_SESSION['usersinfo']->userId))
		{
			$user_id=$_SESSION['usersinfo']->userId;
			$loginStatus = 1;
		}
		else
		{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		$contactAdminId = $this->getAdminReportsTable()->addContact($user_id,$_POST,$ip,$loginStatus);
		return $view = new JsonModel(
		array(
			'contactAdminId' =>	$contactAdminId
		));
	}
	
	public function custReportAction(){
		$user_id = 0;
		$ip=$_SERVER['REMOTE_ADDR'];
		if(isset($_SESSION['usersinfo']->userId))
		{
			$user_id=$_SESSION['usersinfo']->userId;
		}
		$custReportId = $this->getAdminReportsTable()->addReport($user_id,$_POST,$ip);
		return $view = new JsonModel(
		array(
			'custReportId' =>	$custReportId
		));
	}

	public function getUserTable()
    {
        if (!$this->userTable) {				
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Databoxuser\Model\UserFactory');			
        }
        return $this->userTable;
    }
	public function getUserCategoriesTable()
    {
        if (!$this->userCategoriesTable) {				
            $sm = $this->getServiceLocator();
            $this->userCategoriesTable = $sm->get('Databox\Model\UserCategoriesFactory');			
        }
        return $this->userCategoriesTable;
    }
	
	public function getAdminReportsTable()
    {
        if (!$this->adminReportsTable) {				
            $sm = $this->getServiceLocator();
            $this->adminReportsTable = $sm->get('ZfcAdmin\Model\AdminReportsFactory');			
        }
        return $this->adminReportsTable;
    }
	public function getDataboxCommentsTable()
    {
        if (!$this->databoxCommentsTable) {				
            $sm = $this->getServiceLocator();
            $this->databoxCommentsTable = $sm->get('Databox\Model\DataboxCommentsFactory');			
        }
        return $this->databoxCommentsTable;
    }
	public function searchAjaxDataboxsAction(){
		$baseUrls = $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl = $baseUrlArr['baseUrl'];
		$basePath = $baseUrlArr['basePath'];
		$search=$_POST['search'];
		if($search=='0'){
			$getAllCommentsDataboxes = $this->getUserCategoriesTable()->getCommentsAllDataboxes();
		}else{
			$getAllCommentsDataboxes = $this->getUserCategoriesTable()->getSearchDataboxComments($search);
		}
		$view = new ViewModel(
		array(
			'baseUrl' 	   				=> $baseUrl,
			'basePath' 					=> $basePath,
			'getAllCommentsDataboxes' 		=> $getAllCommentsDataboxes->toArray(),	
			'search' 					=> $search,	
			));
		return $view->setTerminal(true);
	}
	public function ajaxCommentsAction(){
		$comment_databox_id=$_POST['comment_databox_id'];
		$getDataboxComments = $this->getDataboxCommentsTable()->getDataboxComments($comment_databox_id);
		$comments="";
		$countD=0;
		if($getDataboxComments->count()){
			foreach($getDataboxComments as $data){
					$comments.='<p id="p_databox_comments_' . $data->databox_comment_id . '" >'.$data->databox_comment .'&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" style="color:red;" onClick="deleteComment('.$data->databox_comment_id.' )">DElETE</a></p>';
					$countD++;
			}
		}
		if($countD==0){
			$comments.='<p>Not Found!</p>';
		}
		$view = new JsonModel(
		array(
			'comments' 				=> $comments
		));
		return $view;
	}
	public function deleteCommentAction()
    {
		$deleteComment = $this->getDataboxCommentsTable()->deleteCommentId($_POST['databox_comment_id']);
			return $view = new JsonModel(
				array(
					'output'			=>	1,
				));
    }
	
}