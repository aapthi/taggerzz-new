<?php
namespace ScnSocialAuth\Controller;
use Hybrid_Auth;
use ScnSocialAuth\Mapper\Exception as MapperException;
use ScnSocialAuth\Mapper\UserProviderInterface;
use ScnSocialAuth\Options\ModuleOptions;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Model\Pendingmail;
use Application\Model\Cities;
use Application\Model\Country;
use Application\Model\Ipaddress;
use Application\Model\State;
use Users\Model\InviteToFriendsTable;
use Users\Model\InviteToFriends;

class UserController extends AbstractActionController
{
		protected  $ipaddressTable;
		protected  $citiesTable;
		protected  $stateTable;
		protected  $countryTable;
	    protected  $pendingmailTable;
	    protected  $invitetoFriendsTable;
    /**
     * @var UserProviderInterface
     */
    protected $mapper;
	
    /**
     * @var Hybrid_Auth
     */
    protected $hybridAuth;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /*
     * @todo Make this dynamic / translation-friendly
     * @var string
     */
    protected $failedAddProviderMessage = 'Add provider failed. Please try again.';
	public function staticAction()
	{
    $pageName = $this->params('page_name');
    $view = new ViewModel();

    // loads views like view/static/author.phtml
    $view->setTemplate('static/' . $pageName); 

    return $view;
	}
    public function addProviderAction()
    {
        // Make sure the provider is enabled, else 404
        $provider = $this->params('provider');
        if (!in_array($provider, $this->getOptions()->getEnabledProviders())) {
            return $this->notFoundAction();
        }

        $authService = $this->zfcUserAuthentication()->getAuthService();

        // If user is not logged in, redirect to login page
        if (!$authService->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login');
        }

        $hybridAuth = $this->getHybridAuth();
        $adapter = $hybridAuth->authenticate($provider);

        if (!$adapter->isUserConnected()) {
            $this->flashMessenger()->setNamespace('zfcuser-index')->addMessage($this->failedAddProviderMessage);

            return $this->redirect()->toRoute('zfcuser');
        }

        $localUser = $authService->getIdentity();
        $userProfile = $adapter->getUserProfile();
        $accessToken = $adapter->getAccessToken();
        $redirect = $this->params()->fromQuery('redirect', false);

        try {
            $this->getMapper()->linkUserToProvider($localUser, $userProfile, $provider, $accessToken);
        } catch (MapperException\ExceptionInterface $e) {
            $this->flashMessenger()->setNamespace('zfcuser-index')->addMessage($e->getMessage());
        }

        if ($this->getServiceLocator()->get('zfcuser_module_options')->getUseRedirectParameterIfPresent() && $redirect) {
            return $this->redirect()->toUrl($redirect);
        }

        return $this->redirect()->toRoute(
            $this->getServiceLocator()->get('zfcuser_module_options')->getLoginRedirectRoute()
        );
    }

    public function providerLoginAction()
    {
		$provider = $this->getEvent()->getRouteMatch()->getParam('provider');
        if (!in_array($provider, $this->getOptions()->getEnabledProviders())) {
            return $this->notFoundAction();
        }
        $hybridAuth = $this->getHybridAuth();

        $query = array('provider' => $provider);
        if ($this->getServiceLocator()->get('zfcuser_module_options')->getUseRedirectParameterIfPresent() && $this->getRequest()->getQuery()->get('redirect')) {
            $query = array_merge($query, array('redirect' => $this->getRequest()->getQuery()->get('redirect')));
        }
        $redirectUrl = $this->url()->fromRoute('scn-social-auth-user/authenticate', array(), array('query' => $query));

        $adapter = $hybridAuth->authenticate(
            $provider,
            array(
                'hauth_return_to' => $redirectUrl,
            )
        );

        return $this->redirect()->toUrl($redirectUrl);
    }

    public function loginAction()
    {
		$zfcUserLogin = $this->forward()->dispatch('zfcuser', array('action' => 'login'));
        if (!$zfcUserLogin instanceof ModelInterface) {
            return $zfcUserLogin;
        }
        $viewModel = new ViewModel();
        $viewModel->addChild($zfcUserLogin, 'zfcUserLogin');
        $viewModel->setVariable('options', $this->getOptions());

        $redirect = false;
        if ($this->getServiceLocator()->get('zfcuser_module_options')->getUseRedirectParameterIfPresent() && $this->getRequest()->getQuery()->get('redirect')) {
            $redirect = $this->getRequest()->getQuery()->get('redirect');
        }
        $viewModel->setVariable('redirect', $redirect);
		$viewModel->setTerminal(true);
        return $viewModel;
    }

