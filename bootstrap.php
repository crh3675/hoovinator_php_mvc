<?php

use PHPRouter\Route; 
use PHPRouter\RouteCollection;

// load up policies
$policyConfig = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'config'. DIRECTORY_SEPARATOR . 'policies.php';
$policies = array();

if(file_exists($policyConfig)) {
   $policies = require $policyConfig;
}

// load up routes
$routeConfig = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'config'. DIRECTORY_SEPARATOR . 'routes.php';
$routes = array();

if(file_exists($routeConfig)) {
   $routes = require $routeConfig;
}

// build routes
$collection = new RouteCollection();
$collection->add('error', new Route('/error/display', array(
    '_controller' => ERROR_CONTROLLER,
    'methods' => 'GET'
)));

foreach($routes as $name => $details){
   $collection->add($name, new Route($details['route'], array(
       '_controller' => $details['controller'],
       'methods' => isset($details['methods']) ? $details['methods'] : 'GET'
   )));
}

// Start our application
$app = new \MVC\Framework\Application( $collection,  $policies );