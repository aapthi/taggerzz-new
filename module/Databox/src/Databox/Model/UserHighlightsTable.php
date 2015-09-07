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

class UserHighlightsTable 
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }

	public function addUserHighlight( $userHghltDetails )
    {
		$userId = 0;
		if( isset($_SESSION['usersinfo']) )
		{
			$userId = $_SESSION['usersinfo']->userId;
		}
		$data = array(
			'user_id' 	     => $userId, 
			'hightlight_image' 	 => $userHghltDetails["highlightImage"], 
			'highlight_hashtag' 	 => $userHghltDetails["highlightHashTag"], 
			'highlight_title' 	 => $userHghltDetails["highlightTitle"], 
			'setting_id' 	 => $userHghltDetails["settingId"], 
			'highlight_keywords' 	 => $userHghltDetails["highlightKeywords"], 
			'created_date' 	 => date('Y-m-d H:i:s'), 	
			'updated_date' 	 => date('Y-m-d H:i:s'), 	
			'status' 	     => "1"	
		);	
		
		$this->tableGateway->insert($data);		
		return $this->tableGateway->lastInsertValue;
    }

	public function checkPublicHashtag( $publicHashtag )
    {
		$catPrivacy = 1;
		
		$select = $this->tableGateway->getSql()->select();
		$select->where( 'user_hashname="'. $publicHashtag .'"' );
		$select->where( 'category_type="'. $catPrivacy .'"' );
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->count();
    }
}