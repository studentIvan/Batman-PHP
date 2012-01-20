<?php
namespace Bootstrap;

/**
 * Bootstrap
 */
class Bootstrap extends \Framework\Core\WebApplication {
    public static function boot() {
        try {
            parent::boot();
        } catch (\Exception $e) {
            echo "Exception: " . $e->getMessage() . "<br><pre>" . $e->getTraceAsString();
        }
    }
}
