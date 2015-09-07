<?php
return array(
	'controller_plugins' => array(
		'invokables' => array(
		   'Myplugin' => 'Application\Controller\Plugin\Myplugin',
		 )
	 ),
	'view_helpers' 				=> 	array(
		'invokables' 			=> 	array(
			'action' 			=> 	'Eva\View\Helper\Action',
		),  
	),
	'router' 					=> 	array(
		'routes' 				=> 	array(
			'home' 				=> 	array(
				'type' 			=> 	'Zend\Mvc\Router\Http\Literal',
				'options' 		=> 	array(
					'route'    	=> 	'/',
					'defaults' 	=> 	array(
						'controller' 	=> 	'Application\Controller\Index',
						'action'     	=> 	'index',
					),
				),
			),
			'application' 		=> 	array(
				'type'    		=> 	'Literal',
				'options' 		=> 	array(
					'route'    	=> 	'/application',
					'defaults' 	=> 	array(
						'__NAMESPACE__' 	=> 	'Application\Controller',
						'controller'    	=> 	'Index',
						'action'       		=> 	'index',
					),
				),
				'may_terminate' 	=> 	true,
                'child_routes' => array(
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Index',
                                'action'     => 'logout',
                            ),
                        ),
                    ),
					'deactivate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/deactivate',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Index',
                                'action'     => 'deactivate',
                            ),
                        ),
                    ),
				
					'reports' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/reports',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Index',
                                'action'     => 'reports',
                            ),
                        ),
                    ),
                ),
			),
			'member-collections' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/member-collections',
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action'     => 'memberCollections',
					),
				),
			),
			'mainpage' => array(
					'type' => 'Literal',
					'options' => array(
						'route' => '/contentpage',
						'defaults' => array(
							'controller' => 'Application\Controller\Index',
							'action'     => 'mainpage',
						),
					),
				),
				'intro' => array(
					'type' => 'Literal',
					'options' => array(
						'route' => '/intro',
						'defaults' => array(
							'controller' => 'Application\Controller\Index',
							'action'     => 'introductionPage',
						),
					),
				),
			'user-collection' => array(
				'type'    => 'segment',
				'options' => array(
					'route' => '/user-collection[/:id]',
					'constraints' => array(
					   'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_~-]*',
					),
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action'     => 'userCollection',
					),
				),
			),
			'search-hash-names' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/search-hash-names',
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action'     => 'searchHashNames',
					),
				),
			),
			'user-collection-boxes-ajax' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/user-collection-boxes-ajax',
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action'     => 'userCollectionBoxesAjax',
					),
				),
			),
			'user-collection-links' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/user-collection-links',
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action'     => 'userCollectionLinks',
					),
				),
			),
			'display-terms' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/display-terms',
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action'     => 'terms',
					),
				),
			),
			'display-privacy-policy' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/display-privacy-policy',
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action'     => 'privacyPolicy',
					),
				),
			),
			'browser-display' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/browser-display',
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action'     => 'browserDisplay',
					),
				),
			),
			'signup' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/signup',
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action'     => 'signup',
					),
				),
			),
		),
	),
	'service_manager' 			=> 	array(
		'abstract_factories' 	=> 	array(
			'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
			'Zend\Log\LoggerAbstractServiceFactory',
		),
		'aliases' 				=> 	array(
			'translator' 		=> 	'MvcTranslator',
		),
	),
	'translator' 				=> 	array(
		'locale' 				=> 	'en_US',
		'translation_file_patterns' 	=> array(
			array(
				'type'     				=> 	'gettext',
				'base_dir' 				=> 	__DIR__ . '/../language',
				'pattern'  				=> 	'%s.mo',
			),
		),
	),
	'controllers' 					=> 	array(
		'invokables' 				=> 	array(
			'Application\Controller\Index' 	=> 	'Application\Controller\IndexController'
		),
	),
	'view_manager' 						=> 	array(
		'display_not_found_reason' 		=> 	true,
		'display_exceptions'       		=> 	true,
		'doctype'                  		=> 	'HTML5',
		'not_found_template'       		=> 	'error/404',
		'exception_template'       		=> 	'error/index',
		'template_map' => array(
			'layout/layout'           	=> 	__DIR__ . '/../view/layout/layout.phtml',
			'application/index/index' 	=> 	__DIR__ . '/../view/application/index/index.phtml',
			'error/404'               	=> 	__DIR__ . '/../view/error/404.phtml',
			'error/index'             	=>	__DIR__ . '/../view/error/index.phtml',
		),
		'template_path_stack' 			=> array(
			__DIR__ . '/../view',
		),
		'strategies' 					=> array(
			'ViewJsonStrategy',
		),
	),	
);
