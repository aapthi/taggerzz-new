<?php
namespace ScnSocialAuth;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
	public function getServiceConfig()
    {
        return array(
				'factories' => array(
		'Album\Model\UsersTable' =>  'Album\Factory\Model\UsersTableFactory',
		'Album\Model\FriendscircleTable' =>  'Album\Factory\Model\FriendscircleTableFactory'
		)
		);
	}
}
