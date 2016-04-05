<?php
$dbParams = array(
    'database'  			=> 	'taggerzz_live',
    'username'  			=> 	'taggerzz_aapthi',
    'password'  			=> 	'aapthitech@1234',
    'hostname'  			=> 	'localhost',
);
return array(
    'db' 					=> 	array(
		'dsn'      			=> 	'mysql:dbname=freshDBprod;host=localhost',
        'username' 			=> 	'taggerzz_aapthi',
        'password' 			=> 	'aapthitech@1234',
    ),
     'urls'                 =>     array(
        'baseUrl'         =>     'https://taggerzz.com',
        'basePath'         =>     'https://taggerzz.com/public',
        'imagesUrl'        =>    '#',
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