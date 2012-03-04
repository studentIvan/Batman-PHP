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

    public function authManual(WebResponse $response) {
        $response->send('Not supported');
    }

    public function index(WebResponse $response)
    {
        $response->send(
            $this->tpl
                ->match('auth', $this->session->isAuth())
                ->match('adminPath', Config::get('admin', 'path'))
                ->render('index', 'twig')
        );
    }

    /**
     * @return array|bool
     */
    private function _getAdministrativeMap()
    {
        if ($bundles = Config::get('admin', 'bundles')) {
            $map = array();
            foreach ($bundles as $bundle)
            {
                $map[$bundle] = array();
                foreach (glob("app/logic/$bundle/Solutions/*.php") as $solution)
                {
                    $solution = rtrim(basename($solution), '.php');
                    $map[$bundle][$solution] = array();
                    $className = "\\$bundle\\Solutions\\$solution";
                    foreach (get_class_methods($className) as $method)
                    {
                        $reflect = new \ReflectionMethod($className, $method);
                        if ($reflect->isPublic()) {
                            $reflectName = $reflect->getName();
                            if (!$phpDoc = $reflect->getDocComment()) continue;
                            if (strpos($phpDoc, '@administrative') == false) continue;
                            $map[$bundle][$solution][$reflectName] = array();
                            foreach ($reflect->getParameters() as $param)
                            {
                                /**
                                 * @var $param \ReflectionParameter
                                 */
                                if (!$param->isArray()) {
                                    $map[$bundle][$solution][$reflectName][] = $param->getName();
                                }
                                else
                                {
                                    /*
                                     * Arrays not supported in this version
                                     */
                                    unset($map[$bundle][$solution][$reflectName]);
                                    break;
                                }
                            }
                        }
                    }

                    if (count($map[$bundle][$solution]) == 0)
                    {
                        unset($map[$bundle][$solution]);
                    }
                }
            }

            return $map;
        }

        return array();
    }

    public function getMap(WebResponse $response, WebRequest $request)
    {
        $request->ifNotAjaxRequestThrowForbidden();
        $this->session->ifNotAuthorizedThrowForbidden();
        $map = $this->_getAdministrativeMap();
        $response->send(((count($map) > 0) ? $map : 0), true);
    }

    public function execute(WebResponse $response, WebRequest $request)
    {
        $request->ifNotAjaxRequestThrowForbidden();
        $this->session->ifNotAuthorizedThrowForbidden();
        $map = $this->_getAdministrativeMap();
        $methodParameters = array();
        if ($needle = $request->postStr('needle'))
        {
            list($bundle, $solution, $method) = explode(':', $needle);
            if (isset($map[$bundle][$solution][$method]))
            {
                foreach ($map[$bundle][$solution][$method] as $param)
                {
                    if ($receivedParam = $request->postStr($param))
                    {
                        $lowerReceivedParam = strtolower($receivedParam);
                        if ($lowerReceivedParam == 'false') $receivedParam = false;
                        if ($lowerReceivedParam == 'true') $receivedParam = true;
                        $methodParameters[] = $receivedParam;
                    }
                    else
                    {
                        $response->sendForbidden('some parameters are not received');
                    }
                }

                $classPath = "\\$bundle\\Solutions\\$solution";

                try
                {
                    $object = new $classPath();
                    call_user_func_array(array($object, $method), $methodParameters);
                    $response->send('ok');
                }
                catch (\Exception $e)
                {
                    $response->sendForbidden($e->getMessage());
                }
            }
            else
            {
                $response->sendForbidden('wrong needle');
            }
        }
        else
        {
            $response->sendForbidden('needle not received');
        }
    }

    public function out()
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
        $request->ifNotAjaxRequestThrowForbidden();
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