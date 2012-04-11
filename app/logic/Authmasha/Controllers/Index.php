<?php
namespace Authmasha\Controllers;

use \Framework\Packages\UserAuth;

class Index extends \Framework\Core\Controller
{
    protected $session;

    public function __construct()
    {
        $this->session = new UserAuth('Masha');
        $this->session->setConfiguration(array(
            'usersTable' => 'users',
            'passwordColumn' => 'password',
            'uidColumn' => 'id',
            'emailColumn' => 'username', // use username column as auth controller
            'sessionsTable' => 'sessions',
        ));
    }

    public function index()
    {
        $authStatus = $this->session->isAuth() ? 'true' : 'false';
        return "<body>
        <p>UserAuth:Masha Tester</p>
        <p><a href='/masha/login'>Login as Someuser:12345</a> | <a href='/masha/logout'>Logout</a></p>
        <p>session->isAuth: $authStatus</p>
        <p>session->getUserId: {$this->session->getUserId()}</p>
        <p>session->getHash: {$this->session->getHash()}</p>
        </body>";
    }

    public function login()
    {
        try
        {
            $this->session->auth('Someuser', 12345);
            return '<body><a href="/masha">Login successful, go back</a></body>';
        }
        catch (UserAuth\Exceptions\AuthException $e)
        {
            return "<body><h2>{$e->getMessage()}</h2>
            <p><a href='/masha'>Go back</a></p>
            <pre>{$e->getTraceAsString()}</pre></body>";
        }
    }

    public function logout()
    {
        try
        {
            $this->session->out();
            return '<body><a href="/masha">Logout successful, go back</a></body>';
        }
        catch (UserAuth\Exceptions\AuthException $e)
        {
            return "<body><h2>{$e->getMessage()}</h2>
            <p><a href='/masha'>Go back</a></p>
            <pre>{$e->getTraceAsString()}</pre></body>";
        }
    }
}