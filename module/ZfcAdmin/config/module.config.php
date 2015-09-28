<?php
return array(
'controller_plugins' => array(
    'invokables' => array(
       'Adminplugin' => 'ZfcAdmin\Controller\Plugin\Adminplugin',
     )
 ),
    'controllers' => array(
        'invokables' => array(
            'ZfcAdmin\Controller\AdminController' => 'ZfcAdmin\Controller\AdminController',
        ),
    ),
    'zfcadmin' => array(
        'use_admin_layout'      => true,
        'admin_layout_template' => 'layout/admin',
    ),
	'myadmin' => array(
        'use_admin_layout'      => true,
        'admin_layout_template' => 'layout/admin',
    ),
    'navigation' => array(
        'admin' => array(),
    ),

    'router' => array(
        'routes' => array(
            'zfcadmin' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        'controller' => 'ZfcAdmin\Controller\AdminController',
                        'action'     => 'index',
                    ),
                ),
				'may_terminate' => true,
				'child_routes' => array(
                    'admin-login' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '[/:action]',
                            'constraints' => array(
								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     => '[a-zA-Z0-9][a-zA-Z0-9_-]*',
							),
							'defaults' => array(
                                'controller' => 'ZfcAdmin\Controller\AdminController',
                                'action'     => 'adminLogin',
                            ),
                        ),
                    ),
					'admin-logout' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/admin-logout',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'adminLogout',
							),
						),
					),
					'administrator-settings' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/administrator-settings',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'administratorSettings',
							),
						),
					),
					'admin-email-exists' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/admin-email-exists',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'adminEmailExists',
							),
						),
					),
					'ajax-data' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/ajax-data',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'ajaxData',
							),
						),
					),
					'set-databox-prior-order' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/set-databox-prior-order',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'setDataboxPriorOrder',
							),
						),
					),
					'set-highlight-prior-order' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/set-highlight-prior-order',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'setHighlightPriorOrder',
							),
						),
					),
					'check-prior-order-exists' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/check-prior-order-exists',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'checkExistingOrder',
							),
						),
					),
					'check-montage-order-exists' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/check-montage-order-exists',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'checkMontageOrder',
							),
						),
					),
					'set-montage-prior-order' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/set-montage-prior-order',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'setMontagePriorOrder',
							),
						),
					),
					'remove-databox' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/remove-databox',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'removeDatabox',
							),
						),
					),
					'hide-databox' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/hide-databox',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'hideDatabox',
							),
						),
					),
					'append-ajax-users' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/append-ajax-users',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'appendAjaxUsers',
							),
						),
					),
					'search-ajax-users' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/search-ajax-users',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'searchAjaxUsers',
							),
						),
					),
					'update-user-status' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/update-user-status',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'updateUserStatus',
							),
						),
					),
					'contact-admin' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/contact-admin',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'contactAdmin',
							),
						),
					),
					'cust-report' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/cust-report',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'custReport',
							),
						),
					),
					'search-ajax-databoxs' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/search-ajax-databoxs',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'searchAjaxDataboxs',
							),
						),
					),
					'ajax-comments' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/ajax-comments',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'ajaxComments',
							),
						),
					),
					'delete-comment' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/delete-comment',
							'defaults' => array(
								'controller' => 'ZfcAdmin\Controller\AdminController',
								'action'     => 'deleteComment',
							),
						),
					),
                ),				
            ),	
        ),
    ),    
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
		'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
