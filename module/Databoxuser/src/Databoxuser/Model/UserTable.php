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

class UserTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }

	public function getCurrentUserId()
    {
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array( 'maxUserId' => new Expression('MAX(user_id)')));
		
		$rowset = $this->tableGateway->selectWith($select);
		$row = $rowset->current();
		if( !$row )
		{
			throw new \Exception("Could not retrieve max User Id.");
		}
		
		return $row;
	}
	
	public function addUser( $userInfo )
    {	
		$data = array(
			'email' 	         => $userInfo['email'], 
			'password' 	         => $userInfo['password'], 
			'created_date' 	     => date('Y-m-d H:i:s'), 	
			'last_updated_date'  => date('Y-m-d H:i:s'), 	
			'status' 	         => "0"	,
			'type' 	         	 => "1"	,
			'display_name' 	     => $userInfo['displayname'] 
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }
	
	public function updateUser( $userInfo,$userId )
    {	
		$data = array(
			'email' 	          => $userInfo['email'],
			'password' 	          => $userInfo['password'],
			'last_updated_date'   => date('Y-m-d H:i:s'),	
			'display_name' 	      => $userInfo['displayname'],
			'status' 	         => "1",
			'type' 	         	 => "1"	,
		);	
		$row=$this->tableGateway->update($data, array('user_id' => $userId));
		return $row;
	}

	public function checkEmailExists( $userInfo )
    {
		$select = $this->tableGateway->getSql()->select();
		$select->join('user_details', 'user_details.user_id=user.user_id',array('*'),'left');	
		$select->where('user.email="'.$userInfo['email'].'"');
		$select->where('user.password!="'.$userInfo['password'].'"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();
		return $row;
	}
	
	public function checkUserExists( $userInfo )
    {
		$validStatus = 1;
		$deactivatedStatus = 2;
		$type = 1;
		
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array( "u_user_id" => 'user_id',"email" => 'email',"display_name" => 'display_name',"userStatus" => 'status'));
		$select->join('user_details', 'user_details.user_id=user.user_id',array('*'),'left');	
		$select->where('user.email="'.$userInfo['email'].'"');
		$select->where('user.password="'.$userInfo['password'].'"');
		$select->where('user.type="'.$type.'"');
		$select->where->equalTo( 'user.status',$validStatus );
		$select->where->OR->equalTo( 'user.status',$deactivatedStatus );
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();
		return $row;
	}
	public function getUser( $userId )
    {
		$select = $this->tableGateway->getSql()->select();
		$select->join('user_details', 'user_details.user_id=user.user_id',array('*'),'left');	
		$select->where('user.user_id="'.$userId.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();
		return $row;
	}
	public function updateAccount( $user )
    {
		if(isset($user['password'])){
			if($user['password'] !=""){
				$datap = array(
					'password' 	          => md5($user['password']),
				);
				$roww=$this->tableGateway->update($datap, array('user_id' => $user['userId']));			
			}
		}
		$data = array(
			'last_updated_date'   => date('Y-m-d H:i:s'),	
			'display_name' 	      => $user['display_name'],
		);	
		$row=$this->tableGateway->update($data, array('user_id' => $user['userId']));
		return $row;
	}
	public function changeAccountStatus( $user )
    {
		$data = array(
			'last_updated_date'   	=> date('Y-m-d H:i:s'),	
			'status' 	      		=> $user['status'],
			'type' 	         	 	=> "1"	,
		);	
		$row=$this->tableGateway->update($data, array('user_id' => $user['userId']));
		return $row;
	}
	public function checkAdminEmailExists( $userInfo )
    {
		$select = $this->tableGateway->getSql()->select();
		$select->join('user_details', 'user_details.user_id=user.user_id',array('*'),'left');
		$select->where('email="'.$userInfo['email'].'"');
		$select->where('password="'.$userInfo['password'].'"');
		$select->where('type="0"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet;
		return $row;
	}
	public function getUsersSet($offset,$limit)
    {
		$select = $this->tableGateway->getSql()->select();
		$select->join('user_details', 'user_details.user_id=user.user_id',array('montage_hash_name'),'left');
		$select->where('user.type=1');
		if($offset){
			$select->where('user.status=1')->limit((int)$limit)->offset((int)$offset);
		}else{
			$select->where('user.status=1')->limit((int)$limit)->offset((int)$offset);
		}
		
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet;
		return $row;
	}

	public function getAllUsers()
    {
		$select = $this->tableGateway->getSql()->select();
		$select->join('user_details', 'user_details.user_id=user.user_id',array('montage_hash_name'),'left');
		$select->where('user.type=1');
		$select->where('user.status=1');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet;
		return $row;
	}


	public function getSearchUsers($search,$frnds)
    {
		$userId=$_SESSION['usersinfo']->userId;
		$select = $this->tableGateway->getSql()->select();
		$select->where('type=1');
		$select->where('status=1');
		$select->where->like( 'display_name', '%' . $search . '%' );
		if($userId!=""){
			$select->where('user.user_id NOT IN ('.$userId.')');
		}	
		if($frnds!=""){
			$select->where('user.user_id NOT IN ('.$frnds.')');
		}		
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet;
		return $row;
	}
	public function getTotalAllUsers()
    {
		$select = $this->tableGateway->getSql()->select();
		$select->join('user_details', 'user_details.user_id=user.user_id',array('montage_hash_name'),'left');
		$select->where('user.type=1');
		$select->where('user.status=1');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet;
		return $row;
	}
	public function getMontageName($user_id)
    {
		$select = $this->tableGateway->getSql()->select();
		$select->join('user_details', 'user_details.user_id=user.user_id',array('montage_hash_name'),'left');
		$select->where('user.user_id="'.$user_id.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet;
		return $row;
	}
	
	public function checkMontageOrderExists( $userId,$montagePriorOrder )
    {
		$select = $this->tableGateway->getSql()->select();
		$select->where('user.montage_prior_order="' . $montagePriorOrder . '"');
		$select->where( 'user.user_id!="'. $userId .'"' );
		$resultSet = $this->tableGateway->selectWith($select);
		// echo "<pre>";print_r($resultSet);exit;
		return $resultSet;
    }
	
	public function setMontagePriorOrder( $userId,$montagePriorOrder,$carryingUserId )
	{
		// echo $userId;echo "#";echo $montagePriorOrder;echo "#";echo $carryingUserId;echo "#";exit;
		if( $carryingUserId > 0 )
		{
			$data1 = array(
				'montage_prior_order' => 1500,
				'last_updated_date' 	 		=> date('Y-m-d H:i:s') 	
			);	
			$row1=$this->tableGateway->update($data1, array('user_id' => $carryingUserId));
		}

		$data = array(
			'montage_prior_order' => $montagePriorOrder,
			'last_updated_date' 	 	=> date('Y-m-d H:i:s') 	
		);	
		$row=$this->tableGateway->update($data, array('user_id' => $userId));
		return $row;
	}

	public function getUserRow( $userId )
    {
		$select = $this->tableGateway->getSql()->select();
		$select->join('user_details', 'user_details.user_id=user.user_id',array('montage_image'),'left');
		$select->where('user.user_id="'.$userId.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();
		return $row;
	}
	public function checkRegEmailExists( $email )
    {
		$select = $this->tableGateway->getSql()->select();
		$select->where('user.email="'.$email.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet;
		return $row;
	}
	public function resetUserPassword( $userId,$newPassword )
    {
		$data = array(
			'password' 	        => md5($newPassword),
			'last_updated_date' => date('Y-m-d H:i:s')	
		);	
		$row=$this->tableGateway->update($data, array('user_id' => $userId));
		return $row;
	}
	//users count
	public function getUsersCount()
    {
		$select = $this->tableGateway->getSql()->select();
		$select->where('user.type=1');
		$select->where('user.status=1');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = count($resultSet);
		return $row;
	}
	public function checkUserNameExists( $email )
    {
		$select = $this->tableGateway->getSql()->select();
		$select->where('user.display_name="'.$email.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet;
		return $row;
	}
}