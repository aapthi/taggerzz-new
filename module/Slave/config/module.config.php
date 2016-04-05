<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Slave\Controller\Slave' => 'Slave\Controller\SlaveController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'slave' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/slave[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Slave\Controller\Slave',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);