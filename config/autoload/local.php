<?php

return array(
    'db'                 =>     array(
        'dsn'             =>    'mysql:dbname=taggerzz;host=localhost',
        'username'         =>     'root',
        'password'         =>     '',
    ),
    'slave1' => array(
        'dsn'              =>     'mysql:dbname=taggerzz1;host=localhost',
        'username'         =>     'root',
        'password'         =>     '',
    ),
    'slave2'             =>     array(
        'dsn'              =>     'mysql:dbname=taggerzz2;host=localhost',
        'username'         =>     'root',
        'password'         =>     '',
    ),
    'slave3'             =>     array(
        'dsn'              =>     'mysql:dbname=taggerzz3;host=localhost',
        'username'         =>     'root',
        'password'         =>     '',
    ),
    'urls'                 =>     array(
        'baseUrl'         =>     'http://localhost/taggerzz-new/trunk',
        'basePath'         =>    'http://localhost/taggerzz-new/trunk/public',
        'imagesUrl'        =>    '#',
    ),
    'service_manager'     =>     array(
        'factories'     =>     array(  
        ),
    ),
);

?>
