<?php

/**
 * A simple, clean and secure PHP framework.
 *
 * MVC FRAMEWORK VERSION
 *
 */ 

defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', getenv('APPLICATION_ENV') || 'development');

defined('APPLICATION_PATH') 
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application') . DIRECTORY_SEPARATOR);

defined('LIBS_PATH')
|| define('LIBS_PATH',  realpath(dirname(__FILE__) . '/../system') . DIRECTORY_SEPARATOR);


// Load application config (error reporting, database credentials etc.)
require '../application/config/local.php';

// The auto-loader to load the php-login related internal stuff automatically
require '../autoload.php';

// The Composer auto-loader (official way to load Composer contents) to load external stuff automatically
if (file_exists('../vendor/autoload.php')) {
    require '../vendor/autoload.php';
}

$policyConfig = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'config'. DIRECTORY_SEPARATOR . 'policies.php';
$policies = array();

if(file_exists($policyConfig)) {
   $policies = require $policyConfig;
}

// Start our application
$app = new \MVC\Framework\Application( $policies );
