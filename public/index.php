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
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('PUBLIC_PATH') 
|| define('PUBLIC_PATH', realpath(dirname(__FILE__) . '/../public'));

defined('POLICY_PATH') 
|| define('POLICY_PATH', APPLICATION_PATH . DIRECTORY_SEPARATOR . 'policies');

// Load application config (error reporting, database credentials etc.)
require '../application/config/config.php';

// The auto-loader to load the php-login related internal stuff automatically
require '../application/config/autoload.php';

// The Composer auto-loader (official way to load Composer contents) to load external stuff automatically
if (file_exists('../vendor/autoload.php')) {
    require '../vendor/autoload.php';
}

$policies = require APPLICATION_PATH . DIRECTORY_SEPARATOR . 'config'. DIRECTORY_SEPARATOR . 'policies.php';

// Start our application
$app = new \MVC\Framework\Application( $policies );
