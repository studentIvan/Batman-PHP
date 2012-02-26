<?php
namespace Framework\Common;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Cookie;
use \Framework\Core\Config;
use \Zend\Registry;

class WebResponse extends Response
{
    /**
     * @param bool|string $content
     * @param bool $json
     */
    public function send($content = false, $json = false)
    {
        if ($content)
        {
            if ($json)
            {
                $this->setContent(json_encode($content));
                $this->headers->set('Content-Type', 'application/json');
            }
            else
            {
                $this->setContent($content);
                if ($this->headers->get('Content-Type') == '')
                    $this->headers->set('Content-Type', 'text/html');
            }
        }

        if (Config::get('framework', 'debug_toolbar'))
        {
            if ($this->headers->get('Content-Type') == 'text/html')
            {
                $this->setContent($this->_toolbarInject($this->getContent()));
            }
        }

        parent::send();
    }

    /**
     * @param string $message
     */
    public function sendForbidden($message = '')
    {
        $this->setStatusCode(403, 'Forbidden');
        $this->send($message);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Cookie $cookie
     */
    public function setCookie(Cookie $cookie)
    {
        $this->headers->setCookie($cookie);
    }

    /**
     * @param string $html
     * @return mixed
     */
    protected function _toolbarInject($html)
    {
        $registry = Registry::getInstance();
        $sqlDebugData = isset($registry->sql_debug_data) ? $registry->sql_debug_data : array();
        $sqlCounter = count($sqlDebugData);
        $sqlDebugDataJson = json_encode($sqlDebugData);
        $global = json_encode(array(
            'server' => $_SERVER,
            'post' => $_POST,
            'get' => $_GET,
            'cookie' => $_COOKIE,
        ));
        $memory = number_format(memory_get_usage() / 1024 / 1024, 3);
        $time = number_format((microtime(true) - DEBUG_TOOLBAR_START_TIME), 4);
        if ($time > 1) $time = "<a style='border: none; color: #ff6347;'>$time</a>";
        if ($time < 0.3) $time = "<a style='border: none; color: #C6E746;'>$time</a>";
        $toolbar_body = "Batman-PHP Debugger | Time: $time sec |
        <a href='#' title='Dump sql queries data into console'
        onclick='console.info(\"Queries information: %o\", $sqlDebugDataJson);'>DBAL Queries: $sqlCounter</a> |
        <a href='#' title='Dump global variables into console'
        onclick='console.info(\"Global variables: %o\", $global);'>Global variables</a> |
        Memory: {$memory} mb";
        ob_start(); include "system/Framework/Common/Debug/Views/toolbar.phtml";
        return str_replace('</body>', ob_get_clean() . '</body>', $html);
    }

}
