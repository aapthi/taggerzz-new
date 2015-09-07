<?php

namespace Databox;
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


use Databox\Model\Category;
use Databox\Model\CategoryTable;
use Databox\Model\UserCategories;
use Databox\Model\UserCategoriesTable;
use Databox\Model\CategoryLinks;
use Databox\Model\CategoryLinksTable;
use Databox\Model\LinkDetails;
use Databox\Model\LinkDetailsTable;
use Databox\Model\LinkSetting;
use Databox\Model\LinkSettingTable;
use Databox\Model\SettingFlexibleType;
use Databox\Model\SettingFlexibleTypeTable;
use Databox\Model\UserHighlights;
use Databox\Model\UserHighlightsTable;
use Databox\Model\RelevanceWorthVote;
use Databox\Model\RelevanceWorthVoteTable;
use Databox\Model\CatImage;
use Databox\Model\CatImageTable;
use Databox\Model\JsPlumbGrid;
use Databox\Model\JsPlumbGridTable;
use Databox\Model\SearchCategoriesList;
use Databox\Model\SearchCategoriesListTable;
use Databox\Model\DataboxViews;
use Databox\Model\DataboxViewsTable;
use Databox\Model\UserMessages;
use Databox\Model\UserMessagesTable;
use Databox\Model\BlockUser;
use Databox\Model\BlockUserTable;
use Databox\Model\UserCollections;
use Databox\Model\UserCollectionsTable;

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
				__DIR__ . '/class_map.php',
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
            	'Databox\Model\CategoryFactory'=>'Databox\Factory\Model\CategoryTableFactory',			
            	'Databox\Model\UserCategoriesFactory'=>'Databox\Factory\Model\UserCategoriesTableFactory',			
            	'Databox\Model\CategoryLinksFactory'=>'Databox\Factory\Model\CategoryLinksTableFactory',			
            	'Databox\Model\LinkDetailsFactory'=>'Databox\Factory\Model\LinkDetailsTableFactory',			
            	'Databox\Model\LinkSettingFactory'=>'Databox\Factory\Model\LinkSettingTableFactory',			
            	'Databox\Model\SettingFlexibleTypeFactory'=>'Databox\Factory\Model\SettingFlexibleTypeTableFactory',			
            	'Databox\Model\UserHighlightsFactory'=>'Databox\Factory\Model\UserHighlightsTableFactory',			
            	'Databox\Model\RelevanceWorthVoteFactory'=>'Databox\Factory\Model\RelevanceWorthVoteTableFactory',			
            	'Databox\Model\CatImageFactory'=>'Databox\Factory\Model\CatImageTableFactory',			
            	'Databox\Model\JsPlumbGridFactory'=>'Databox\Factory\Model\JsPlumbGridTableFactory',			
            	'Databox\Model\SearchCategoriesListFactory'=>'Databox\Factory\Model\SearchCategoriesListTableFactory',			
            	'Databox\Model\DataboxViewsFactory'=>'Databox\Factory\Model\DataboxViewsTableFactory',		
            	'Databox\Model\UserMessagesFactory'=>'Databox\Factory\Model\UserMessagesTableFactory',		
            	'Databox\Model\BlockUserFactory'=>'Databox\Factory\Model\BlockUserTableFactory',		
            	'Databox\Model\UserCollectionsFactory'=>'Databox\Factory\Model\UserCollectionsTableFactory',		
			),			
        );
    }
}