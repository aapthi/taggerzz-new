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

class SettingFlexibleTypeTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }

	public function addGridRow( $x_coordinate,$y_coordinate,$roww,$columnn,$height_width )
    {	
		$data = array(
			'x_coordinate' 	     => $x_coordinate, 
			'y_coordinate' 	     => $y_coordinate, 
			'roww' 	     		 => $roww, 
			'columnn' 	     	 => $columnn, 
			'height_width' 	     => $height_width, 
			'status' 	         => "1",	
			'created_date' 	     => date('Y-m-d H:i:s'), 	
			'updated_date' 	     => date('Y-m-d H:i:s') 	
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }

	public function updateGrid( $gridSettings )
    {	
		$data = array(
			'x_coordinate' 	     => $gridSettings["x_coordinate"], 
			'y_coordinate' 	     => $gridSettings["y_coordinate"], 
			'roww' 	     		 => $gridSettings["roww"], 
			'columnn' 	     	 => $gridSettings["columnn"], 
			'height_width' 	     => $gridSettings["height_width"], 
			'updated_date' 	     => date('Y-m-d H:i:s') 	
		);	
		$row=$this->tableGateway->update($data, array('setting_flexible_id' => $gridSettings["setting_flexible_id"]));	return $row;
	}
	
	public function deleteSetting( $settingId )
    {	
		$deleteStatus=$this->tableGateway->delete(array('setting_flexible_id' => $settingId));
		return $deleteStatus;
	}

}