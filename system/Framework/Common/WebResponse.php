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

}
