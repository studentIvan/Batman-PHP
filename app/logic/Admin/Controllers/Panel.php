<?php
namespace Admin\Controllers;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;

use \Exceptions\ForbiddenException;
use \Framework\Core\Controller;
use \Framework\Core\Config;

use \Framework\Packages\UserAuth;
use \Framework\Packages\UserAuth\Exceptions\AuthException;

/**
 * Panel controller
 *
 */
class Panel extends Controller
{
    protected $session;

    public function __construct()
    {
        $this->session = new UserAuth();
        if (Config::get('framework', 'debug_toolbar'))
        {
            $frameworkCfg = Config::get('framework');
            $frameworkCfg['debug_toolbar'] = false;
            Config::set('framework', $frameworkCfg);
        }
    }

    public function index(WebResponse $response)
    {
        $response->send(
            $this->tpl
                ->match('auth', $this->session->isAuth())
                ->render('index', 'twig')
        );
    }

    public function out(WebResponse $response)
    {
        try {
            $this->session->out();
            header('Location: /admin/');
        } catch (AuthException $e) {
            throw new ForbiddenException($e->getMessage());
        }
    }

    public function auth(WebResponse $response, WebRequest $request)
    {
        $request->protectAjax();
        $login = $request->postStr('login');
        $password = $request->postStr('password');
        $cfgLogin = Config::get('admin', 'login');
        $cfgPasswd = Config::get('admin', 'password');
        if ($login == $cfgLogin and $password == $cfgPasswd)
        {
            try
            {
                $this->session->auth($login);
                $response->send(true, true);
            }
            catch (AuthException $e)
            {
                $response->sendForbidden($e->getMessage());
            }
        }
        else
        {
            $response->sendForbidden('Wrong login or password');
        }
    }
}