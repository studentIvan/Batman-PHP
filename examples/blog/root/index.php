<?php
use Bootstrap\Dispatcher as WebApplicationStarter;
define('DEBUG_TOOLBAR_START_TIME', microtime(true));
define('FRAMEWORK_PATH', __DIR__ . '/../../');
define('APPLICATION_PATH', str_replace('root', '', __DIR__));
require_once FRAMEWORK_PATH . 'autoload.php';
new WebApplicationStarter();