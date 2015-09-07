<?php 
namespace Databox\Factory\Model;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\Feature;

use Databox\Model\UserCollections;
use Databox\Model\UserCollectionsTable;

class UserCollectionsTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setHydrator(new ObjectProperty());
        $resultSetPrototype->setObjectPrototype(new UserCollections());
        $tableGateway       = new TableGateway('user_collections', $db,array(),$resultSetPrototype);
        $table              = new UserCollectionsTable($tableGateway);
        return $table;
    }
}