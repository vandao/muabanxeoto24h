<?php

error_reporting(E_ALL);

header("X-Powered-By: " . $_SERVER['HTTP_HOST']);
header('Server: ' . $_SERVER['HTTP_HOST']);
header("Access-Control-Allow-Origin: *");

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

if (in_array(APPLICATION_ENV, array('production', 'stagging'))) {
    ini_set('display_errors', 0);
} else {
    ini_set('display_errors', 1);
}

try {

    /**
     * Read the configuration
     */
    $config    = include __DIR__ . "/../app/config/config.php";
    $configEnv = include __DIR__ . "/../app/config/config-" . APPLICATION_ENV . ".php";
    $config->merge($configEnv);

    /**
     * Read auto-loader
     */
    include __DIR__ . "/../app/config/loader.php";

    /**
     * Read services
     */
    include __DIR__ . "/../app/config/services.php";

    /**
     * Read constants
     */
    include __DIR__ . "/../app/config/constants.php";

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
   echo $e->getMessage();
   echo $e->getTraceAsString();
}
