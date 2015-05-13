<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'vandao0',
        'dbname'      => 'muabanxeoto',
    ),
    'upload' => array(
        'dataDir'      => '/home/web/data/muabanxeoto/',
        'uploadDir'    => '/home/web/data/muabanxeoto/uploads',
        'uploadUri'    => '/uploads',
        'tmpDir'       => 'tmp',
    ),
    'url' => array(
        'frontend' => 'http://muabanxeoto24h.com/',
        'backend'  => 'http://admin.muabanxeoto24h.com/',
        'api'      => 'http://api.admin.muabanxeoto24h.com/',
    )
));
