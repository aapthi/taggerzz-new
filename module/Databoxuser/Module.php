<?php
namespace Databoxuser;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature;
use Zend\Loader;
use Zend\EventManager\EventInterface;
use Zend\Mvc\Router\RouteMatch;
use Zend\ModuleManager\ModuleManager;
use Zend\Stdlib\Hydrator\ClassMethods;


use Databoxuser\Model\User;
use Databoxuser\Model\UserTable;
use Databoxuser\Model\UserDetails;
use Databoxuser\Model\UserDetailsTable;
use Databoxuser\Model\ForgotPassword;
use Databoxuser\Model\ForgotPasswordTable;


class Module implements 
	Feature\AutoloaderProviderInterface,
    Feature\ConfigProviderInterface,
    Feature\ServiceProviderInterface
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
            'factories' => array( 
            	'Databoxuser\Model\UserFactory'=>'Databoxuser\Factory\Model\UserTableFactory',			
            	'Databoxuser\Model\UserDetailsFactory'=>'Databoxuser\Factory\Model\UserDetailsTableFactory',		
            	'Databoxuser\Model\LoginLinkExpiredFactory'=>'Databoxuser\Factory\Model\LoginLinkExpiredTableFactory',			
            	'Databoxuser\Model\ForgotPasswordFactory'=>'Databoxuser\Factory\Model\ForgotPasswordTableFactory'			
			),			
        );
    }
}