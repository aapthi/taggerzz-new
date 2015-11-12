<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Databoxuser\Controller\Databoxuser' => 'Databoxuser\Controller\DataboxuserController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'databoxuser' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/databoxuser',
                    'defaults' => array(
                        'controller' => 'Databoxuser\Controller\Databoxuser',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'user-login' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/user-login[/:id]',
							'constraints' => array(
							   'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_~-]*',
							),
                            'defaults' => array(
                                'controller' => 'Databoxuser\Controller\Databoxuser',
                                'action'     => 'userLogin',
                            ),
                        ),
                    ),
                    'email-exists' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/email-exists',
                            'defaults' => array(
                                'controller' => 'Databoxuser\Controller\Databoxuser',
                                'action'     => 'emailExists',
                            ),
                        ),
                    ),
                    'set-redirect-session' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/set-redirect-session',
                            'defaults' => array(
                                'controller' => 'Databoxuser\Controller\Databoxuser',
                                'action'     => 'setRedirectSession',
                            ),
                        ),
                    ),
					'user-verified' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/user-verified[/:id]',
							'constraints' => array(
							   'id'     => '[a-zA-Z][a-zA-Z0-9-=_-]*',
							),
                            'defaults' => array(
                                'controller' => 'Databoxuser\Controller\Databoxuser',
                                'action'     => 'userVerified',
                            ),
                        ),
                    ),
					'skip-to-continue' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/skip-to-continue',
                            'defaults' => array(
                                'controller' => 'Databoxuser\Controller\Databoxuser',
                                'action'     => 'skipToContinue',
                            ),
                        ),
                    ),
					'record-activity-points' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/record-activity-points',
                            'defaults' => array(
                                'controller' => 'Databoxuser\Controller\Databoxuser',
                                'action'     => 'recordActivityPoints',
                            ),
                        ),
                    ),
					'inivite-friend' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/inivite-friend',
                            'defaults' => array(
                                'controller' => 'Databoxuser\Controller\Databoxuser',
                                'action'     => 'iniviteFriend',
                            ),
                        ),
                    ),
                    'user-redirect' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/user-redirect',
                            'defaults' => array(
                                'controller' => 'Databoxuser\Controller\Databoxuser',
                                'action'     => 'userRedirect',
                            ),
                        ),
                    ),
                    'update-profile' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/update-profile',
                            'defaults' => array(
                                'controller' => 'Databoxuser\Controller\Databoxuser',
                                'action'     => 'updateProfile',
                            ),
                        ),
                    ),
                    'email-login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/email-login',
                            'defaults' => array(
                                'controller' => 'Databoxuser\Controller\Databoxuser',
                                'action'     => 'emailLogin',
                            ),
                        ),
                    ),
                    'login-step-two' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login-step-two',
                            'defaults' => array(
                                'controller' => 'Databoxuser\Controller\Databoxuser',
                                'action'     => 'loginStepTwo',
                            ),
                        ),
                    ),
                    'reset-user-password' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/reset-user-password',
                            'defaults' => array(
                                'controller' => 'Databoxuser\Controller\Databoxuser',
                                'action'     => 'resetUserPassword',
                            ),
                        ),
                    ),
                    'dashboard' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/dashboard',
                            'defaults' => array(
                                'controller' => 'Databoxuser\Controller\Databoxuser',
                                'action'     => 'dashboard',
                            ),
                        ),
                    ),
                ),
            ),
			'accounts' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/accounts',
					'defaults' => array(
						'controller' => 'Databoxuser\Controller\Databoxuser',
						'action'     => 'accounts',
					),
				),
			),
			'change-status-account' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/change-status-account',
					'defaults' => array(
						'controller' => 'Databoxuser\Controller\Databoxuser',
						'action'     => 'changeStatusAccount',
					),
				),
			),
			'change-message-status' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/change-message-status',
					'defaults' => array(
						'controller' => 'Databoxuser\Controller\Databoxuser',
						'action'     => 'changeMessageStatus',
					),
				),
			),
			'show-message-content' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/show-message-content',
					'defaults' => array(
						'controller' => 'Databoxuser\Controller\Databoxuser',
						'action'     => 'showMessageContent',
					),
				),
			),
			'insert-message' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/insert-message',
					'defaults' => array(
						'controller' => 'Databoxuser\Controller\Databoxuser',
						'action'     => 'insertMessage',
					),
				),
			),
			'get-email-details' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/get-email-details',
					'defaults' => array(
						'controller' => 'Databoxuser\Controller\Databoxuser',
						'action'     => 'getEmailDetails',
					),
				),
			),
			'insert-blocked-user' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/insert-blocked-user',
					'defaults' => array(
						'controller' => 'Databoxuser\Controller\Databoxuser',
						'action'     => 'insertBlockedUser',
					),
				),
			),
			'check-blocked-user' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/check-blocked-user',
					'defaults' => array(
						'controller' => 'Databoxuser\Controller\Databoxuser',
						'action'     => 'checkBlockedUser',
					),
				),
			),
			'all-users-names-ajax' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/all-users-names-ajax',
					'defaults' => array(
						'controller' => 'Databoxuser\Controller\Databoxuser',
						'action'     => 'allUsersNamesAjax',
					),
				),
			),
			'forgot-password' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/forgot-password[/:id]',
					'constraints' => array(	
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'Databoxuser\Controller\Databoxuser',
						'action'     => 'forgotPassword',
					),
				),
			),
			'reset-password' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/reset-password[/:id]',
					'constraints' => array(	
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'Databoxuser\Controller\Databoxuser',
						'action'     => 'resetPassword',
					),
				),
			),
			'update-hinting' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/update-hinting[/:id]',
					'constraints' => array(	
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'Databoxuser\Controller\Databoxuser',
						'action'     => 'updateHinting',
					),
				),
			),
        ),
	),     
    'view_manager' => array(
        'template_path_stack' => array(
            'databoxuser' => __DIR__ . '/../view',
        ),
		'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
	
);
?>