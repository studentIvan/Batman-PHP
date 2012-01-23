<?php
namespace Framework\Common;
use \Symfony\Component\HttpFoundation\Response;

class WebResponse extends Response
{
    public function sendJSON() {
        $content = $this->getContent();
        $this->setContent(json_encode($content));
        $this->send();
    }
}
