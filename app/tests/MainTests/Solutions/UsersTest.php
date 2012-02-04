<?php
namespace MainTests\Solutions;
use \Main\Solutions\Users;
use \Framework\Common\Database;
use \PHPUnit_Framework_TestCase as TestCase;

require_once 'PHPUnit/Autoload.php';

/**
 * Unit test of solution Users
 *
 * @todo create method in this test, e.g. test
 *
 * Using: php bin/manager.php solution:test Users
 * for specific bundle use: php bin/manager.php solution:test Users mytest Main
 *
 * See PHPUnit manual for more:
 * @link http://www.phpunit.de/manual/3.6/en/writing-tests-for-phpunit.html
 */

class UsersTest extends TestCase {

    public function __construct() {
        $this->component = new Users(Database::newInstance());
    }

    public function test() {

    }
    
}