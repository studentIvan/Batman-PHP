<?php
namespace Framework\Common;
use \Symfony\Component\HttpFoundation\Response;

class WebResponse extends Response
{
    /**
     * @param mixed $content
     */
    public function setJSON($content = false) {
        $this->setContent(json_encode($content));
        $this->headers->set('Content-Type', 'application/json');
    }

    /**
     * @param bool|string $content
     * @param bool $json
     */
    public function send($content = false, $json = false)
    {
        if ($content)
        {
            if ($json) {
                $this->setJSON($content);
            } else {
                $this->setContent($content);
            }
        }

        $this->sendHeaders();
        $this->sendContent();

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
    }

}
