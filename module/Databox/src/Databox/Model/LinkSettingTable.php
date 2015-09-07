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

class LinkSettingTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }

	public function addLinkSetting( $category_link_id,$setting_flexible_id )
    {	
		$data = array(
			'link_id' 	     => $category_link_id, 
			'setting_id' 	 => $setting_flexible_id, 	
			'status' 	     => "1",
			'created_date' 	 => date('Y-m-d H:i:s'), 	
			'updated_date' 	 => date('Y-m-d H:i:s') 	
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }
	
	public function getSettingIds( $linkId )
    {	
		$select = $this->tableGateway->getSql()->select();
		$select->where('link_setting.link_id="'. $linkId .'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}

	public function deleteLinkSetting( $linkId,$settingId )
    {	
		$deleteStatus=$this->tableGateway->delete(array('link_id' => $linkId,'setting_id' => $settingId));
		return $deleteStatus;
	}
	
}