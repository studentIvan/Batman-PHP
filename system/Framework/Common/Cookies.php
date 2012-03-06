<?php
namespace Framework\Common;

/**
 * Simple cookie helper (native php, bypass symfony request/response)
 *
 * ! Use Symfony HttpFoundation for works with cookies:
 * $request->getCookie() or $request->cookies->get()
 * $response->setCookie() or $response->headers->setCookie()
 */
class Cookies
{
    /**
     * @param string $key
     * @return bool|string
     */
    public function get($key)
    {
        return isset($_COOKIE[$key]) ? htmlspecialchars($_COOKIE[$key], ENT_QUOTES) : false;
    }

    /**
     * @param string|array $key
     * @param string $value
     * @param bool|int $time
     * @param string $path
     * @param bool|string $host
     */
    public function set($key, $value = '', $time = false, $path = '/', $host = false)
    {
        if (is_array($key))
        {
            foreach ($key as $k => $v)
            {
                if (is_int($k))
                {
                    $k = $v;
                    $v = '';
                }

                self::set($k, $v);
            }
        }
        else
        {
            $time = $time || time()+28080000;
            $host = $host || ".{$_SERVER['HTTP_HOST']}";
            setcookie($key, $value, $time, $path, $host);
        }
    }
}
