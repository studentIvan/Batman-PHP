<?php
namespace Framework\Core;
use \Framework\Core\Config;

/**
 * [Bundle]::[Controller]::[Method]::[Option]
 */
class Router
{
    /**
     * @static
     * @return array
     */
    public static function directing()
    {
        $option = null;
        $method = 'index';
        $controller = 'Main';
        $defaultBundleLoaded = Config::get('application', 'default');
        $bundle = ($defaultBundleLoaded) ? $defaultBundleLoaded : 'Main';
        if (isset($_SERVER['QUERY_STRING'])) {
            $x = explode('::', htmlspecialchars($_SERVER['QUERY_STRING'], ENT_QUOTES, 'UTF-8'));
            if ($x && isset($x[0]) && !empty($x[0])) {
                $bundle = $x[0];
                if (isset($x[1]) && !empty($x[1])) {
                    $controller = $x[1];
                    if (isset($x[2]) && !empty($x[2])) {
                        $method = $x[2];
                        if (isset($x[3]) && !empty($x[3])) {
                            $option = $x[3];
                        }
                    }
                }
            }
        }
        $loadString = '\\' . $bundle . '\\Controllers\\' . $controller;
        if (!class_exists($loadString, true))
            $controller = 'Main';
        if (!method_exists($loadString, $method))
            $method = 'index';
        return array(
            'bundle' => $bundle,
            'controller' => $controller,
            'method' => $method,
            'option' => $option,
        );
	}
}