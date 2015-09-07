<?php
$dbParams = array(
    'database'  			=> 	'freshDBprod',
    'username'  			=> 	'freshDBprod',
    'password'  			=> 	'Fresh@2013',
    'hostname'  			=> 	'freshDBprod.db.12018856.hostedresource.com',
);
return array(
    'db' 					=> 	array(
		'dsn'      			=> 	'mysql:dbname=freshDBprod;host=freshDBprod.db.12018856.hostedresource.com',
        'username' 			=> 	'freshDBprod',
        'password' 			=> 	'Fresh@2013',
    ),
    'urls' 					=> 	array(
        'CASURL' 			=> 	'www.yahoo.com',
    ),
	'service_manager' 		=> array(
        'factories' 		=> array(
            'Zend\Db\Adapter\Adapter' 		=> function ($sm) use ($dbParams) {
                return new Zend\Db\Adapter\Adapter(array(
                    'driver'    			=> 	'pdo',
                    'dsn'       			=> 	'mysql:dbname='.$dbParams['database'].';host='.$dbParams['hostname'],
                    'database'  			=> 	$dbParams['database'],
                    'username'  			=> 	$dbParams['username'],
                    'password'  			=> 	$dbParams['password'],
                    'hostname'  			=> 	$dbParams['hostname'],
                ));
            },
        ),
    ),
);