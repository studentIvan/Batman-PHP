<?php
namespace Framework\Tests;
require_once 'PHPUnit/Autoload.php';
use \Framework\Core\Config;
 
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $defaultBundle = Config::get('application', 'default');
        $this->assertTrue((is_string($defaultBundle) && strlen($defaultBundle) > 0));
    }
}