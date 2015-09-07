<?php 
namespace Databox\Factory\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\Feature;

use Databox\Model\SettingFlexibleType;
use Databox\Model\SettingFlexibleTypeTable;

class SettingFlexibleTypeTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setHydrator(new ObjectProperty());
        $resultSetPrototype->setObjectPrototype(new SettingFlexibleType());
        $tableGateway       = new TableGateway('setting_flexible_type', $db,array(),$resultSetPrototype);
        $table              = new SettingFlexibleTypeTable($tableGateway);
        return $table;
    }
}