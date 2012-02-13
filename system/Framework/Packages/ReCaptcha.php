<?php
namespace Framework\Packages;
use \Framework\Core\Config;
use \Framework\Common\WebRequest;

class ReCaptcha
{
    protected $publicKey;
    protected $privateKey;

    public function __construct() {
        $this->publicKey = Config::get('recaptcha', 'public_key');
        $this->privateKey = Config::get('recaptcha', 'private_key');
        include_once __DIR__ . '/ReCaptcha/recaptchalib.php';
    }

    /**
     * Get ReCaptcha public key
     * from application configuration
     *
     * @static
     * @return string
     */
    public static function getPublicKey() {
        $publicKey = Config::get('recaptcha', 'public_key');
        return ($publicKey) ? $publicKey : 'error';
    }

    /**
     * Verify ReCaptcha Request
     *
     * @param \Framework\Common\WebRequest $request
     * @return bool
     */
    public function verify(WebRequest $request) {
        $responseField = $request->postStr('recaptcha_response_field');
        $challengeField = $request->postStr('recaptcha_challenge_field');
        if ($responseField) {
            $reCaptchaResponse = recaptcha_check_answer(
                $this->privateKey, $request->getClientIp(), $challengeField, $responseField
            );
            $answer = $reCaptchaResponse->is_valid;
            unset($reCaptchaResponse);
            return $answer;
        } else {
            return false;
        }
    }
}
