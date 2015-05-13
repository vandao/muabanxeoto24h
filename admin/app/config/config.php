<?php
return new \Phalcon\Config(array(
    'database' => array(
        'adapter'          => 'Mysql',
        'host'             => 'localhost',
        'username'         => 'root',
        'password'         => 'password',
        'dbname'           => 'muabanxeoto',
        //'dbnameEmailQueue' => 'email_queue',
    ),
    'application' => array(
        'controllersDir'   => __DIR__ . '/../../app/controllers/',
        'modelsDir'        => __DIR__ . '/../../app/models/DbTable', //DbTable auto generated form Phalcon DevTools, do not touch it
        'modelsRealDir'    => __DIR__ . '/../../app/models/',
        'viewsDir'         => __DIR__ . '/../../app/views/',
        'formsDir'         => __DIR__ . '/../../app/forms/',
        'pluginsDir'       => __DIR__ . '/../../app/plugins/',
        'libraryDir'       => __DIR__ . '/../../app/library/',
        'cacheDir'         => __DIR__ . '/../../app/cache/',
        'baseUri'          => '/',
    ),
    'upload' => array(
        'dataDir'      => 'D:/web/data/muabanxeoto/',
        'uploadDir'    => 'D:/web/data/muabanxeoto/uploads',
        'uploadUri'    => '/uploads',
        'tmpDir'       => 'tmp',
    ),
    'url' => array(
        'frontend' => 'http://dev.muabanxeoto24h.com/',
        'backend'  => 'http://dev.admin.muabanxeoto24h.com/',
        'api'      => 'http://dev.api.muabanxeoto24h.com/',
    ),
    'permission' => array(
        'superAdminId' => 1,
        'defaultAllow' => array(
            'permission' => array(
                'index'
            ),
            'index' => array(
                'index'
            ),
            'session' => array(
                'logout'
            ),
        )
    )
));
