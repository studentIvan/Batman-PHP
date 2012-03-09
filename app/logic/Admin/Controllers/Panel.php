<?php
namespace Admin\Controllers;

use \Framework\Core\Config,
    \Framework\Common\WebRequest,
    \Framework\Common\WebResponse,
    \Exceptions\ForbiddenException,
    \Framework\Common\Security,
    \Framework\Common\Database,
    \Framework\Packages\UserAuth,
    \Framework\Packages\UserAuth\Exceptions\AuthException;

/**
 * Batman Admin Panel
 */
class Panel extends \Framework\Core\Controller
{
    /**
     * @var \Framework\Packages\UserAuth
     */
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

    public function authManual()
    {
        return 'Not supported';
    }

    public function index()
    {
        return $this->tpl
            ->match('auth', $this->session->isAuth())
            ->match('adminPath', Config::get('admin', 'path'))
            ->render('panel', 'twig');
    }

    /**
     * @return array|bool
     */
    private function _getAdministrativeMap()
    {
        if ($bundles = Config::get('admin', 'bundles'))
        {
            $map = array();

            foreach ($bundles as $bundle)
            {
                $map[$bundle] = array();

                foreach (glob("app/logic/$bundle/Solutions/*.php") as $solution)
                {
                    $solution = preg_replace('/^(\S+)\.php$/', '$1', basename($solution));

                    $map[$bundle][$solution] = array();
                    $className = "\\$bundle\\Solutions\\$solution";

                    foreach (get_class_methods($className) as $method)
                    {
                        $reflect = new \ReflectionMethod($className, $method);

                        if ($reflect->isPublic())
                        {
                            $reflectName = $reflect->getName();
                            if (strpos($reflectName, '_') !== false) continue;

                            if ($phpDoc = $reflect->getDocComment())
                            {
                                if (Config::get('admin', 'admin_method_only') and
                                    strpos($phpDoc, '@admin_method') == false) continue;

                                preg_match('/@admin_method ([^\n]+)\n/', $phpDoc, $matchData);

                                $map[$bundle][$solution][$reflectName]['desc'] =
                                    (isset($matchData[1])) ? trim($matchData[1]) : '';
                            }
                            else
                            {
                                if (Config::get('admin', 'admin_method_only')) continue;
                            }

                            $map[$bundle][$solution][$reflectName]['p'] = array();

                            foreach ($reflect->getParameters() as $param)
                            {
                                /**
                                 * @var $param \ReflectionParameter
                                 */
                                if (!$param->isArray())
                                {
                                    $opt = $param->isOptional() ? '_' : '';
                                    $map[$bundle][$solution]
                                        [$reflectName]['p'][] = $opt . $param->getName();
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

        if ($needle = $request->postStr('needle'))
        {
            if ($needle == 'SQL')
            {
                if ($sql = $request->postStr('_sql'))
                {
                    try
                    {
                        $db = Database::getInstance($request->postStr('_dbcfg'));
                        $security = new Security();
                        $query = $db->query($sql);

                        try {
                            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
                        } catch (\Exception $e) {
                            $result =  null;
                        }

                        $response->send((is_null($result) ? 'ok' : $security->mixedClean($result, true)), true);
                    }
                    catch (\Exception $e)
                    {
                        $response->sendForbidden($e->getMessage());
                    }
                }
            }
            else
            {
                $map = $this->_getAdministrativeMap();
                $methodParameters = array();

                list($bundle, $solution, $method) = explode(':', $needle);

                if (isset($map[$bundle][$solution][$method]))
                {
                    foreach ($map[$bundle][$solution][$method]['p'] as $param)
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
                            if (strpos($param, '_') !== 0) {
                                $response->sendForbidden('some parameters are not received');
                            } else {
                                $methodParameters[] = false;
                            }
                        }
                    }

                    $classPath = "\\$bundle\\Solutions\\$solution";

                    try
                    {
                        $security = new Security();
                        $object = new $classPath();
                        $result = call_user_func_array(array($object, $method), $methodParameters);
                        $response->send((is_null($result) ? 'ok' : $security->mixedClean($result, true)), true);
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
            header('Location: /' . Config::get('admin', 'path'));
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