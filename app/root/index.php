<?php
use Bootstrap\Dispatcher as WebApplicationStarter;
define('DEBUG_TOOLBAR_START_TIME', microtime(true));
require_once '../../autoload.php';
new WebApplicationStarter();