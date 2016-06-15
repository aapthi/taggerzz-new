<?php 
namespace Databoxuser\Factory\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\Feature;

use Databoxuser\Model\rechargeOrders;
use Databoxuser\Model\rechargeOrdersTable;

class rechargeOrdersTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setHydrator(new ObjectProperty());
        $resultSetPrototype->setObjectPrototype(new rechargeOrders());
        $tableGateway       = new TableGateway('recharge_orders', $db,array(),$resultSetPrototype);
        $table              = new rechargeOrdersTable($tableGateway);
        return $table;
    }
}