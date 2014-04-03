<?php

/**
 * A simple, clean and secure PHP framework.
 *
 * MVC FRAMEWORK VERSION
 *
 */ 
 
define('URL', 'http://maas-ads.localhost/');

define('DEFAULT_CONTROLLER', 'Main');
define('ERROR_CONTROLLER' ,  'ErrorController::display');

define('APPLICATION_ENV',  getenv('APPLICATION_ENV') || 'development');
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application') . DIRECTORY_SEPARATOR);
define('SYSTEM_PATH',      realpath(dirname(__FILE__) . '/../system') . DIRECTORY_SEPARATOR);
define('PUBLIC_PATH',      realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

define('CONTROLLER_PATH',  APPLICATION_PATH . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR);
define('MODELS_PATH',      APPLICATION_PATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH',       APPLICATION_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);
define('LAYOUTS_PATH',     APPLICATION_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR);
define('POLICY_PATH',      APPLICATION_PATH . DIRECTORY_SEPARATOR . 'policies' . DIRECTORY_SEPARATOR);

   
define('COOKIE_RUNTIME', 1209600);
// the domain where the cookie is valid for, for local development ".127.0.0.1" and ".localhost" will work
// IMPORTANT: always put a dot in front of the domain, like ".mydomain.com" !
define('COOKIE_DOMAIN', '.localhost');

// Load application config (error reporting, database credentials etc.)
require '../application/config/local.php';

// The auto-loader to load the php-login related internal stuff automatically
require '../autoload.php';

// The Composer auto-loader (official way to load Composer contents) to load external stuff automatically
if (file_exists('../vendor/autoload.php')) {
    require '../vendor/autoload.php';
}

// Load up the bootstrap file.  Bootstrap starts the application
require '../bootstrap.php';
