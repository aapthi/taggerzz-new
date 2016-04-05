<?php
return array(
    'controllers' => array(
        'factories' => array(
            'ScnSocialAuth-HybridAuth' => 'ScnSocialAuth\Service\HybridAuthControllerFactory',
            'ScnSocialAuth-User' => 'ScnSocialAuth\Service\UserControllerFactory',
        ),
		'invokables' => array(
            'Getcontacts' => 'ScnSocialAuth\Controller\GetcontactsController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'scnsocialauthprovider' => 'ScnSocialAuth\Controller\Plugin\ScnSocialAuthProvider',
        ),
    ),
    'router' => array(
        'routes' => array(
            'scn-social-auth-hauth' => array(
                'type'    => 'Literal',
                'priority' => 2000,
                'options' => array(
                    'route' => '/scn-social-auth/hauth',
                    'defaults' => array(
                        'controller' => 'ScnSocialAuth-HybridAuth',
                        'action'     => 'index',
                    ),
                ),
            ),
			'unsubscribe' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/unsubscribe[/:id]',
							'constraints' => array(
								'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
                            'defaults' => array(
                                'controller' => 'ScnSocialAuth-User',
                                'action'     => 'unsubscribe',
                            ),
                        ),
                    ),
			'scn-social-auth-contacts' => array(
                'type'    => 'Literal',
                'priority' => 2000,
                'options' => array(
                    'route' => '/contacts',
                    'defaults' => array(
                        'controller' => 'Getcontacts',
                        'action'     => 'friendscontacts',
                    ),
                ),
            ),
			'invitefriends' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/invitefriends[/:id]',
							'constraints' => array(
								'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
                            'defaults' => array(
                                'controller' => 'ScnSocialAuth-User',
                                'action'     => 'invitefriends',
                            ),
                        ),
                    ),
				'scnauth' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/scnauth[/:action][/:id]',
							'constraints' => array(
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
                            'defaults' => array(
                                'controller' => 'ScnSocialAuth-User',
                                'action'     => 'index',
                            ),
                        ),
                    ),
			'static' => array(
                'type'    => 'segment',
                'priority' => 2000,
                'options' => array(
                    'route' => '/user[/:action][/:id]',
					'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'zfcuser',
                        'action'     => 'index',
                    ),
                ),
            ),
            'scn-social-auth-user' => array(
                'type' => 'Literal',
                'priority' => 2000,
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'zfcuser',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'authenticate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/authenticate',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'authenticate',
                            ),
                        ),
                    ),
					'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'ScnSocialAuth-User',
                                'action'     => 'login',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'provider' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:provider',
                                    'constraints' => array(
                                        'provider' => '[a-zA-Z][a-zA-Z0-9_-]+',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'ScnSocialAuth-User',
                                        'action' => 'provider-login',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'ScnSocialAuth-User',
                                'action'     => 'logout',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'ScnSocialAuth-User',
                                'action'     => 'register',
                            ),
                        ),
                    ),
                    'add-provider' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/add-provider',
                            'defaults' => array(
                                'controller' => 'ScnSocialAuth-User',
                                'action'     => 'add-provider',
                            ),
                        ),
                        'child_routes' => array(
                            'provider' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:provider',
                                    'constraints' => array(
                                        'provider' => '[a-zA-Z][a-zA-Z0-9_-]+',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'ScnSocialAuth_ZendDbAdapter' => 'Zend\Db\Adapter\Adapter',
            'ScnSocialAuth_ZendSessionManager' => 'Zend\Session\SessionManager',
        ),
        'factories' => array(
            'HybridAuth' => 'ScnSocialAuth\Service\HybridAuthFactory',
            'ScnSocialAuth-ModuleOptions' => 'ScnSocialAuth\Service\ModuleOptionsFactory',
            'ScnSocialAuth-UserProviderMapper' => 'ScnSocialAuth\Service\UserProviderMapperFactory',
            'ScnSocialAuth\Authentication\Adapter\HybridAuth' => 'ScnSocialAuth\Service\HybridAuthAdapterFactory',
            'ZfcUser\Authentication\Adapter\AdapterChain' => 'ScnSocialAuth\Service\AuthenticationAdapterChainFactory',
			'Business\Model\PendingmailTableFactory' => 'Business\Factory\Model\PendingmailTableFactory',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'socialSignInButton' => 'ScnSocialAuth\View\Helper\SocialSignInButton',
        ),
        'factories' => array(
            'scnUserProvider'   => 'ScnSocialAuth\Service\UserProviderViewHelperFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'scn-social-auth' => __DIR__ . '/../view'
        ),
    ),
);
