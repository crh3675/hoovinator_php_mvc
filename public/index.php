<?php

/**
 * A simple, clean and secure PHP Login Script embedded into a small framework.
 * Also available in other version: one-file, minimal, advanced. See php-login.net for more info.
 *
 * MVC FRAMEWORK VERSION
 *
 * @author Panique
 * @link http://www.php-login.net
 * @link https://github.com/panique/php-login/
 * @license http://opensource.org/licenses/MIT MIT License
 */

defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', getenv('APPLICATION_ENV') || 'development');

defined('APPLICATION_PATH') 
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('PUBLIC_PATH') 
|| define('PUBLIC_PATH', realpath(dirname(__FILE__) . '/../public'));

defined('POLICY_PATH') 
|| define('POLICY_PATH', APPLICATION_PATH . DIRECTORY_SEPARATOR . 'policies');

defined('APPLICATION_KINT_DEBUG')
|| define('APPLICATION_KINT_DEBUG', FALSE);

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
$app = new Application( $policies );
