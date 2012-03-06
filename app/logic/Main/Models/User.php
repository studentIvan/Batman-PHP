<?php
namespace Main\Models;
use \Framework\Common\Security;

class User extends \Framework\Common\Model
{
    public $username, $password, $created;

    public function __construct($username, $password = null)
    {
        $this->username = $username;
        $this->created = date('Y-m-d H:i:sP');
        if ($password) $this->setPassword($password);
    }

    public function setPassword($password)
    {
        $this->password = Security::getHash($password);
    }
}
