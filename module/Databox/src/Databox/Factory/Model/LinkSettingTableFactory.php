<?php 
namespace Databox\Factory\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\Feature;

use Databox\Model\LinkSetting;
use Databox\Model\LinkSettingTable;

class LinkSettingTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setHydrator(new ObjectProperty());
        $resultSetPrototype->setObjectPrototype(new LinkSetting());
        $tableGateway       = new TableGateway('link_setting', $db,array(),$resultSetPrototype);
        $table              = new LinkSettingTable($tableGateway);
        return $table;
    }
}