<?php 
namespace Databox\Factory\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\Feature;

use Databox\Model\DataboxViews;
use Databox\Model\DataboxViewsTable;

class DataboxViewsTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setHydrator(new ObjectProperty());
        $resultSetPrototype->setObjectPrototype(new DataboxViews());
        $tableGateway       = new TableGateway('databox_views', $db,array(),$resultSetPrototype);
        $table              = new DataboxViewsTable($tableGateway);
        return $table;
    }
}