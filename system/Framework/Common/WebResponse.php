<?php
namespace Framework\Common;
use \Symfony\Component\HttpFoundation\Response;
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

    protected function _toolbarInject($html)
    {
        $registry = Registry::getInstance();
        $sqlCounter = isset($registry->debugger_sql_counter) ? $registry->debugger_sql_counter : 0;
        $toolbar_body = "Batman-PHP Debugger | Time: 0.000 ms | DBAL Queries: $sqlCounter";
        ob_start(); include "system/Framework/Common/Debug/Views/toolbar.phtml";
        return str_replace('</body>', ob_get_clean() . '</body>', $html);
    }

}
