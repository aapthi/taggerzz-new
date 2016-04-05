<?php
namespace ZfcUser\Entity;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;

class IpltelUserTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	public function getalladmins(){
		$select = $this->tableGateway->getSql()->select();			
		$select->where('user_type="admin"');		
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function getdetails_superadmin(){
		$select = $this->tableGateway->getSql()->select();			
		$select->where('user_type="superadmin"');		
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->current();
	}
	public function checkEmail($email)
    {	
		$select = $this->tableGateway->getSql()->select();			
		$select->where('email="'.$email.'"');		
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->count();			
		return $row;		
	}
	public function checkUniqueRecord($email){	
		 $select = $this->tableGateway->getSql()->select()			
			->where('email="'.$email.'"');					 
		$resultSet = $this->tableGateway->selectWith($select);			
		$row=$resultSet->count();		
		return $row;
	}
	public function getMembersByMailId($email){	
		 $select = $this->tableGateway->getSql()->select()			
			->where('email="'.$email.'"');					 
		$resultSet = $this->tableGateway->selectWith($select);		
		return $resultSet;
	}
	public function adduser($users,$upassword)
    {
		if(isset($_SESSION['user']['user_type']) && $_SESSION['user']['user_type']=='superadmin'){
			if(isset($users['admin_adding_user_id']) && $users['admin_adding_user_id']!=""){
				$admin_user_id= $users['admin_adding_user_id'];
			}else{
				$admin_user_id= $_SESSION['user']['user_id'];
			}
		}else if(isset($_SESSION['user']['user_type']) && $_SESSION['user']['user_type']=='admin'){
			$admin_user_id= $_SESSION['user']['user_id'];
		}
		$password=md5($upassword);		
		$data = array(
			'company_name' 	  	       => $users['cname'], 	
			'industry'   			   => $users['industry'], 	
			'address1' 		           => $users['address1'],
			'address2' 		           => $users['address2'],
			'address3' 		           => $users['address3'],
			'landmarknearby'           => $users['lmnear'],
			'city' 		         	   => $users['city'],
			'country' 		           => $users['country'],
			'email' 		           => $users['email'],
			'password' 		           => $password,
			'phone' 		           => $users['phone'],  		
			'fax'                      => $users['fax'], 
			'contact_person'           => $users['cperson'], 	
			'mobile' 		           => $users['mobile'], 	
			'second_email' 		       => $users['email1'], 	
			'bankaddress' 		       => $users['baddress'], 	
			'account_number' 		   => $users['acno'], 
			'note' 		   			   => $users['note'], 
			'user_type' 			   => $users['type'],
            'u_status'			       => 1,
			'parent_user_id' 		   => $admin_user_id, 			
		);	
		$insertresult=$this->tableGateway->insert($data);
		$select = $this->tableGateway->getSql()->select()
				->where('email="'.$data['email'].'"')
				->where('password="'.$data['password'].'"');
		$resultSet = $this->tableGateway->selectWith($select);	
		$row = $resultSet->current();	
		return $row;		
    }
	public function updateIptelUser( $areaprefix,$phoneNumber,$status,$email ){
		$pin=rand( 1000,999999999999 );
		$data = array(
				'areaprefix' 	=>$areaprefix,
				'phone_number' 	=>$phone_number,
				'status'  	=>$status,
				'pin'  	=>$pin,
				);
		$updateuserid=$this->tableGateway->update($data, array('email' => $email));
		return 	$updateuserid;			
	}	
	public function getUser($user)
    {
		$select = $this->tableGateway->getSql()->select()
				->where('user_id="'.$user.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();
        return $row;
	}	
	public function updateUser($users,$userid)
    {
		if(isset($_SESSION['user']['user_type']) && $_SESSION['user']['user_type']=='superadmin'){
			if(isset($users['admin_adding_user_id']) && $users['admin_adding_user_id']!=""){
				$admin_user_id= $users['admin_adding_user_id'];
			}else{
				$admin_user_id= $_SESSION['user']['user_id'];
			}
		}else if(isset($_SESSION['user']['user_type']) && $_SESSION['user']['user_type']=='admin'){
			$admin_user_id= $_SESSION['user']['user_id'];
		}
		$data = array(
			'company_name' 	  	       => $users['cname'], 	
			'industry'   			   => $users['industry'], 	
			'address1' 		           => $users['address1'],
			'address2' 		           => $users['address2'],
			'address3' 		           => $users['address3'],
			'landmarknearby'           => $users['lmnear'],
			'city' 		         	   => $users['city'],
			'country' 		           => $users['country'],
			'email' 		           => $users['email'],
			'phone' 		           => $users['phone'],  
			'fax'                      => $users['fax'], 
			'contact_person'           => $users['cperson'], 	
			'mobile' 		           => $users['mobile'], 	
			'second_email' 		       => $users['email1'], 	
			'bankaddress' 		       => $users['baddress'], 	
			'account_number' 		   => $users['acno'], 
			'note' 		   			   => $users['note'], 
			'user_type' 			   => $users['type'],
            'u_status'			       => $users['status'],
			'parent_user_id' 		   => $admin_user_id, 	
		);
		$updateuserid=$this->tableGateway->update($data, array('user_id' => $userid));
		return 	$updateuserid;		
	}
	public function editupdate($users,$userid)
    {
		$data = array(
			'company_name' 	  	       => $users['cname'], 	
			'industry'   			   => $users['industry'], 	
			'address1' 		           => $users['address1'],
			'address2' 		           => $users['address2'],
			'address3' 		           => $users['address3'],
			'landmarknearby'           => $users['lmnear'],
			'city' 		         	   => $users['city'],
			'country' 		           => $users['country'],
			'email' 		           => $users['email'],
			'phone' 		           => $users['phone'],  
			'fax'                      => $users['fax'], 			
		);
		$updateuserid=$this->tableGateway->update($data, array('user_id' => $userid));
		return 	$updateuserid;		
	}
	public function editUpdate2($users,$userid)
    {
		$data = array(
			'contact_person'           => $users['cperson'], 	
			'mobile' 		           => $users['mobile'], 	
			'second_email' 		       => $users['second_email'], 	
			'bankaddress' 		       => $users['baddress'], 	
			'account_number' 		   => $users['acno'], 
			'note' 		   			   => $users['note'], 
			'user_type' 			   => $users['type'],
            'u_status'			       => $users['status'],		
		);
		$updateuserid=$this->tableGateway->update($data, array('user_id' => $userid));
		return 	$updateuserid;		
	}
	public function getallAdminUsers(){
		if(isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id']!=""){
			$user_id=$_SESSION['user']['user_id'];
			$user_details=$this->editUser($user_id);
			$select = $this->tableGateway->getSql()->select();
			$select->where('parent_user_id="'.$user_id.'"');	
			$resultSet = $this->tableGateway->selectWith($select);
			return $resultSet;
		}
	}
	public function reportsData()
    {
		$select = $this->tableGateway->getSql()->select();
		$user_id=$_SESSION['user']['user_id'];
		$select->where('parent_user_id="'.$user_id.'"');
		$select->where('user_type="admin"');
		$select->where('u_status="1"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function getdataUsers($usertype)
    {	
		$select = $this->tableGateway->getSql()->select();
		$user_id=$_SESSION['user']['user_id'];
		if($usertype=='superadmin'){
			$select->where('parent_user_id="'.$user_id.'"');
			$select->where('user_id!="'.$user_id.'"');
		}else if($usertype=='user'){
			$select->where('user_type="'.$usertype.'"');
			$select->where('parent_user_id="'.$user_id.'"');
		}else if($usertype=='admin'){
			$select->where('user_type="'.$usertype.'"');
			$select->where('parent_user_id="'.$user_id.'"');
		}else{
			$select->where('user_type!="superadmin"');
		}
		$resultSet = $this->tableGateway->selectWith($select);	
		return $resultSet;
	}
	public function getUserdelete($user_id)
    {
		$data = array(
			'u_status'      	=>2,
		);
		$result=$this->tableGateway->update($data, array('user_id' => $user_id));
		//$result=$this->tableGateway->delete(array('user_id' => $data));
		return  $result;
	}	
	public function getadminUsers($user_id,$user_user_id,$check)
    {		
		$select = $this->tableGateway->getSql()->select();
		$select->where('parent_user_id="'.$user_id.'"');
		if( $check !='check' ){
			$select->where('user_id!="'.$user_user_id.'"');
		}
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function getall_Admin_Users($user_id)
    {	
		$select = $this->tableGateway->getSql()->select();
		$select->where('parent_user_id="'.$user_id.'"');		
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function checkLogin($user,$authreg='')
    {
		if($authreg==''){		
			$password=md5($user['userPassword']);
			$email=$user['userEmail'];
		}else if($authreg=='check'){
			$password=$user['password'];
			$email=$user['email'];
		}else{
			$password=$user['userPassword'];
			$email=$user['userEmail'];
		}	
		$select = $this->tableGateway->getSql()->select();				
		$select->join('country', 'users_new.country=country.country_id',array('usercountry'=>'nicename'),'left');
		$select->where('users_new.email="'.$email.'"');
		$select->where('users_new.password="'.$password.'"');
		$select->where('users_new.u_status="1"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();
        return $row;
	}
	public function getuserDetailes($user_id){	
		$select = $this->tableGateway->getSql()->select();
		$select	->join('country', 'users_new.country=country.country_id',array('usercountry'=>'nicename'),'left');
		$select->where('users_new.user_id="'.$user_id.'"');	
		$resultSet = $this->tableGateway->selectWith($select);	
		return $resultSet;				
	}	
	public function editUser($id)
    {
		$select = $this->tableGateway->getSql()->select()
				->join('country', 'users_new.country=country.country_id',array('usercountry'=>'nicename'),'left')
				->join('industry', 'users_new.industry=industry.industry_id',array('industryname'),'left')
				->where('user_id= "'.$id.'"');					 
		$resultSet = $this->tableGateway->selectWith($select);	
		$row = $resultSet->current();
        return $row;
	}
	public function getpassword($pwd,$userid){ 
		$pwd=md5($pwd);
		$select = $this->tableGateway->getSql()->select();
		$select->where('password="'.$pwd.'"');
		$select->where('user_id="'.$userid.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->count();			
		return $row;
	}
	public function changepwd($pwd,$userid){
		$password=md5($pwd);
		$data = array(
				'user_id'       =>$userid,
				'password'      =>$password,
				);
		$changepassword=$this->tableGateway->update($data, array('user_id' => $data['user_id']));
		return 	$changepassword;			
	}
	public function getcountries($uId)
    {
	    $select = $this->tableGateway->getSql()->select();
		$select->join('country', 'country.country_id=users_new.country',array('*'),'left');
		$select->where('users_new.user_id="'.$uId.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();
        return $row;
	}
	public function getallusers()
	{
		$select = $this->tableGateway->getSql()->select();			
		$select->where('user_type="user"');		
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function getallCombinations($adminId){
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array(new Expression('count(*) as countUsers'),'*'));
		$select->join('admin_combinations', new Expression('admin_combinations.user_id=users_new.parent_user_id AND admin_combinations.country_id=users_new.country AND admin_combinations.industry_id=users_new.industry'),array('country_id','industry_id','combination_id'),'left');
		$select->join('country', 'country.country_id=users_new.country',array('country_name'),'left');
		$select->join('industry', 'industry.industry_id=users_new.industry',array('industryname'),'left');
		$select->where('admin_combinations.user_id="'.$adminId.'"');
		$select->group(array('users_new.parent_user_id','users_new.country','users_new.industry'));
		$resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
	}
	public function updateAdminId($countryId,$industryId,$fromAdminId,$toAdminId){
		$data = array(
			'parent_user_id'      	=>$toAdminId,
		);
		$result=$this->tableGateway->update($data, array('parent_user_id' => $fromAdminId,'country' => $countryId,'industry' => $industryId));
		$select = $this->tableGateway->getSql()->select();
		$select->where('industry="'.$industryId.'"');
		$select->where('country="'.$countryId.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return 	$resultSet;			
	}
	public function addNewAdmin($companyname,$emailcheck,$pnumber,$upassword)
    {
		if(isset($_SESSION['user']['user_type']) && $_SESSION['user']['user_type']=='superadmin'){
			$admin_user_id= $_SESSION['user']['user_id'];
		}
		$password=md5($upassword);		
		$data = array(
			'company_name' 	  	       => $companyname,
			'email' 		           => $emailcheck,
			'password' 		           => $password,
			'phone' 		           => $pnumber,  		
			'user_type' 			   => 'admin',
            'u_status'			       => 1,
			'parent_user_id' 		   => $admin_user_id, 			
		);	
		$this->tableGateway->insert($data);	
		return $this->tableGateway->lastInsertValue;	
    }
	public function getcombination_users($ind,$countid,$parentId){
		$select = $this->tableGateway->getSql()->select();				
		$select->where('users_new.industry="'.$ind.'"');
		$select->where('users_new.country="'.$countid.'"');
		$select->where('users_new.parent_user_id="'.$parentId.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function countcombinationUsers($adminid,$countid,$indid){
		$select = $this->tableGateway->getSql()->select();				
		$select->where('users_new.industry="'.$indid.'"');
		$select->where('users_new.country="'.$countid.'"');
		$select->where('users_new.parent_user_id="'.$adminid.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->count();
	}
}