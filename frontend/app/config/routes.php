<?php

$router = new Phalcon\Mvc\Router();

//Define a route
$router->add(
    "/:controller/:action/:params",
    array(
        "controller" => 1,
        "action"     => 2,
        "params"     => 3,
    )
);