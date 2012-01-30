<?php
namespace Bootstrap;
use \Exception;
use \RuntimeException;
use \Exceptions\ForbiddenException;
use \Exceptions\NotFoundException;
use \Framework\Core\Config;
use \Framework\Common\Log;
use \Framework\Core\Template;

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
            echo "RuntimeException: " . $e->getMessage() .
                (Config::get('framework', 'phpdebug') ? "<br><pre>" . $e->getTraceAsString() : "");
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
