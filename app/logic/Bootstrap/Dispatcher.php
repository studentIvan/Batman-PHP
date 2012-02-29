<?php
namespace Bootstrap;
use \Exception;
use \RuntimeException;
use \Exceptions\ForbiddenException;
use \Exceptions\NotFoundException;
use \Framework\Core\Config;
use \Framework\Common\Log;
use \Framework\Core\Template;
use \Framework\Common\SwiftMailer;

class Dispatcher
{
    public function __construct()
    {
        try {
            Bootstrap::boot();
        } catch (RuntimeException $e) {
            /**
             * Simple runtime errors handler
             */
            if (Config::get('framework', 'phpdebug')) {
                echo "RuntimeException: " . $e->getMessage() . "<br><pre>" . $e->getTraceAsString();
            } else {
                Log::writeException($e);
                echo "<b>Notice:</b> critical system error";
                if ($adminEmail = Config::get('application', 'admin_email'))
                {
                    $point = md5(date('Y-m-d H')) . '.log';
                    if (!file_exists('app/logs/' . $point))
                    {
                        Log::write('Runtime exception sended for admin', $point);
                        SwiftMailer::send(SwiftMailer::createMessage(
                            "Runtime exception [{$_SERVER['HTTP_HOST']}]",
                            $adminEmail, $e->getMessage()
                        ));
                    }
                }
            }
        } catch (ForbiddenException $e) {
            /**
             * Simple 403 error page
             */
            header('HTTP/1.0 403 Forbidden');
            $template = new Template();
            echo $template->render('403', 'native');
        } catch (NotFoundException $e) {
            /**
             * Simple 404 error page
             */
            header('HTTP/1.0 404 Not Found');
            $template = new Template();
            echo $template->render('404', 'native');
        } catch (Exception $e) {
            /**
             * Simple other exceptions handler
             */
            if (Config::get('framework', 'phpdebug')) {
                echo "Exception: " . $e->getMessage() . "<br><pre>" . $e->getTraceAsString();
            } else {
                Log::writeException($e);
                echo "<b>Notice:</b> system error";
            }
        }
    }
}
