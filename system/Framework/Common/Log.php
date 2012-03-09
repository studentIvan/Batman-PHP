<?php
namespace Framework\Common;
use \Framework\Core\Config;
 
class Log
{
    /**
     * @static
     * @param string $message
     * @param bool|string $filename
     * @return bool
     */
    public static function write($message, $filename = false)
    {
        if (is_array($message)) $message = serialize($message);
        $filename = 'app/logs/' . (($filename) ? $filename : date("m.d.y")) . '.log';
        $maxSize = Config::get('application', 'log_file_max_size_mb');
        if ($maxSize) {
            $maxSize *= (1024 * 1024);
            if (file_exists($filename) && filesize($filename) > $maxSize) {
                return false;
            }
        }
        $message = date("F j, Y, g:i a") . "::: " . str_replace(array("\r", "\n", "\t"), '', trim($message)) . ".\r\n";
        $log = fopen($filename, "ab");
        fseek($log, 0);
        fwrite($log, $message);
        fclose($log);
        return true;
    }

    /**
     * @static
     * @param \Exception $e
     * @return void
     */
    public static function writeException(\Exception $e)
    {
        self::write("Exception: {$e->getMessage()}, at {$e->getFile()}:{$e->getLine()}", 'errors');
    }
}
