<?php
namespace Framework\Core;
use \Framework\Core\Config;
use \Framework\Core\Router;
use \Framework\Core\Template;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;

class WebApplication {

    /**
     * @static
     * @return void
     */
    public static function boot()
    {
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
        $_controller->tpl = new Template($bundle);

        /**
         * Loading "autoload_solutions"
         * @see app/config/config.yml
         */
        $_solutions = Config::get('application', 'autoload_solutions');
        if ($_solutions) {
            foreach ($_solutions as $_solution) {
                $_solution = strtolower($_solution);
                $loadString = '\\' . $bundle . '\\Solutions\\' . ucfirst($_solution);
                $_controller->$_solution = new $loadString();
            }
        }

        /**
         * Run controller method
         */
        $request = WebRequest::createFromGlobals();
        $response = new WebResponse();

        if ($option !== null) {
            $_controller->$method($option, $request, $response);
        } else {
            $_controller->$method($request, $response);
        }

    }

}