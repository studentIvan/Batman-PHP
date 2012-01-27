<?php
namespace Framework\Common;
use \Framework\Core\Config;

class SwiftMailer {

    /**
     * @var bool
     */
    protected static $registered = false;

    /**
     * Register Swift Mailer Dependency Map
     *
     * @static
     */
    protected static function register() {
        if (!self::$registered) {
            include_once 'vendor/swiftmailer/swiftmailer/lib/swift_required.php';
            self::$registered = true;
        }
    }

    /**
     * Send swift message(s)
     *
     * @static
     * @param \Swift_Mime_Message $message
     * @param bool $transport Use SMTP-transport (locate swift_transport in config.yml)
     * @return int
     * @throws \Swift_IoException
     */
    public static function send(\Swift_Mime_Message $message, $transport = true) {
        if (!self::$registered) self::register();
        if ($transport)
        {
            if (!$host = Config::get('swift_transport', 'host'))
                throw new \Swift_IoException('Error loading swift_transport host configuration');
            if (!$username = Config::get('swift_transport', 'username'))
                throw new \Swift_IoException('Error loading swift_transport username configuration');
            if (!$password = Config::get('swift_transport', 'password'))
                throw new \Swift_IoException('Error loading swift_transport password configuration');

            list($server, $port) = explode(':', $host);
            $swiftTransport =
                \Swift_SmtpTransport::newInstance($server, $port)
                    ->setUsername($username)
                    ->setPassword($password)
                ;
        } else {
            $swiftTransport = Swift_MailTransport::newInstance();
        }
        $mailer = \Swift_Mailer::newInstance($swiftTransport);
        return $mailer->send($message);
    }

    /**
     * Create swift message object
     *
     * @static
     * @param string $subject
     * @param array|string $to
     * @param string $body
     * @return \Swift_Mime_Message
     * @throws \Swift_IoException
     */
    public static function createMessage($subject, $to, $body) {
        if (!self::$registered) self::register();
        if (!$email = Config::get('swift_vcard', 'email'))
            throw new \Swift_IoException('Error loading swift_vcard email configuration');
        if (!$firstName = Config::get('swift_vcard', 'first_name'))
            throw new \Swift_IoException('Error loading swift_vcard first_name configuration');
        if (!$lastName = Config::get('swift_vcard', 'last_name'))
            throw new \Swift_IoException('Error loading swift_vcard last_name configuration');

        return
            \Swift_Message::newInstance($subject)
                ->setTo($to)
                ->setFrom(array($email => "$firstName $lastName"))
                ->setBody($body)
            ;
    }
}