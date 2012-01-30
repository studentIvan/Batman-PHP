<?php
namespace Framework\Packages;
use \Framework\Interfaces\PackageInterface;
use \Framework\Common\Database;

class UserAuth implements PackageInterface
{
    public function __construct() {

    }

    public function getPackageInfo() {
        return array(
            'about' => 'User Authorisation Helper for Batman-PHP',
            'version' => '0.1'
        );
    }
}
