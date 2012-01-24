<?php
namespace Main\Controllers;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;
use \Framework\Core\Controller;
use \Framework\Packages\ReCaptcha;

/**
 * Bus controller
 * 
 * @link http://api.symfony.com/2.0/Symfony/Component/HttpFoundation.html
 */
class Bus extends Controller
{
    public function index(WebRequest $request, WebResponse $response) {
        /* write some code here */
    }

    public function verifyCaptcha(WebRequest $request, WebResponse $response) {
        $request->protectAjax();
        $captcha = new ReCaptcha();
        $response->setJSON($captcha->verify($request));
        $response->send();
    }

    public function getCaptchaPublicKey(WebRequest $request, WebResponse $response) {
        $request->protectAjax();
        $response->setJSON(ReCaptcha::getPublicKey());
        $response->send();
    }
}