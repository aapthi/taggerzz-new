<?php 
namespace Databoxuser\Factory\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\Feature;

use Databoxuser\Model\Invitations;
use Databoxuser\Model\InvitationsTable;

class InvitationsTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setHydrator(new ObjectProperty());
        $resultSetPrototype->setObjectPrototype(new Invitations());
        $tableGateway       = new TableGateway('invitations', $db,array(),$resultSetPrototype);
        $table              = new InvitationsTable($tableGateway);
        return $table;
    }
}