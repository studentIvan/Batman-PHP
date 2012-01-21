<?php
namespace Framework\Core;
 
class Terminator {

    /**
     * @static
     * @throws \ErrorException
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @return void
     */
    public static function terminate($errno, $errstr, $errfile, $errline) {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}