<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Dashboard\Controller\Dashboard' => 'Dashboard\Controller\DashboardController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'dashboard' => array(
                'type' => 'segment',
                'priority' => 1000,
                'options' => array(
                    'route' => '/dashboard[/:id]',
						'constraints' => array(
						   'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_~-]*',
						),
                    'defaults' => array(
                        'controller' => 'Dashboard\Controller\Dashboard',
                        'action'     => 'index',
                    ),
                ),
			),
			'update-dash-board' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/update-dash-board',
					'defaults' => array(
						'controller' => 'Dashboard\Controller\Dashboard',
						'action'     => 'updateDashBoard',
					),
				),
			),
			'montage' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/montage',
                    'defaults' => array(
                        'controller' => 'Dashboard\Controller\Dashboard',
                        'action'     => 'montage',
                    ),
                ),
            ),
			'montage-hash-name' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/montage-hash-name',
					'defaults' => array(
						'controller' => 'Dashboard\Controller\Dashboard',
						'action'     => 'montageHashName',
					),
				),
			),
			'upload-montage-image' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/upload-montage-image',
					'defaults' => array(
						'controller' => 'Dashboard\Controller\Dashboard',
						'action'     => 'uploadMontageImage',
					),
				),
			),
			'pagination-montage-links' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/pagination-montage-links',
					'defaults' => array(
						'controller' => 'Dashboard\Controller\Dashboard',
						'action'     => 'paginationMontageLinks',
					),
				),
			),
			'montage-lode-ajax' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/montage-lode-ajax',
					'defaults' => array(
						'controller' => 'Dashboard\Controller\Dashboard',
						'action'     => 'montageLodeAjax',
					),
				),
			),
        ),
	),     
    'view_manager' => array(
        'template_path_stack' => array(
            'Dashboard' => __DIR__ . '/../view',
        ),
		'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
	
);
?>