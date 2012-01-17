<?php
/**
 * PHP T.H.O.R.
 */
error_reporting(-1);
chdir('../..');
require_once 'autoload.php';
$defaultBundle = Framework\Core\Config::get('application', 'default');
Bootstrap\Bootstrap::boot();