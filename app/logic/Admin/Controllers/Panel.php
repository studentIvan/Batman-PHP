<?php
namespace Admin\Controllers;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;
use \Framework\Core\Controller;
use \Framework\Core\Config;
use \Exceptions\ForbiddenException;
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
        $this->session = new \Framework\Packages\UserAuth('NativePhpSession');
        if (Config::get('framework', 'debug_toolbar'))
        {
            $frameworkCfg = Config::get('framework');
            $frameworkCfg['debug_toolbar'] = false;
            Config::set('framework', $frameworkCfg);
        }
    }

    public function index(WebResponse $response, WebRequest $request)
    {
        $this->tpl->match('session', array(
            'auth' => $this->session->isAuth(),
            'login' => $this->session->getLogin(),
        ));
        $response->send($this->tpl->render('index', 'twig'));
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