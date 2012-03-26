<?php
namespace Framework\Core;

use \Framework\Core\Config,
    \Framework\Core\Router,
    \Framework\Core\Template,
    \Framework\Common\WebRequest,
    \Framework\Common\WebResponse,
    \Exceptions\NotFoundException,
    \Exceptions\ForbiddenException;

class WebApplication
{
    /**
     * @static
     * @return void
     */
    public static function boot()
    {
        if (Config::get('application', 'forbidden_all')) {
            throw new ForbiddenException('Application was turned off');
        }

        $option = null;
        $bundleName = $controllerName = $methodName = '';

        /**
         * Application Error Resolver
         */
        set_error_handler('\\Framework\\Core\\Terminator::terminate');

        /**
         * Routing
         */
        extract(Router::directing());
        $loadString = "\\$bundleName\\Controllers\\$controllerName";
        $controller = new $loadString();
        if ($controller instanceof Controller) {
            $controller->tpl = new Template($bundleName);
        }

        /**
         * Loading "autoload_solutions"
         * @see app/config/config.yml
         */
        if ($autoloadSolutions = Config::get('application', 'autoload_solutions'))
        {
            foreach ($autoloadSolutions as $marker)
            {
                list($autoloadBundle, $loadedSolution) = explode(':', strtolower($marker));
                $loadString = '\\' . $autoloadBundle . '\\Solutions\\' . ucfirst($loadedSolution);
                $controller->$loadedSolution = new $loadString();
            }
        }

        /**
         * Run controller method
         */
        $request = WebRequest::createFromGlobals();
        $response = new WebResponse();

        /**
         * @var $result \Framework\Common\WebResponse
         */
        $result = ($option !== null) ?
            $controller->$methodName($option, $request, $response) :
            $controller->$methodName($request, $response);

        if (!is_null($result))
        {
            if (is_string($result))
            {
                /*
                * since 0.2.1-ALPHA-DEV
                * return 'hello world';
                */
                $response->send($result);
            }
            elseif ($result === 403)
            {
                /*
                * return 403;
                */
                throw new ForbiddenException();
            }
            elseif ($result === 404)
            {
                /*
                * return 404;
                */
                throw new NotFoundException();
            }
            elseif (is_numeric($result))
            {
                /*
                * since 0.2.2-ALPHA-DEV
                * return 'hello world';
                */
                $response->send($result);
            }
        }
    }
}