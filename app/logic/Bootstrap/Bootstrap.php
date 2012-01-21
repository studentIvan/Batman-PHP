<?php
namespace Bootstrap;
use \Framework\Core\Config;
use \Framework\Common\Log;
/**
 * Bootstrap
 */
class Bootstrap extends \Framework\Core\WebApplication {
    public static function boot() {
        try {
            parent::boot();
        } catch (\Exception $e) {
            if (Config::get('framework', 'phpdebug')) {
                echo "Exception: " . $e->getMessage() . "<br><pre>" . $e->getTraceAsString();
            } else {
                Log::writeException($e);
                echo "<b>Notice:</b> system error";
            }
        }
    }
}
