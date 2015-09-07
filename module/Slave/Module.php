<?php
namespace Slave;
use Slave\Model\Slave;
use Slave\Model\SlaveTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Slave\Factory\Model\SlaveAdapter;
use Zend\Db\TableGateway\Feature;
class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
	public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    public function getServiceConfig()
    {
        return array(
            'factories' 					=> 	array(
                'Slave\Model\SlaveTable' 	=>  function($sm) {
					$tableGateway 			= 	$sm->get('SlaveTableGateway');
                    $table 					= 	new SlaveTable($tableGateway);
                    return $table;
                },
                'SlaveTableGateway' 		=> 	function ($sm) {
                    $dbAdapter 				= 	$sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype 	= 	new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Slave());
                    return new TableGateway('slave', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter1')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
            ),
        );
    }
}