<?php 
namespace Databox\Factory\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\Feature;

use Databox\Model\CategoryLinks;
use Databox\Model\CategoryLinksTable;

class CategoryLinksTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setHydrator(new ObjectProperty());
        $resultSetPrototype->setObjectPrototype(new CategoryLinks());
        $tableGateway       = new TableGateway('category_links', $db,array(),$resultSetPrototype);
        $table              = new CategoryLinksTable($tableGateway);
        return $table;
    }
}