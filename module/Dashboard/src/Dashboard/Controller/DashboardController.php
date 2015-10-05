<?php
namespace Dashboard\Controller;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
class DashboardController extends AbstractActionController
{
	protected  $userCategoriesTable;
	protected  $categoryTable;
	protected  $userDetailsTable;
	protected  $catImageTable;
	protected  $categoryLinksTable;
	protected  $linkDetailsTable;
	protected  $jsPlumbGridTable;

    public function indexAction()
	{
		$baseUrls 	= $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl 	= $baseUrlArr['baseUrl'];
		$basePath 	= $baseUrlArr['basePath'];
		$getDataboxes = $this->getUserCategoriesTable()->getDataboxes( $_SESSION['usersinfo']->userId );
		$dashboard=array();
		$count=1;
		if($getDataboxes->count()!=0){
			foreach($getDataboxes as $databoxes){
				if(array_key_exists($databoxes->category_id,$dashboard)){
					$count++;
					$dashboard[$databoxes->category_id]['category_title']=$databoxes->category_title;
					$dashboard[$databoxes->category_id]['category_description']=$databoxes->category_description;
					$dashboard[$databoxes->category_id]['category_image']=$databoxes->category_image;
					$dashboard[$databoxes->category_id]['user_hashname']=$databoxes->user_hashname;
					$dashboard[$databoxes->category_id]['meta_tags']=$databoxes->meta_tags;
					$dashboard[$databoxes->category_id]['hash_note']=$databoxes->hash_note;
					$dashboard[$databoxes->category_id]['secret_code']=$databoxes->secret_code;
					$dashboard[$databoxes->category_id]['category_type']=$databoxes->category_type;
					$dashboard[$databoxes->category_id]['links'][$count]=$databoxes->link .'***'.$databoxes->link_validity_status;
					$dashboard[$databoxes->category_id]['current_db_info'][$count]=$databoxes->url_id . "\t" . $databoxes->link . "\t" . $databoxes->title . "\t" . $databoxes->image . "\t" . $databoxes->description . "\t" . $databoxes->web_author . "\t" . $databoxes->meta_content . "\t" . $databoxes->link_validity_status . "\t" . $databoxes->is_video . "\t" . $databoxes->iframe_src;
					$dashboard[$databoxes->category_id]['totalLinks']=$count;
					$dashboard[$databoxes->category_id]['user_category_id']=$databoxes->user_category_id;
				}else{
					$count=1;
					$dashboard[$databoxes->category_id]['category_title']=$databoxes->category_title;
					$dashboard[$databoxes->category_id]['category_description']=$databoxes->category_description;
					$dashboard[$databoxes->category_id]['category_image']=$databoxes->category_image;
					$dashboard[$databoxes->category_id]['user_hashname']=$databoxes->user_hashname;
					$dashboard[$databoxes->category_id]['meta_tags']=$databoxes->meta_tags;
					$dashboard[$databoxes->category_id]['hash_note']=$databoxes->hash_note;
					$dashboard[$databoxes->category_id]['secret_code']=$databoxes->secret_code;
					$dashboard[$databoxes->category_id]['category_type']=$databoxes->category_type;
					$dashboard[$databoxes->category_id]['links'][$count]=$databoxes->link .'***'.$databoxes->link_validity_status;
					$dashboard[$databoxes->category_id]['current_db_info'][$count]=$databoxes->url_id . "\t" . $databoxes->link . "\t" . $databoxes->title . "\t" . $databoxes->image . "\t" . $databoxes->description . "\t" . $databoxes->web_author . "\t" . $databoxes->meta_content . "\t" . $databoxes->link_validity_status . "\t" . $databoxes->is_video . "\t" . $databoxes->iframe_src;
					$dashboard[$databoxes->category_id]['totalLinks']=$count;
					$dashboard[$databoxes->category_id]['user_category_id']=$databoxes->user_category_id;
				}
			}
			
			$userId = $_SESSION['usersinfo']->userId;
			$files = glob('./public/dashboard/'.$userId.'-'.'*');
			foreach( $files as $file )
			{
				if( is_file($file) )
				@unlink($file);
			}
			foreach( $dashboard as $key=>$currentBox )
			{
				// echo "<pre>";print_r( $key );echo "#";print_r( $currentBox );
				$currentDbContent = implode( PHP_EOL,$currentBox["current_db_info"] ) . PHP_EOL;
				$fileName = './public/dashboard/'.$userId.'-'.$key.'.txt';
				$fp = fopen( $fileName, 'a' );
				fwrite( $fp,$currentDbContent );
				fclose( $fp );
			}
			// exit;
			
			$view = new ViewModel(
			array(
				'baseUrl' 			=> 	$baseUrl,
				'basePath' 			=> 	$basePath,
				'dashboard'			=>	$dashboard,
				'totalDataboxes'	=>	count($dashboard),
			));
			return $view->setTemplate( "/dashboard/dashboard/Dashboard.phtml" );
		}else{
			$view = new ViewModel(
			array(
				'baseUrl' 	=> $baseUrl,
				'basePath' 	=> $basePath,
			));
			return $view->setTemplate( "/dashboard/dashboard/Dashboard_Empty.phtml" );
		}
	}
	public function updateDashBoardAction(){
		if(isset($_POST['type'])){
			$_SESSION['deleteDatabox']=1;
			$deleteDataboxe = $this->getUserCategoriesTable()->deleteDataboxe( $_POST['userCategoryId'] );
			return $view = new JsonModel(
			array(
				'output' 	=> 1,
			));
		}else{
			//echo '<pre>'; print_r($_POST); exit;
			foreach($_POST as $key=>$data){
				if(strpos($key,'user_category_id-') !== false){
					$category=explode('-',$key);
					$categoryId=$category[1];
				}
			}
			if($_POST['imageId'.$categoryId]!=0){
				unlink('./public/images/project/categoryImages/'.$_POST['imageIdLoad'.$categoryId]);
			}
			if(isset($_POST['sendEmail'.$categoryId])){
				if($_POST['sendEmail'.$categoryId]!=""){
					$user_session = new Container('usersinfo');
					$user_session->email=$_POST['sendEmail'.$categoryId];
				}
			}
			$baseUrls 	= $this->getServiceLocator()->get('config');
			$baseUrlArr = $baseUrls['urls'];
			$baseUrl 	= $baseUrlArr['baseUrl'];
			$basePath 	= $baseUrlArr['basePath'];
			$categoryImage ="";
			if($_POST['typeImageCrop'.$categoryId]==0){
				if(isset($_FILES)){
					if($_FILES['change_image'.$categoryId]['name']!=""){
						move_uploaded_file( $_FILES['change_image'.$categoryId]['tmp_name'],'./public/images/project/categoryImages/'.$_FILES['change_image'.$categoryId]['name'] );
						$categoryImage = $_FILES['change_image'.$categoryId]['name'];
					}
				}
			}else{
				$categoryImage = $_POST['imageId'.$categoryId];
			}

			$categoryDescripton = "";
			$categoryDescripton = $_POST['catDescription'.$categoryId];
			
			$getDataboxes = $this->getCategoryTable()->updateInformation( $_POST,$categoryImage,$categoryId );
			$getDataboxes = $this->getUserCategoriesTable()->updateInformation( $_POST,$categoryId );
			$getDataboxes = $this->getUserCategoriesTable()->updateCatDescription( $categoryId,$categoryDescripton );
			//SEND MAIL START
			if(isset($_POST['private-'.$categoryId])){
				global $dashBoardSubject;                                
				global $dashBoardMessage;
				$dashBoardMessage = str_replace("<FULLNAME>",$_SESSION['usersinfo']->displayName, $dashBoardMessage);
					if($_POST['private-'.$categoryId]=='Private'){
						$dashBoardMessage = str_replace("<MESSAGE>",'Your Public Data box Changed to Private Data box Successfully', $dashBoardMessage);
						$dashBoardMessage = str_replace("<SECURITYCODE>",$_POST['secret_code'.$categoryId], $dashBoardMessage);
					}else{
						$dashBoardMessage = str_replace("<MESSAGE>",'Your Private Data box Changed to Public Data box Successfully', $dashBoardMessage);
						$dashBoardMessage = str_replace("<SECURITYCODE>",'Not Required', $dashBoardMessage);
					}
				$dashBoardMessage = str_replace("<HASHNAME>",$_POST['user_hashname'.$categoryId], $dashBoardMessage);
				$to=$_SESSION['usersinfo']->email;        
				sendMail($to,$dashBoardSubject,$dashBoardMessage);
			}else{
				if(isset($_POST['secret_code'.$categoryId])){
					global $dashBoardSubject;                                
					global $dashBoardMessage;
					$dashBoardMessage = str_replace("<FULLNAME>",$_SESSION['usersinfo']->displayName, $dashBoardMessage);
					$dashBoardMessage = str_replace("<MESSAGE>",'Your Unique Code Of '.$_POST['user_hashname'.$categoryId].' Data Box Changed Successfully', $dashBoardMessage);
					$dashBoardMessage = str_replace("<SECURITYCODE>",$_POST['secret_code'.$categoryId], $dashBoardMessage);
					$dashBoardMessage = str_replace("<HASHNAME>",$_POST['user_hashname'.$categoryId], $dashBoardMessage);
					$to=$_SESSION['usersinfo']->email;        
					sendMail($to,$dashBoardSubject,$dashBoardMessage);
				}
			}
			//END
			
			// Added code for category urls new insertion

			if( isset( $_POST['catBoxesChanged'.$categoryId] ) && $_POST['catBoxesChanged'.$categoryId] == 1 )
			{
				// echo $_POST['catBoxesChanged'.$categoryId];exit;
				
				$linkIdsArray=array();
				$categoryLinksRs = $this->getCategoryLinksTable()->getCategoryLinks( $categoryId );
				foreach( $categoryLinksRs as $currentLinkRow )
				{
					$linkIdsArray[] = $currentLinkRow->category_link_id;
				}
				// echo "<pre>";print_r($linkIdsArray);exit;
				
				foreach( $linkIdsArray as $linkNum=>$linkInfo )
				{
					$linkDetailsDeleteStatus = $this->getLinkDetailsTable()->deleteLinkDetail( $linkInfo );
				}
				
				$categoryLinksDeleteStatus = $this->getCategoryLinksTable()->deleteCategoryLinks( $categoryId );

				$jsPlumbGridDeleteStatus = $this->getJsPlumbGridTable()->deleteCategoryGrid( $categoryId );
				
				$userId = $_SESSION['usersinfo']->userId;
				$fileName = './public/dashboard/'.$userId.'-'.$categoryId.'.txt';
				$fhandle = fopen( $fileName,"r" );
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

							$category_link_id = $this->getCategoryLinksTable()->addLinkMains( $categoryId,$currentUrl,$linkValidityStatus );
							$link_details_id = $this->getLinkDetailsTable()->addLinkDetails( $category_link_id,$title,$image,$description,$author,$keywords,$urlId,$isVideo,$iframeSrc );
						}
					}
					@unlink( $fileName );
					// echo "<pre>";print_r( $fileUrlsArray );
				}
				else
				{
					fclose($fhandle);
				}
			}

			// end code for category urls new insertion
			
			$_SESSION['updateDatabox']=1;
			return $this->redirect()->toUrl('dashboard');
		}
	}
	public function montageAction()
	{
		$baseUrls 	= $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl 	= $baseUrlArr['baseUrl'];
		$basePath 	= $baseUrlArr['basePath'];
		
		$relevanceWorthVoteTable=$this->getServiceLocator()->get('Databox\Model\RelevanceWorthVoteFactory');
		$getDataboxes = $this->getUserCategoriesTable()->getMontages( $_SESSION['usersinfo']->userId );
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
			$view = new ViewModel(
			array(
				'baseUrl' 			=> 	$baseUrl,
				'basePath' 			=> 	$basePath,
				'dashboard'			=>	$dashboard,
				'totalDataboxes'	=>	count($dashboard),
				'privateCount'		=>	$privateCount,
			));
			/*if($_SESSION['usersinfo']->montage_hash_name==""){
				return $view->setTemplate( "/dashboard/dashboard/Montage_First_Access.phtml" );
			}else{*/
				return $view->setTemplate( "/dashboard/dashboard/Montage.phtml" );
			//}
		}else{
			$view = new ViewModel(
			array(
				'baseUrl' 	=> $baseUrl,
				'basePath' 	=> $basePath,
			));
			//return $view->setTemplate( "/dashboard/dashboard/Montage_Empty.phtml" );
			return $view->setTemplate( "/dashboard/dashboard/Montage.phtml" );
		}
	}
	public function montageHashNameAction(){
		$row = $this->getUserDetailsTable()->addText($_SESSION['usersinfo']->userId,$_POST);
		$user_session = new Container('usersinfo');
		if($_POST['type']=='hash'){
			$user_session->montage_hash_name=$_POST['text'];	
		}else if($_POST['type']=='title'){
			$user_session->montage_title=$_POST['text'];
		}else{
			$user_session->montage_paragraph=$_POST['text'];
		}
		return $view = new JsonModel(array(
			'output' 	=> 1,
		));
	}
	public function uploadMontageImageAction(){
	
	//echo '<pre>'; print_r($_FILES); exit;
		header('Access-Control-Allow-Origin: *');
		if(isset($_POST['unlink'])){
			if($_POST['page']=='dash'){
				if($_POST['imageId']!=0){
					unlink('./public/images/project/montageImages/'.$_POST['imageId']);
				}
			}else{
				if($_POST['imageId']!=0){
					unlink('./public/images/project/categoryImages/'.$_POST['imageId']);
				}
			}
			return $view = new JsonModel(array(
				'output' 	=> 1,
			));
		}else{
			$baseUrls 	= $this->getServiceLocator()->get('config');
			$baseUrlArr = $baseUrls['urls'];
			$baseUrl 	= $baseUrlArr['baseUrl'];
			$basePath 	= $baseUrlArr['basePath'];
			if($_POST['page']!='montage'){
				$getImageId = $this->getCatImageTable()->addImage();
				$montageImage=$getImageId;
			}
			$imageName="";
			$value="";
			$croppedX = 0;
			$croppedY = 0;
			$croppedNewWidth = 0;
			$croppedNewHeight = 0;
			if(isset($_FILES) && isset($_FILES['montage_file']['name'])){
				$image = stripslashes($_FILES['montage_file']['name']);
				$extension = getExtension($image);
				$extension = strtolower($extension);
				$uploadedfile=$_FILES['montage_file']['tmp_name'];
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
				$filename='./public/images/project/montageImages/'.$montageImage.'.'.$extension;
				imagejpeg($tmp,$filename,100);
				imagedestroy($tmp);
				imagedestroy($src);
				if(isset($_SESSION['usersinfo']->userId)){
					$row = $this->getUserDetailsTable()->addImage($_SESSION['usersinfo']->userId,$montageImage.'.'.$extension);
					$user_session = new Container('usersinfo');
					$user_session->montage_image=$montageImage.'.'.$extension;
					$value=1;
				}else{
					$value=0;
				}
				$imageName=$montageImage.'.'.$extension;
			}else{
				if($_POST['page']=='acc'){
					define('UPLOAD_DIR', './public/images/project/montageImages/');
					if($_POST['imageId']!=0){
						unlink('./public/images/project/montageImages/'.$_POST['imageId']);
					}
				}else if($_POST['page']=='montage'){
					define('UPLOAD_DIR', './public/images/project/montageMainImage/');
					if($_POST['imageId']!=0){
						unlink('./public/images/project/montageMainImage/'.$_POST['imageId'].'.jpg');
						$montageImage=$_POST['imageId'];
					}
				}else{
					define('UPLOAD_DIR', './public/images/project/categoryImages/');
					if($_POST['imageId']!=0){
						unlink('./public/images/project/categoryImages/'.$_POST['imageId']);
					}
				}
				if(isset($_FILES) && isset($_FILES['fileCropInp']['name'])){
					if( isset($_POST['croppedNewWidth']) )
					{
						$croppedNewWidth = $_POST['croppedNewWidth'];
					}
					if( isset($_POST['croppedNewHeight']) )
					{
						$croppedNewHeight = $_POST['croppedNewHeight'];
					}
					if( isset($_POST['croppedX']) )
					{
						$croppedX = $_POST['croppedX'];
					}
					if( isset($_POST['croppedY']) )
					{
						$croppedY = $_POST['croppedY'];
					}
					
					$image = stripslashes($_FILES['fileCropInp']['name']);
					$extension = getExtension($image);
					$extension = strtolower($extension);
					
					$uploadedfile=$_FILES['fileCropInp']['tmp_name'];
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
					
					$tmp=imagecreatetruecolor($croppedNewWidth,$croppedNewHeight);
					imagecopyresampled($tmp,$src,0,0,$croppedX,$croppedY,$croppedNewWidth,$croppedNewHeight,$croppedNewWidth,$croppedNewHeight);

					// Code for minimum width with aspect ratio.
					$minAllowedWidth=300;
					if( $croppedNewWidth < $minAllowedWidth )
					{
						$originalWidth = $croppedNewWidth;
						$originalHeight = $croppedNewHeight;
						
						$tzCropRatio = 0;
						$tzCropRatio = $originalHeight / $originalWidth;

						$croppedNewWidth = $minAllowedWidth;
						$croppedNewHeight = $croppedNewWidth * $tzCropRatio;
						
						$tmp1=imagecreatetruecolor($croppedNewWidth,$croppedNewHeight);
						imagecopyresampled($tmp1,$tmp,0,0,0,0,$croppedNewWidth,$croppedNewHeight,$originalWidth,$originalHeight);
						
						$filename='./public/images/project/categoryImages/'.$montageImage.'.'.$extension;
						imagejpeg($tmp1,$filename,100);
						imagedestroy($tmp);
						imagedestroy($tmp1);
						imagedestroy($src);
					}
					else
					{
						$filename='./public/images/project/categoryImages/'.$montageImage.'.'.$extension;
						imagejpeg($tmp,$filename,100);
						imagedestroy($tmp);
						imagedestroy($src);
					}
					// End Code for minimum width with aspect ratio.

					$imageName=$montageImage.'.'.$extension;
					
					if( isset($_POST['imageId']) && $_POST['imageId']!=0 )
					{
						@unlink('./public/images/project/categoryImages/'.$_POST['imageId']);

					}
				}else{
					$img = $_POST['data'];
					$img = str_replace('data:image/png;base64,', '', $img);
					$img = str_replace(' ', '+', $img);
					$data = base64_decode($img);
					$file = UPLOAD_DIR . $montageImage.'.png';
					$success = file_put_contents($file, $data);
					$this->allTOJpeg($montageImage,UPLOAD_DIR);
					if(isset($_SESSION['usersinfo']->userId)){
						if($_POST['page']=='acc'){
							$row = $this->getUserDetailsTable()->addImage($_SESSION['usersinfo']->userId,$montageImage.'.jpg');
							$user_session = new Container('usersinfo');
							$user_session->montage_image=$montageImage.'.jpg';
						}else if($_POST['page']=='montage'){
							$row = $this->getUserDetailsTable()->addMontageMainImage($_SESSION['usersinfo']->userId,$montageImage.'.jpg');
							$user_session = new Container('usersinfo');
							$user_session->montage_main_image=$montageImage.'.jpg';
						} 
						$value=1;
					}else{
						$value=0;
					}
					$imageName=$montageImage.'.jpg';
				}
			}
			$deleteImage = $this->getCatImageTable()->deleteImage($getImageId);
			return $view = new JsonModel(array(
				'output' 	=> 1,
				'imageId' 	=> $imageName,
				'value' 	=> $value
			));
		}
	}

	private function allTOJpeg($file,$path){
		$img = $path . $file . '.png';
		$dst = $path . $file;
		if (($img_info = getimagesize($img)) === FALSE)
		die("Image not found or not an image");
		$width = $img_info[0];
		$height = $img_info[1];
		switch ($img_info[2]) {
			case IMAGETYPE_GIF  : $src = imagecreatefromgif($img);  break;
			case IMAGETYPE_JPEG : $src = imagecreatefromjpeg($img); break;
			case IMAGETYPE_PNG  : $src = imagecreatefrompng($img);  break;
			default : die("Unknown filetype");
		}
		$tmp = imagecreatetruecolor($width, $height);
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
		imagejpeg($tmp, $dst.".jpg");
		unlink($img);
	}
	public function getCatImageTable()
    {
        if (!$this->catImageTable) {				
            $sm = $this->getServiceLocator();
            $this->catImageTable = $sm->get('Databox\Model\CatImageFactory');			
        }
        return $this->catImageTable;
    }
	public function paginationMontageLinksAction(){
		$baseUrls 	= $this->getServiceLocator()->get('config');
		$baseUrlArr = $baseUrls['urls'];
		$baseUrl 	= $baseUrlArr['baseUrl'];
		$basePath 	= $baseUrlArr['basePath'];
		$result= new ViewModel(array(
				'dashboard'		=>	$_SESSION['montageLinks'],
				'start'			=>	$_POST['start'],
				'end'			=>	$_POST['end'],
				'catId'			=>	$_POST['catId'],
				'baseUrl'		=>  $baseUrl,
				'basePath'		=>  $basePath,
			));
			$result->setTerminal(true);
			return $result;
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
	public function getUserDetailsTable()
    {
        if (!$this->userDetailsTable) {				
            $sm = $this->getServiceLocator();
            $this->userDetailsTable = $sm->get('Databoxuser\Model\UserDetailsFactory');			
        }
        return $this->userDetailsTable;
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
	public function getJsPlumbGridTable()
    {
        if (!$this->jsPlumbGridTable) {				
            $sm = $this->getServiceLocator();
            $this->jsPlumbGridTable = $sm->get('Databox\Model\JsPlumbGridFactory');			
        }
        return $this->jsPlumbGridTable;
    }
	
}

		

	
