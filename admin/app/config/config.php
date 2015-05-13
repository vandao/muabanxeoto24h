<?php
return new \Phalcon\Config(array(
    'database' => array(
        'adapter'          => 'Mysql',
        'host'             => 'localhost',
        'username'         => 'root',
        'password'         => 'password',
        'dbname'           => 'learning_english',
        'dbnameEmailQueue' => 'email_queue',
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
        'dataDir'      => '/home/web/data/learning-english/',
        'uploadDir'    => '/home/web/data/learning-english/uploads',
        'uploadUri'    => '/uploads',
        'tmpDir'       => 'tmp',
    ),
    'url' => array(
        'frontend' => 'http://learning-english/',
        'backend'  => 'http://learning-english.admin/',
        'api'      => 'http://learning-english.api/',
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
