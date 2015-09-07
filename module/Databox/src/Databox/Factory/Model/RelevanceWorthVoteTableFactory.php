<?php 
namespace Databox\Factory\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\Feature;

use Databox\Model\RelevanceWorthVote;
use Databox\Model\RelevanceWorthVoteTable;

class RelevanceWorthVoteTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setHydrator(new ObjectProperty());
        $resultSetPrototype->setObjectPrototype(new RelevanceWorthVote());
        $tableGateway       = new TableGateway('relevance_worth_vote', $db,array(),$resultSetPrototype);
        $table              = new RelevanceWorthVoteTable($tableGateway);
        return $table;
    }
}