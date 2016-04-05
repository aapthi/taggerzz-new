<?php
namespace Slave\Model;
use Zend\Db\TableGateway\TableGateway;
class SlaveTable
{
    protected $tableGateway;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
	public function selectData()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
	public function insertData()
    {
		$data=array(
			'name'			=>	'pratap12222111',
		);
        $this->tableGateway->insert($data);
		return $this->tableGateway->lastInsertValue;
    }
	public function updateData()
    {
		$data=array(
		'name'			=>	'jajjara',
		);
        $row=$this->tableGateway->update($data, array('id' => '1'));
		return $this->tableGateway->lastInsertValue;
    }
	public function deleteData()
    {
        $this->tableGateway->delete(array('id' => '1'));
    }
}