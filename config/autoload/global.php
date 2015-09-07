<?php
include( 'public/global/globalvalues.php');
include( 'public/global/globalfunctions.php');
include( 'public/global/labelnames.php');
include( 'public/global/validationmessages.php');
return array(
    'db' 					=>	array(
        'driver'         	=> 	'Pdo',
        'driver_options' 	=> array(
            PDO::MYSQL_ATTR_INIT_COMMAND 	=> 	'SET NAMES \'UTF8\''
        ),
    ),
	'slave1' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=taggerzz1;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
	'slave2' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=taggerzz2;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
	'slave3' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=taggerzz3;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' 		=> 	array(
        'factories' 		=> 	array(
            'Zend\Db\Adapter\Adapter'
							=> 'Zend\Db\Adapter\AdapterServiceFactory',
			'SlaveAdapter1' => 'Slave\Factory\Model\SlaveAdapter1ServiceFactory',
			'SlaveAdapter2' => 'Slave\Factory\Model\SlaveAdapter2ServiceFactory',
			'SlaveAdapter3' => 'Slave\Factory\Model\SlaveAdapter3ServiceFactory',
        ),
    ),
	 'abstract_factories' => array(
				'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
		  ),
);