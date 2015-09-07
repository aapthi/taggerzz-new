<?php 
namespace Databoxuser\Factory\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\Feature;

use Databoxuser\Model\UserDetails;
use Databoxuser\Model\UserDetailsTable;

class UserDetailsTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setHydrator(new ObjectProperty());
        $resultSetPrototype->setObjectPrototype(new UserDetails());
        $tableGateway       = new TableGateway('user_details', $db,array(),$resultSetPrototype);
        $table              = new UserDetailsTable($tableGateway);
        return $table;
    }
}