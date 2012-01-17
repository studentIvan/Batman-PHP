<?php
namespace Framework\Core;
use \Framework\Core\Config;
use \Framework\Core\Router;

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
         * Routing
         */
        extract(Router::directing());
        $loadString = '\\' . $bundle . '\\Controllers\\' . $controller;
        $_controller = new $loadString();

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
        $_controller->$method($option);
    }

}