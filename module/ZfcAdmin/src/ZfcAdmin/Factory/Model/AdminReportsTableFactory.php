<?php 
namespace ZfcAdmin\Factory\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\Feature;

use ZfcAdmin\Model\AdminReports;
use ZfcAdmin\Model\AdminReportsTable;

class AdminReportsTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setHydrator(new ObjectProperty());
        $resultSetPrototype->setObjectPrototype(new AdminReports());
        $tableGateway       = new TableGateway('admin_reports', $db,array(),$resultSetPrototype);
        $table              = new AdminReportsTable($tableGateway);
        return $table;
    }
}