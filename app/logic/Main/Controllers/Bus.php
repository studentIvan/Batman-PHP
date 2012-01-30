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
    public function index(WebResponse $response, WebRequest $request) {
        /* write some code here */
    }

    public function verifyCaptcha(WebResponse $response, WebRequest $request) {
        $request->protectAjax();
        $captcha = new ReCaptcha();
        $response->send($captcha->verify($request), true);
    }

    public function getCaptchaPublicKey(WebResponse $response, WebRequest $request) {
        $request->protectAjax();
        $response->send(ReCaptcha::getPublicKey(), true);
    }
}