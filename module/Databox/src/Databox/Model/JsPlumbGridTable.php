<?php
namespace Databox\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;

class JsPlumbGridTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }

	public function addRow($data)
    {
		$deleteStatus=$this->tableGateway->delete(array('user_category_id' => $data['catId']));
		$data = array( 
			'div_html' 	        		=> $data['DivHtml'],			
			'user_category_id' 	        => $data['catId'],				
			'count_divs' 	        	=> $data['incrId'],
			'user_id' 	        		=> $data['user_id'],
		);
		//echo '<pre>'; print_r($data); exit;
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }
	public function getRow($catId){
		$select = $this->tableGateway->getSql()->select();		
		$select->where('user_category_id="'.$catId.'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	public function deleteCategoryGrid( $userCategoryId )
    {	
		$deleteStatus=$this->tableGateway->delete(array('user_category_id' => $userCategoryId));
		return $deleteStatus;
	}
	
}