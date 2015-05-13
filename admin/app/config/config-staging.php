<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'password',
        'dbname'      => 'learning_english',
    ),
    'url' => array(
        'frontend' => 'http://learning.englishcrush.com/',
        'backend'  => 'http://learning.admin.affiliate.englishcrush.com/',
        'api'      => 'http://learning.api.affiliate.englishcrush.com/',
    )
));
