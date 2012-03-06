<?php
namespace Framework\Core;

use \Framework\Core\Config,
    \Exceptions\NotFoundException;

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

        if (!$bundle = Config::get('application', 'default'))
            $bundle = 'Main';

        if (isset($_SERVER['QUERY_STRING']))
        {
            $x = explode('::', htmlspecialchars($_SERVER['QUERY_STRING'], ENT_QUOTES, 'UTF-8'));
            if ($x && isset($x[0]) && !empty($x[0]))
            {
                $bundle = $x[0];
                if (isset($x[1]) && !empty($x[1]))
                {
                    $controller = $x[1];
                    if (isset($x[2]) && !empty($x[2]))
                    {
                        $method = $x[2];
                        if (isset($x[3]) && !empty($x[3]))
                        {
                            $option = $x[3];
                            if ($option == '')
                                $option = null;
                        }
                    }
                }
            }
        }

        $loadString = "\\$bundle\\Controllers\\$controller";
        $found = (class_exists($loadString, true) && method_exists($loadString, $method));

        if (!$found) {
            throw new NotFoundException($loadString);
        }

        return array(
            'bundleName' => $bundle,
            'controllerName' => $controller,
            'methodName' => $method,
            'option' => $option,
        );
	}
}