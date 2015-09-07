<?php 
namespace Databox\Factory\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\Feature;

use Databox\Model\SearchCategoriesList;
use Databox\Model\SearchCategoriesListTable;

class SearchCategoriesListTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setHydrator(new ObjectProperty());
        $resultSetPrototype->setObjectPrototype(new SearchCategoriesList());
        $tableGateway       = new TableGateway('search_categories_list', $db,array(),$resultSetPrototype);
        $table              = new SearchCategoriesListTable($tableGateway);
        return $table;
    }
}