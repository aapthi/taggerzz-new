<?php
namespace Products\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;

class ProductsnewTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	public function addProducts($product,$superadmin_id,$parent_id,$user_country)
    {	
		if(isset($_SESSION['user']['user_type']) && $_SESSION['user']['user_type']=="superadmin"){			
			$superadmin_user_id=$_SESSION['user']['user_id'];
			$admin_user_id=$product['admin_user_id'];
			$userId=$product['user_user_id'];
			$status=1;
		}else if($_SESSION['user']['user_type']=="admin"){
			$superadmin_user_id=$superadmin_id;
			$admin_user_id=$_SESSION['user']['user_id'];
			$userId=$product['user_user_id'];
			$status=1;
		}else if($_SESSION['user']['user_type']=="user"){			
			$superadmin_user_id=$superadmin_id;
			$admin_user_id=$parent_id;
			$userId=$_SESSION['user']['user_id'];
			$status=0;
		}
		if($product['type']=='bid'){
			$min_price=$product['tshm'];
		}else{
			$min_price="";
		}
		$data = array(
			'brandcode' 	     => $product['brand'], 
			'genericname' 	     => $product['generic'], 	
			'fuction' 	         => $product['fuction'],	
			'manufacturer' 	     => $product['manufacture'],
			'origin_country' 	 => $product['origin'],
			'quntity' 	         => $product['qty'],
			'unites' 			 => $product['units'],
			'year_of_manufacture'=> $product['year'],
			'package' 			 => $product['packing'],
			'per'				 => $product['per'],
			'suggested_price'    => $product['tsh'],
			'min_price' 		 => $min_price,
			'user_id'            => $userId,
			'category_id' 		 => $product['category_id'],
			'type' 		 		 => $product['type'],
			'p_status' 		     => $status,
			'admin_user_id' 	 => $admin_user_id,
			'superadmin_user_id' => $superadmin_user_id,
			'saleing' 		     => $product['selling'],
			'user_currency'      => $product['user_currency'],
			'selling_country'    => $user_country
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }
	public function getallProducts($countryId,$industryId,$categoryId){
		if(isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id']!=""){
			$userid=$_SESSION['user']['user_id'];
		}else{
			$userid="";
		}
		$select = $this->tableGateway->getSql()->select();
		if($_SESSION['user']['user_type']=="user"){
			if($countryId=="all" && $industryId=="all" && $categoryId=="all"){	
				$select->where('productsnew.user_id!="'.$userid.'"');
			}else if($countryId!="all" && $industryId=="all" && $categoryId=="all"){
				$select->where('productsnew.selling_country="'.$countryId.'"');	
				$select->where('productsnew.user_id!="'.$userid.'"');				
			}else if($countryId!="all" && $industryId!="all" && $categoryId!="all"){			
				$select->where('productsnew.selling_country="'.$countryId.'"');
				$select->where('productsnew.category_id="'.$categoryId.'"');
				$select->where('productsnew.user_id!="'.$userid.'"');
			}
		}else if($_SESSION['user']['user_type']=="admin"){
			if($countryId=="all" && $industryId=="all" && $categoryId=="all"){	
				//$select->where('productsnew.selling_country="'.$countryId.'"');
				$select->where('productsnew.admin_user_id="'.$userid.'"');
			}else if($countryId!="all" && $industryId=="all" && $categoryId=="all"){
				$select->where('productsnew.selling_country="'.$countryId.'"');
				$select->where('productsnew.admin_user_id="'.$userid.'"');
			}else if($countryId!="all" && $industryId!="all" && $categoryId!="all"){			
				$select->where('productsnew.selling_country="'.$countryId.'"');
				$select->where('productsnew.category_id="'.$categoryId.'"');
				$select->where('productsnew.admin_user_id="'.$userid.'"');
			}
		}else if($_SESSION['user']['user_type']=="superadmin"){
			if($countryId=="all" && $industryId=="all" && $categoryId=="all"){	
				
			}else if($countryId!="all" && $industryId=="all" && $categoryId=="all"){
				$select->where('productsnew.selling_country="'.$countryId.'"');						
			}else if($countryId!="all" && $industryId!="all" && $categoryId!="all"){			
				$select->where('productsnew.selling_country="'.$countryId.'"');
				$select->where('productsnew.category_id="'.$categoryId.'"');				
			}
		}		
		$select->where('p_status!=0');		
		$resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;		
	}
	public function getProductsGrid(){
		$select = $this->tableGateway->getSql()->select();
		$select->join('users', 'products.user_id=users.user_id',array('*'),'left');	
		$select->join('offers', 'products.product_id=offers.product_id',array('*'),'left');
		$select->where('products.product_id!=""');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->count();
	}	
	public function deleteproducts($ids){		
		$deleteid=$this->tableGateway->delete(array('(product_id IN ('.$ids.'))'));
		return $deleteid;	
	}
	public function getallbidProducts( ){
	    $select = $this->tableGateway->getSql()->select();		
		$select->where('type="bid"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;		
	}
	public function changeStatus($userId){
		$data = array(
			'p_status'       =>2,
		);
		$result=$this->tableGateway->update($data, array('user_id' => $userId));
		return 	$result;
	}
}