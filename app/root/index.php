<?php
/**
 * Batman PHP
 */
error_reporting(-1);
ini_set('display_errors', 'On');
chdir('../..');
require_once 'autoload.php';
$defaultBundle = Framework\Core\Config::get('application', 'default');
Bootstrap\Bootstrap::boot();