<?php 
namespace Databoxuser\Factory\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\Feature;

use Databoxuser\Model\LoginLinkExpired;
use Databoxuser\Model\LoginLinkExpiredTable;

class LoginLinkExpiredTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setHydrator(new ObjectProperty());
        $resultSetPrototype->setObjectPrototype(new LoginLinkExpired());
        $tableGateway       = new TableGateway('login_link_expired', $db,array(),$resultSetPrototype);
        $table              = new LoginLinkExpiredTable($tableGateway);
        return $table;
    }
}