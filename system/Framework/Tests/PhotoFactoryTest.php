<?php
namespace Framework\Tests;
require_once 'PHPUnit/Autoload.php';
use \Framework\Common\PhotoFactory;
 
class PhotoFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $photo = new PhotoFactory();
        $result = $photo->open(APPLICATION_PATH . 'root/images/other/browsers.png');
        var_dump($result);
    }
}