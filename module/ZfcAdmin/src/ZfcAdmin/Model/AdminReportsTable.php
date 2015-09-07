<?php
namespace ZfcAdmin\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;

class AdminReportsTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	public function addReport($u_id,$reports_data,$ip)
	{
		$data = array(
			'category_id' 	        => $reports_data['categoryId'], 
			'report_content' 	    => $reports_data['content'], 
			'reported_user_id' 	    => $reports_data['userID'], 	
			'reported_by_user_id' 	=> $u_id,	
			'reported_by_ip' 	    => $ip,
			'status' 			    => '0',
			'created_date' 			=> date('Y-m-d H:i:s'),
			'updated_date' 		    => date('Y-m-d H:i:s')
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
	}
	public function getAdminUserReports()
	{
		$content_type = 1;
		$select = $this->tableGateway->getSql()->select();
		$select->join('user_categories', 'admin_reports.category_id=user_categories.category_id',array('user_hashname'),'left');
		$select->join(array('ua' => 'user'), 'user_categories.user_id=ua.user_id',array('reportedUserDName'=>'display_name'),'left');
		$select->join(array('ub' => 'user'), 'admin_reports.reported_by_user_id=ub.user_id',array('reportedByUserDName'=>'display_name'),'left');
		$select->where('admin_reports.content_type="'.$content_type.'"');
		$select->order( 'report_id' );
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function updateReport($reports_data)
	{
		$data = array(
			'report_content' 	    => $reports_data['content'], 
			'updated_date' 		    => date('Y-m-d H:i:s')
		);		
		$row=$this->tableGateway->update($data, array('report_id' => $reports_data['reportId']));
		return $row;
	}
	
	public function addContact($u_id,$contact_data,$ip,$loginStatus)
	{
		if( $loginStatus == 1 )
		{
			$data = array(
				'mail_subject' 	        => $contact_data['contactSubject'], 
				'mail_content' 	 	    => $contact_data['contactContent'], 
				'reported_by_user_id' 	=> $u_id,	
				'status' 			    => '0',
				'content_type'		    => '2',
				'created_date' 			=> date('Y-m-d H:i:s'),
				'updated_date' 		    => date('Y-m-d H:i:s')
			);	
		}
		else
		{
			$data = array(
				'mail_subject' 	        => $contact_data['contactSubject'], 
				'mail_content' 		    => $contact_data['contactContent'], 
				'reported_by_ip' 	    => $ip,
				'status' 			    => '0',
				'content_type'		    => '2',
				'created_date' 			=> date('Y-m-d H:i:s'),
				'updated_date' 		    => date('Y-m-d H:i:s')
			);	
		}
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
	}
	public function getContactContents()
	{
		$content_type = 2;
		$select = $this->tableGateway->getSql()->select();
		$select->join(array('ub' => 'user'), 'admin_reports.reported_by_user_id=ub.user_id',array('reportedByUserDName'=>'display_name'),'left');
		$select->where('admin_reports.content_type="'.$content_type.'"');
		$select->order( 'report_id' );
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
}