    public function logoutAction()
    {
        Hybrid_Auth::logoutAllProviders();

        //return $this->forward()->dispatch('zfcuser', array('action' => 'logout'));
    }

    public function registerAction()
    {
        $zfcUserRegister = $this->forward()->dispatch('zfcuser', array('action' => 'register'));
        if (!$zfcUserRegister instanceof ModelInterface) {
            return $zfcUserRegister;
        }
        $viewModel = new ViewModel();
        $viewModel->addChild($zfcUserRegister, 'zfcUserLogin');
        $viewModel->setVariable('options', $this->getOptions());

        $redirect = false;
        if ($this->getServiceLocator()->get('zfcuser_module_options')->getUseRedirectParameterIfPresent() && $this->getRequest()->getQuery()->get('redirect')) {
            $redirect = $this->getRequest()->getQuery()->get('redirect');
        }
        $viewModel->setVariable('redirect', $redirect);

        return $viewModel;
    }

    /**
     * set mapper
     *
     * @param  UserProviderInterface $mapper
     * @return HybridAuth
     */
    public function setMapper(UserProviderInterface $mapper)
    {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * get mapper
     *
     * @return UserProviderInterface
     */
    public function getMapper()
    {
        if (!$this->mapper instanceof UserProviderInterface) {
            $this->setMapper($this->getServiceLocator()->get('ScnSocialAuth-UserProviderMapper'));
        }

        return $this->mapper;
    }

    /**
     * Get the Hybrid_Auth object
     *
     * @return Hybrid_Auth
     */
    public function getHybridAuth()
    {
        if (!$this->hybridAuth) {
            $this->hybridAuth = $this->getServiceLocator()->get('HybridAuth');
        }

        return $this->hybridAuth;
    }

    /**
     * Set the Hybrid_Auth object
     *
     * @param  Hybrid_Auth    $hybridAuth
     * @return UserController
     */
    public function setHybridAuth(Hybrid_Auth $hybridAuth)
    {
        $this->hybridAuth = $hybridAuth;

        return $this;
    }

    /**
     * set options
     *
     * @param  ModuleOptions  $options
     * @return UserController
     */
    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * get options
     *
     * @return ModuleOptions
     */
    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceLocator()->get('ScnSocialAuth-ModuleOptions'));
        }

        return $this->options;
    }
	public function getCitiesTable(){
		if (!$this->citiesTable) {
			$sm = $this->getServiceLocator();
			$this->citiesTable = $sm->get('Application\Model\CitiesTable');			
		}
		return $this->citiesTable;
	}
	public function invitefriendsAction()
    {
	
		$citynamesss=array();
		$country=array();
		$cities = $this->getCitiesTable()->getCity();
		foreach($cities as $key=>$citynamess){		
			$citynamesss[]=$citynamess;		
		}
		foreach($citynamesss as $citynames){
			$countryname=$citynames->name;			
			$country[$countryname][]=$citynames->city_name;			
		}				
		$this->layout()->setVariable('cities', $country);
		if(isset($_SESSION['ip_cityName'])){
			$_SESSION['ip_cityName']=$_SESSION['ip_cityName'];
		}else{			
			$ipaddress1=$_SERVER['REMOTE_ADDR'];			
			if($ipaddress1!=""){
				try {					
					$table = $this->getServiceLocator()->get('Application\Model\IpaddressTable');
					$ip = $table->getIp($ipaddress1);					
					$_SESSION['ip_cityName']=$ip->city_name;
					$this->layout()->setVariable('cityname', $ip->city_name);				
				}
				catch (\Exception $ex) {					
					$addr_details = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ipaddress1));					
					$cityname = stripslashes(ucfirst($addr_details['geoplugin_city']));	
					$statename = stripslashes(ucfirst($addr_details['geoplugin_region']));
					$countrycode = stripslashes(ucfirst($addr_details['geoplugin_countryCode']));
					$countryname = stripslashes(ucfirst($addr_details['geoplugin_countryName']));					
					$ip=$this->getIpaddressTable()->saveAddress($ipaddress1,$cityname,$statename,$countryname);
					if($cityname!=""){					
						$cityIsExit=$this->getCitiesTable()->checkonCity($cityname);						
						if($cityIsExit==0){	
							$insert_cities = $this->getCitiesTable()->addCity($cityname,$countrycode,$getstateid->state_id="");
						}
					}	
					if($ip>0){
						$ip = $table->getIp($ipaddress1);
						$_SESSION['ip_cityName']=$ip->city_name;
						$this->layout()->setVariable('cityname', $ip->city_name);
					}	
				}
			}
		}
		
		//echo $this->getRequest()->getRequestUri();
		$folowersarray=array();
		 $provider = $this->params()->fromRoute('id', 0);
		 $hybridAuth = $this->getHybridAuth();
		 $suggestfollowers=array();
		if(isset($_SESSION['user']['user_id'])){
			$table = $this->getServiceLocator()->get('Users\Model\UserTable');
			$userDetailess = $table->getuserDetailes($_SESSION['user']['user_id']);
			
			foreach($userDetailess as $user){			
				$userDetailes=$user;
				$_SESSION['user']['totalReviews']=$userDetailes->totalReviews;
				$_SESSION['user']['countFollowers']=$userDetailes->countFollower;
			}				
		}
		
		 if($provider=='0')
		 {
			$providers = $hybridAuth->getConnectedProviders();
			if(isset($providers[0]) && $providers!="0")
			{
				$_SESSION['provider']=$providers[0];
				$provider=$providers[0];
			}
			else
			{
				$provider="loginuser";
			}
				
			
		  }
		  
		  
		  $myfolowers="'".$_SESSION['user']['user_id']."',";
				$followerstable=$this->getServiceLocator()->get('Album\Model\FriendscircleTable');
				
				$followers=$followerstable->getmyfollowings($_SESSION['user']['user_id']);
				if(count($followers))
				{
				foreach($followers as $follower)
				{
				$folowersarray[]=$follower->friend_id;
				$myfolowers.="'".$follower->friend_id."',";
				}
					
				}
				$myfolowers=trim($myfolowers,',');
				//echo "<pre>";print_r($myfolowers);exit;
				$table=$this->getServiceLocator()->get('Album\Model\UsersTable');
				$suggestfollowers=$table->getsuggestedfollowers($myfolowers);
				//echo "<pre>";print_r($suggestfollowers);exit;
		  $user_contacts=array();
		  $friend_arry=array();
		  if($provider!="loginuser")
		  {
			$adapter = $hybridAuth->authenticate($provider);
			$user_contacts = $adapter->getUserContacts();
			$getfriendslist=$this->getinvitetoFriendsTable()->getinviteList();
			foreach($getfriendslist as $freindsarray){
				$friend_arry[$freindsarray->invited_friend_id]=$freindsarray->invited_friend_id;
			}
		  }
		  //echo "<pre>";print_r($friend_arry);exit;
		 return new ViewModel(array(
			 'myfolowers'=>$folowersarray,
			 'user_contacts' => $user_contacts,
			 'provider' => $provider,
			 'suggestfollowers' => $suggestfollowers,
			 'getfriendslists' => $friend_arry,
			 )
		 );	
    }
	public function suggestedfollowersajaxAction()
	{
		$myfolowers="'".$_SESSION['user']['user_id']."',";
				$followerstable=$this->getServiceLocator()->get('Album\Model\FriendscircleTable');
				
				$followers=$followerstable->getmyfollowings($_SESSION['user']['user_id']);
				if(count($followers))
				{
				foreach($followers as $follower)
				{
				$folowersarray[]=$follower->friend_id;
				$myfolowers.="'".$follower->friend_id."',";
				}
					$myfolowers=trim($myfolowers,',');
				}
				$table=$this->getServiceLocator()->get('Album\Model\UsersTable');
				$suggestfollowers=$table->getsuggestedfollowers($myfolowers);
				 $view= new ViewModel(array(
				 'myfolowers'=>$folowersarray,
				 'suggestfollowers' => $suggestfollowers,
				 )
				 );	
				return $view->setTerminal(true)->setTemplate("application/index/suggestfollowers.phtml");
	}
	public function sendInviteAction(){	//echo '<pre>'; print_r($_POST['email']); exit;
		if($_POST['email'])	{
			if(!is_array($_POST['email']))
			{
			$arrayemailId=explode(',',$_POST['email']);
			}
			else
			{
				$arrayemailId=$_POST['email'];
			}
			if(isset($_POST['invite']) && $_POST['invite']=="invite_friends"){
				if(isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id']!=""){
					$user_id=$_SESSION['user']['user_id'];
					foreach($arrayemailId as $arrayemail){
						$addinvitefriends_list=$this->getinvitetoFriendsTable()->addinviteList($arrayemail,$user_id,$_POST['provider']);
					}
				}				
			}			
			foreach($arrayemailId as $emailId){
			$subject='Welcome to Freshen Me';
			$message ='Invition For The Freshen Me';
			$to_mail =$emailId;	
			$to_from  = 'From: dileepkumarkonda@gmail.com' . "\r\n" ;
			//Insert Send email						
			$pendingmail_id[]=$this->getPendingmailTable()->addMail($subject,$message,$to_mail,$to_from);		
			}
			if(count($pendingmail_id)){	
				$result = new JsonModel(array(					
					'output' => 'success',
					'success'=>true,
				));	
			}else{
				$result = new JsonModel(array(					
					'output' => 'not success',
					'success'=>false,
				));	
			}			
			return	$result;		
		}
	}
	public function crontosendpendingmailsAction()
	{
			include('public/PHPMailer_5.2.4/sendmail.php');	
			$tablefactory=$this->getServiceLocator()->get('Business\Model\PendingmailTableFactory');
			$pendingmails=$tablefactory->getpendingmails();
			$ids=array();
			if(count($pendingmails))
			{
				foreach($pendingmails as $mailcontent)
				{
					//echo "<pre>";print_r($mailcontent);exit;
					global $pendingmailsubject;				
					global $pendingmailMessage;
					$datetime	= date("m-d-Y, h:i:s A T"); 
					$dateformat = explode("-", $datetime);
					$longdate=date("F d, Y", mktime(0, 0, 0, $dateformat[0], $dateformat[1], $dateformat[2]));
					$abrivate=date("M d, Y", mktime(0, 0, 0, $dateformat[0], $dateformat[1], $dateformat[2]));
					$shortdate=date("m/d/Y");
					$message=$mailcontent->content;
					$body=$pendingmailMessage;
					$pendingmailsubject=$mailcontent->subject;
					$to=$mailcontent->to_mail;
					$unsubscribelink="<a target='_blank' href='http://" . $_SERVER['HTTP_HOST']."/unsubscribe/".base64_encode($mailcontent->to_mail)."'>Unsubscribe</a>";
					//echo $unsubscribelink;echo $mailcontent->to_mail;exit;
					if(isset($mailcontent->display_name) && $mailcontent->display_name!='')
					{
						$displayname=$mailcontent->display_name;
					}
					else
					{
						$displayname=$mailcontent->to_mail;
					}
					$body = str_replace("<BODY>", $message, $body);
					$body = str_replace("{SENDEREMAIL}",$mailcontent->to_from, $body);
					$body = str_replace("<UNSUBSCRIBE>",$unsubscribelink, $body);
					$body = str_replace("{RECEIVEREMAIL}",$mailcontent->to_mail, $body);
					$body = str_replace("{FIRSTNAME}",$mailcontent->display_name, $body);
					$body = str_replace("{ABRIVATED}",$abrivate, $body);
					$body = str_replace("{LONGDATE}",$longdate, $body);
					$body = str_replace("{SHORTDATE}",$shortdate, $body);
					//echo $body;exit;
				if(sendMail($to,$pendingmailsubject,$body))
				{
					$ids[]=$mailcontent->mail_id;
				}
				}
			}
			if(count($ids))
			{
				$pendingmails=$this->getPendingmailTable()->updatemailstatus($ids);
			}
		    echo "Pending mails are sended";exit;			
	}
	public function unsubscribeAction()
	{
		$subscriber_email=base64_decode($this->params()->fromRoute('id', 0));
		$tblsubscriberTable = $this->getServiceLocator()->get('Application\Model\SubscriberTable');
		$subscriberslist=$tblsubscriberTable->unsubscribeuser($subscriber_email);
		return new viewModel();
		
	}
	public function getPendingmailTable()	{
		if (!$this->pendingmailTable) {
		$sm = $this->getServiceLocator();
		$this->pendingmailTable = $sm->get('Application\Model\PendingmailTable');
		}
		return $this->pendingmailTable;
	}
	public function getCountryTable()
    {
        if (!$this->countryTable) {
            $sm = $this->getServiceLocator();
            $this->countryTable = $sm->get('Application\Model\CountryTable');			
        }
        return $this->countryTable;
    }
	public function getIpaddressTable()
    {
        if (!$this->ipaddressTable) {
            $sm = $this->getServiceLocator();
            $this->ipaddressTable = $sm->get('Application\Model\IpaddressTable');
        }
        return $this->ipaddressTable;
    }
	public function getStateTable()
    {
        if (!$this->stateTable) {
            $sm = $this->getServiceLocator();
            $this->stateTable = $sm->get('Application\Model\StateTable');			
        }
        return $this->stateTable;
    }
	public function getinvitetoFriendsTable(){
		if (!$this->invitetoFriendsTable) {
			$sm = $this->getServiceLocator();
			$this->invitetoFriendsTable = $sm->get('Users\Model\InviteToFriendsTable');			
		}
		return $this->invitetoFriendsTable;
	}
}
