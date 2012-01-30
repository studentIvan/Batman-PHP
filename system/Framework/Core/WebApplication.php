<?php
namespace Framework\Core;
use \Framework\Core\Config;
use \Framework\Core\Router;
use \Framework\Core\Template;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;

class WebApplication
{
    /**
     * @static
     * @return void
     */
    public static function boot()
    {
        if (Config::get('application', 'forbidden_all')) {
            throw new \Exceptions\ForbiddenException('Application was turned off');
        }

        $option = null;
        $bundle = $controller = $method = '';

        /**
         * Application Error Resolver
         */
        set_error_handler('\\Framework\\Core\\Terminator::terminate');

        /**
         * Routing
         */
        extract(Router::directing());
        $loadString = '\\' . $bundle . '\\Controllers\\' . $controller;
        $_controller = new $loadString();
        if ($_controller instanceof Controller) {
            $_controller->tpl = new Template($bundle);
        }

        /**
         * Loading "autoload_solutions"
         * @see app/config/config.yml
         */
        $_solutions = Config::get('application', 'autoload_solutions');
        if ($_solutions) {
            foreach ($_solutions as $__solution) {
                list($_bundle, $_solution) = explode(':', strtolower($__solution));
                $loadString = '\\' . $_bundle . '\\Solutions\\' . ucfirst($_solution);
                $_controller->$_solution = new $loadString();
            }
        }

        /**
         * Run controller method
         */
        $request = WebRequest::createFromGlobals();
        $response = new WebResponse();

        if ($option !== null) {
            $_controller->$method($option, $response, $request);
        } else {
            $_controller->$method($response, $request);
        }

    }
}