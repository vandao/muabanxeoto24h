<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ));

            /**
             * Read custom volt functions
             */
            include __DIR__ . "/volt.php";


            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {
    return new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ));
});
$di->set('dbEmailQueue', function () use ($config) {
    return new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbnameEmailQueue
    ));
});

$di->set('collectionManager', function(){
    return new Phalcon\Mvc\Collection\Manager();
}, true);

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function () {
    $session = new SessionAdapter();
    $session->start();

    $stringParams   = explode("?lang=", $_SERVER['REQUEST_URI']);

    $languageCode   = '';
    if (sizeof($stringParams) > 1) {
        $languageCode = end($stringParams);
        $session->set('lang', $languageCode);
    }

    $currentLang = $session->get('lang');

    if (! $currentLang) {
        $systemLanguage = SystemLanguage::findFirst("is_default = 1");
    } else {
        $systemLanguage = SystemLanguage::findFirst(array(
            "conditions"  => "language_code = ?1",
            "bind"        => array(
                    1 => $session->get('lang'),
                )
        ));
    }
    $session->set('lang_id', $systemLanguage->id);
    
    return $session;
});

$di->set('dispatcher', function() use ($di){

    //Obtain the standard eventsManager from the DI
    $eventsManager = $di->getShared('eventsManager');

    //Instantiate the Bootstrap plugin
    $boostrap = new Bootstrap($di);

    //Listen for events produced in the dispatcher using the Bootstrap plugin
    $eventsManager->attach('dispatch', $boostrap);

    $dispatcher = new Phalcon\Mvc\Dispatcher();

    //Bind the eventsManager to the view component
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;

}, true);

/**
 * Access Control List
 */
$di->set('acl', function () {
    return new Acl();
});

$di->set('systemConfig', function() {
    $configs = array();
    foreach (SystemConfig::find() as $config) {
        $configs[$config->key] = $config->value;
    }

    return $configs;
});

$di->set('systemLabel', function() use ($di) {
    $labels = array();
    foreach (SystemLabel::getAllByLanguage() as $label) {
        $labels[$label->label_key] = $label;
    }

    return $labels;
});

$di->setShared('crypt', function() use($di) {
    $systemConfig = $di->getShared('systemConfig');

    $crypt = new \Phalcon\Crypt();
    $crypt->setMode(MCRYPT_MODE_CFB);
    $crypt->setKey($systemConfig['Cookie_Encrypt_Key']);
    return $crypt;
});

$di->set('formatNumber', function() {
    return new FormatNumber();
});

/**
* Label
*/
$di->set('label', function(){
    return new Label();
});

/**
* Menu
*/
$di->set('layout', function(){
    return new Layout();
});

/**
* Pagination
*/
$di->set('pagination', function(){
    return new Pagination();
});

/**
* Search and filter
*/
$di->set('searchAndFilter', function(){
    return new SearchAndFilter();
});

/**
* Button
*/
$di->set('button', function(){
    return new Button();
});

/**
 * Flash service with custom CSS classes
 */
$di->set('flash', function () {
    return new Flash(array(
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info'
    ));
});

$di->set('dateRange', function () {
    return new DateRange();
});

/**
 * Custom authentication component
 */
$di->set('auth', function () {
    return new Auth();
});

/**
 * Access Control List
 */
$di->set('acl', function () {
    return new Acl();
});

/**
* Add routing capabilities
*/
$di->set('router', function(){
    require __DIR__.'/routes.php';
    return $router;
});

$di->set('config', function () use ($config) {
    return $config;
});