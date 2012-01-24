<?php
namespace Framework\Packages;
use \Framework\Interfaces\Package;

class UserAuth implements Package
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
